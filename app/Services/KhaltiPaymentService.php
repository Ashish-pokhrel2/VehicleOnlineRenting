<?php

namespace App\Services;

use App\Models\BookingPayment;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class KhaltiPaymentService
{
    public function initiate(BookingPayment $payment, User $customer, string $returnUrl): array
    {
        $payload = [
            'return_url' => $returnUrl,
            'website_url' => config('app.url'),
            'amount' => (int) round($payment->amount * 100),
            'purchase_order_id' => $payment->purchase_order_id,
            'purchase_order_name' => $payment->purchase_order_name,
            'customer_info' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $this->normalizePhone($customer),
            ],
            'amount_breakdown' => [
                [
                    'label' => 'Rental amount',
                    'amount' => (int) round(($payment->amount - $payment->service_fee) * 100),
                ],
                [
                    'label' => 'Service fee',
                    'amount' => (int) round($payment->service_fee * 100),
                ],
            ],
            'merchant_extra' => (string) $payment->booking_id,
        ];

        $data = $this->post('/epayment/initiate/', $payload, 'initiate');

        if (! is_array($data) || empty($data['pidx']) || empty($data['payment_url'])) {
            throw new RuntimeException('Khalti returned an invalid initiation response.');
        }

        if (config('services.khalti.verify_checkout_url')) {
            $this->verifyCheckoutUrl((string) $data['payment_url']);
        }

        return $data;
    }

    public function lookup(string $pidx): array
    {
        $data = $this->post('/epayment/lookup/', [
            'pidx' => $pidx,
        ], 'lookup');

        if (! is_array($data)) {
            throw new RuntimeException('Khalti returned an invalid lookup response.');
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function post(string $endpoint, array $payload, string $operation): array
    {
        $secretKey = config('services.khalti.secret_key');
        $baseUrl = rtrim((string) config('services.khalti.base_url'), '/');

        if ($secretKey === '' || $secretKey === null) {
            throw new RuntimeException('Khalti secret key is not configured.');
        }

        if ($baseUrl === '') {
            throw new RuntimeException('Khalti base URL is not configured.');
        }

        $startedAt = microtime(true);

        try {
            $response = Http::baseUrl($baseUrl)
                ->acceptJson()
                ->asJson()
                ->timeout((int) config('services.khalti.timeout', 10))
                ->connectTimeout((int) config('services.khalti.connect_timeout', 3))
                ->withHeaders([
                    'Authorization' => 'Key '.$secretKey,
                ])
                ->post($endpoint, $payload);
        } catch (ConnectionException $exception) {
            $this->logRequest('warning', $operation, null, $startedAt, [
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException(
                'Khalti payment gateway is not reachable right now. Please try again in a moment.',
                0,
                $exception
            );
        }

        $this->logRequest($response->successful() ? 'info' : 'warning', $operation, $response, $startedAt);

        if (! $response->successful()) {
            throw new RuntimeException("Unable to {$operation} Khalti payment.");
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException("Khalti returned an invalid {$operation} response.");
        }

        return $data;
    }

    private function verifyCheckoutUrl(string $paymentUrl): void
    {
        $startedAt = microtime(true);

        try {
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->get($paymentUrl);
        } catch (ConnectionException $exception) {
            $this->logRequest('warning', 'checkout_url', null, $startedAt, [
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Khalti checkout page is currently unavailable.', 0, $exception);
        }

        $this->logRequest($response->successful() ? 'info' : 'warning', 'checkout_url', $response, $startedAt);

        if (! $response->successful()) {
            throw new RuntimeException('Khalti checkout page is currently unavailable.');
        }
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function logRequest(string $level, string $operation, ?Response $response, float $startedAt, array $context = []): void
    {
        Log::log($level, 'Khalti payment request completed.', array_filter([
            'operation' => $operation,
            'status' => $response?->status(),
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            'response' => $response && ! $response->successful() ? $response->json() : null,
        ] + $context, fn ($value): bool => $value !== null));
    }

    private function normalizePhone(User $customer): string
    {
        $phone = preg_replace('/\D+/', '', sprintf('%s%s', $customer->country_code, $customer->phone));

        return is_string($phone) && $phone !== '' ? $phone : (string) $customer->phone;
    }
}
