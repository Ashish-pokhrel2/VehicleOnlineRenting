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

                    <!-- Success Message -->
                    <div id="successMessage"
                         class="mb-5 hidden rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-semibold text-green-700">
                        Message sent successfully. Our support team will contact you soon.
                    </div>

                    <form id="contactForm" class="space-y-5" novalidate>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div>
                                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Full Name
                                </label>

                                <input type="text"
                                       id="name"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Enter your full name">

                                <p id="nameError" class="mt-2 hidden text-sm text-red-500">
                                    Full name is required.
                                </p>
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Email Address
                                </label>

                                <input type="email"
                                       id="email"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="vendor@example.com">

                                <p id="emailError" class="mt-2 hidden text-sm text-red-500">
                                    Valid email address is required.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="mb-2 block text-sm font-semibold text-gray-700">
                                Subject
                            </label>

                            <input type="text"
                                   id="subject"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                   placeholder="Enter message subject">

                            <p id="subjectError" class="mt-2 hidden text-sm text-red-500">
                                Subject is required.
                            </p>
                        </div>

                        <div>
                            <label for="message" class="mb-2 block text-sm font-semibold text-gray-700">
                                Message
                            </label>

                            <textarea id="message"
                                      rows="5"
                                      class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                      placeholder="Write your message here..."></textarea>

                            <p id="messageError" class="mt-2 hidden text-sm text-red-500">
                                Message is required.
                            </p>
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

<script>
    const form = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');

    const fields = {
        name: document.getElementById('name'),
        email: document.getElementById('email'),
        subject: document.getElementById('subject'),
        message: document.getElementById('message'),
    };

    const errors = {
        name: document.getElementById('nameError'),
        email: document.getElementById('emailError'),
        subject: document.getElementById('subjectError'),
        message: document.getElementById('messageError'),
    };

    function showError(fieldName) {
        errors[fieldName].classList.remove('hidden');
        fields[fieldName].classList.add('border-red-400', 'focus:border-red-500', 'focus:ring-red-100');
    }

    function clearError(fieldName) {
        errors[fieldName].classList.add('hidden');
        fields[fieldName].classList.remove('border-red-400', 'focus:border-red-500', 'focus:ring-red-100');
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        let isValid = true;
        successMessage.classList.add('hidden');

        Object.keys(fields).forEach(clearError);

        if (fields.name.value.trim() === '') {
            showError('name');
            isValid = false;
        }

        if (!isValidEmail(fields.email.value.trim())) {
            showError('email');
            isValid = false;
        }

        if (fields.subject.value.trim() === '') {
            showError('subject');
            isValid = false;
        }

        if (fields.message.value.trim() === '') {
            showError('message');
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        successMessage.classList.remove('hidden');
        form.reset();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    Object.keys(fields).forEach(function (fieldName) {
        fields[fieldName].addEventListener('input', function () {
            clearError(fieldName);
            successMessage.classList.add('hidden');
        });
    });
</script>

@endsection