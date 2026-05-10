@extends('layouts.vendor')

@section('content')

<div class="min-h-screen bg-gray-50 pb-12">
    <div>

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                Contact Support
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                Need help with bookings, vehicles, or vendor management? Send us a message and our support team will get back to you.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-6 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                Get In Touch
                            </h2>

                            <p class="text-sm text-gray-500">
                                VehicleRent Vendor Support Team
                            </p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-semibold text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('vendor.contact.store') }}"
                          class="space-y-5">

                        @csrf

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div>
                                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Full Name
                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                       @error('name') border-red-400 @else border-gray-300 @enderror"
                                       placeholder="Enter your full name">

                                @error('name')
                                    <p class="mt-2 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Email Address
                                </label>

                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                       @error('email') border-red-400 @else border-gray-300 @enderror"
                                       placeholder="vendor@example.com">

                                @error('email')
                                    <p class="mt-2 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="mb-2 block text-sm font-semibold text-gray-700">
                                Subject
                            </label>

                            <input type="text"
                                   id="subject"
                                   name="subject"
                                   value="{{ old('subject') }}"
                                   class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                   @error('subject') border-red-400 @else border-gray-300 @enderror"
                                   placeholder="Enter message subject">

                            @error('subject')
                                <p class="mt-2 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="mb-2 block text-sm font-semibold text-gray-700">
                                Message
                            </label>

                            <textarea id="message"
                                      name="message"
                                      rows="5"
                                      class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                                      @error('message') border-red-400 @else border-gray-300 @enderror"
                                      placeholder="Write your message here...">{{ old('message') }}</textarea>

                            @error('message')
                                <p class="mt-2 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side Info -->
            <div class="space-y-5">

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-5 text-lg font-bold text-gray-900">
                        Contact Information
                    </h3>

                    <div class="space-y-4 text-sm text-gray-600">
                        <div>
                            <p class="font-semibold text-gray-900">Email</p>
                            <p>support@vehiclerent.com</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900">Phone</p>
                            <p>+977 981 234 5678</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900">Office</p>
                            <p>
                                VehicleRent Support Center<br>
                                Biratnagar, Nepal
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-5 text-lg font-bold text-gray-900">
                        Business Hours
                    </h3>

                    <div class="space-y-3 text-sm text-gray-600">

                        <div class="flex justify-between gap-4">
                            <span>Monday - Friday</span>
                            <span class="text-right">9:00 AM - 6:00 PM</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span>Saturday</span>
                            <span class="text-red-500">Closed</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span>Sunday</span>
                            <span class="text-right">10:00 AM - 4:00 PM</span>
                        </div>

                    </div>
                </div>

                <div class="rounded-2xl bg-blue-600 p-5 text-white shadow-sm min-h-[220px]">
                    <h3 class="text-lg font-bold">
                        Vendor Support
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-blue-100">
                        Our support team is available to help vendors manage bookings, vehicles, account issues, and customer requests.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection