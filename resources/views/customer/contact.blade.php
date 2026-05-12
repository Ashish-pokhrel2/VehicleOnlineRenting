@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Contact Us
        </h1>

        <p class="text-gray-500 mt-2">
            Need help with your booking or vehicle rental? Send us a message and our support team will get back to you.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15A2.25 2.25 0 012.25 17.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Get In Touch
                        </h2>
                        <p class="text-sm text-gray-500">
                            VehicleRent Customer Support Team
                        </p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-700 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.contact.store') }}" class="space-y-5" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', auth()->user()->name ?? '') }}"
                                   placeholder="Enter your full name"
                                   class="customer-contact-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('name')
                                <p class="customer-contact-error mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   placeholder="customer@example.com"
                                   class="customer-contact-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('email')
                                <p class="customer-contact-error mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700">Message</label>
                            <span id="messageCount" class="text-xs text-gray-400">0 / 500</span>
                        </div>

                        <textarea name="message"
                                  id="messageInput"
                                  rows="4"
                                  maxlength="500"
                                  placeholder="Write your message here..."
                                  class="customer-contact-input w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="customer-contact-error mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="bg-blue-600 text-white px-7 py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                        Send Message
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    Contact Information
                </h3>

                <div class="space-y-5 text-sm">
                    <div>
                        <p class="font-semibold text-gray-900">Email</p>
                        <p class="text-gray-600 mt-1">support@vehiclerent.com</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">Phone</p>
                        <p class="text-gray-600 mt-1">+977 981 234 5678</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">Office</p>
                        <p class="text-gray-600 mt-1">
                            VehicleRent Support Center<br>
                            Biratnagar, Nepal
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-600 rounded-2xl p-5 text-white shadow-sm">
                <h3 class="text-xl font-bold mb-4">
                    Customer Support
                </h3>

                <p class="text-blue-100 leading-7 text-sm">
                    Our support team can assist customers with bookings,
                    cancellations, vehicle availability, account issues,
                    and rental questions.
                </p>
            </div>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.customer-contact-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const error = input.closest('div').querySelector('.customer-contact-error');

            if (error) {
                error.remove();
            }
        });
    });

    const messageInput = document.getElementById('messageInput');
    const messageCount = document.getElementById('messageCount');

    if (messageInput && messageCount) {
        function updateCount() {
            messageCount.textContent = `${messageInput.value.length} / 500`;
        }

        updateCount();
        messageInput.addEventListener('input', updateCount);
    }
</script>
@endsection