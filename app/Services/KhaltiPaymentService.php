<?php

namespace App\Services;

use App\Models\BookingPayment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KhaltiPaymentService
{
    public function initiate(BookingPayment $payment, User $customer, string $returnUrl): array
    {
        $secretKey = config('services.khalti.secret_key');
        $baseUrl = rtrim((string) config('services.khalti.base_url'), '/');

        if ($secretKey === '' || $secretKey === null) {
            throw new RuntimeException('Khalti secret key is not configured.');
        }

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

        $response = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->connectTimeout(5)
            ->withHeaders([
                'Authorization' => 'Key '.$secretKey,
            ])
            ->post('/epayment/initiate/', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to initiate Khalti payment.');
        }

        $data = $response->json();

        if (! is_array($data) || empty($data['pidx']) || empty($data['payment_url'])) {
            throw new RuntimeException('Khalti returned an invalid initiation response.');
        }

        return $data;
    }

    public function lookup(string $pidx): array
    {
        $secretKey = config('services.khalti.secret_key');
        $baseUrl = rtrim((string) config('services.khalti.base_url'), '/');

        if ($secretKey === '' || $secretKey === null) {
            throw new RuntimeException('Khalti secret key is not configured.');
        }

        $response = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->connectTimeout(5)
            ->withHeaders([
                'Authorization' => 'Key '.$secretKey,
            ])
            ->post('/epayment/lookup/', [
                'pidx' => $pidx,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to verify Khalti payment.');
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('Khalti returned an invalid lookup response.');
        }

        return $data;
    }

    private function normalizePhone(User $customer): string
    {
        $phone = preg_replace('/\D+/', '', sprintf('%s%s', $customer->country_code, $customer->phone));

        return is_string($phone) && $phone !== '' ? $phone : (string) $customer->phone;
    }
}
