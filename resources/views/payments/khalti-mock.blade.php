<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Khalti Sandbox Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <main class="min-h-screen px-4 py-10">
        <section class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div>
                    <p class="text-sm font-medium text-purple-700">Khalti Sandbox</p>
                    <h1 class="mt-1 text-xl font-semibold text-gray-950">Mock Checkout</h1>
                </div>
                <img src="{{ asset('images/logo/khltilogo.png') }}" alt="Khalti" class="h-12 w-24 object-contain">
            </div>

            <dl class="mt-6 space-y-4 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">Order</dt>
                    <dd class="text-right font-medium text-gray-900">{{ $payment->purchase_order_name }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-500">PIDX</dt>
                    <dd class="max-w-48 truncate text-right font-mono text-xs text-gray-900">{{ $pidx }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-t border-gray-200 pt-4">
                    <dt class="text-base font-semibold text-gray-900">Amount</dt>
                    <dd class="text-base font-bold text-gray-950">Rs. {{ number_format((float) $payment->amount, 2) }}</dd>
                </div>
            </dl>

            <div class="mt-6 grid grid-cols-1 gap-3">
                <a href="{{ $successUrl }}" class="rounded-md bg-purple-700 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-purple-800">
                    Complete Mock Payment
                </a>
                <a href="{{ $cancelUrl }}" class="rounded-md border border-gray-300 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Cancel Payment
                </a>
            </div>

            <p class="mt-5 text-xs leading-5 text-gray-500">
                This local checkout appears only when Khalti sandbox checkout is unavailable and mock checkout is enabled.
            </p>
        </section>
    </main>
</body>
</html>
