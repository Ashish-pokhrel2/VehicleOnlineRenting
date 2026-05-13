<?php

namespace App\Services;

use App\Models\BookingPayment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EsewaEpayPaymentService
{
    /**
     * @return array<string, string>
     */
    public function paymentPayload(BookingPayment $payment, string $successUrl, string $failureUrl): array
    {
        $amount = $this->formatAmount($payment->amount);
        $payload = [
            'amount' => $amount,
            'tax_amount' => '0',
            'total_amount' => $amount,
            'transaction_uuid' => $payment->purchase_order_id,
            'product_code' => $this->productCode(),
            'product_service_charge' => '0',
            'product_delivery_charge' => '0',
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ];

        $payload['signature'] = $this->signPayload($payload['signed_field_names'], $payload);

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function decodeSuccessPayload(string $encodedPayload): array
    {
        $decoded = base64_decode($encodedPayload, true);

        if ($decoded === false) {
            throw new RuntimeException('eSewa returned an invalid payment response.');
        }

        $payload = json_decode($decoded, true);

        if (! is_array($payload)) {
            throw new RuntimeException('eSewa returned an invalid payment response.');
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function status(BookingPayment $payment): array
    {
        $response = Http::acceptJson()
            ->timeout(15)
            ->connectTimeout(5)
            ->retry(2, 200, throw: false)
            ->get($this->statusUrl(), [
                'product_code' => $this->productCode(),
                'total_amount' => $this->formatAmount($payment->amount),
                'transaction_uuid' => $payment->purchase_order_id,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException($this->failureMessage($response->json(), 'Unable to verify eSewa payment.'));
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('eSewa returned an invalid status response.');
        }

        return $data;
    }

    public function signPayload(string $signedFieldNames, array $payload): string
    {
        $message = $this->buildSignatureMessage($signedFieldNames, $payload);
        $hash = hash_hmac('sha256', $message, $this->secretKey(), true);

        return base64_encode($hash);
    }

    public function buildSignatureMessage(string $signedFieldNames, array $payload): string
    {
        $fields = array_map('trim', explode(',', $signedFieldNames));
        $segments = [];

        foreach ($fields as $field) {
            $segments[] = $field.'='.(string) ($payload[$field] ?? '');
        }

        return implode(',', $segments);
    }

    public function verifySignature(string $signedFieldNames, array $payload, string $signature): bool
    {
        return hash_equals($this->signPayload($signedFieldNames, $payload), $signature);
    }

    public function epayUrl(): string
    {
        $epayUrl = (string) config('services.esewa.epay_url');

        if ($epayUrl === '') {
            throw new RuntimeException('eSewa ePay URL is not configured.');
        }

        return $epayUrl;
    }

    private function statusUrl(): string
    {
        $statusUrl = (string) config('services.esewa.status_url');

        if ($statusUrl === '') {
            throw new RuntimeException('eSewa status URL is not configured.');
        }

        return $statusUrl;
    }

    private function productCode(): string
    {
        $productCode = (string) config('services.esewa.product_code');

        if ($productCode === '') {
            throw new RuntimeException('eSewa product code is not configured.');
        }

        return $productCode;
    }

    private function secretKey(): string
    {
        $secretKey = (string) config('services.esewa.secret_key');

        if ($secretKey === '') {
            throw new RuntimeException('eSewa secret key is not configured.');
        }

        return $secretKey;
    }

    private function formatAmount(float|string $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    private function failureMessage(mixed $response, string $fallback): string
    {
        if (is_array($response) && isset($response['error_message']) && is_string($response['error_message'])) {
            return $fallback.': '.$response['error_message'];
        }

        return $fallback;
    }
}
