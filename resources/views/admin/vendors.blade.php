@extends('layouts.admin')

@section('content')
    <section class="mx-auto w-full max-w-7xl space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="space-y-2">
                <h1 class="text-4xl font-bold text-slate-900">Vendor Management</h1>
                <p class="text-base text-slate-600">{{ $stats['totalVendors'] }} total vendors</p>
            </div>

            <form method="GET" action="{{ route('admin.vendors') }}" class="w-full md:w-64">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3-3" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search vendors..."
                        class="h-10 w-full rounded-lg border border-black bg-white pl-10 pr-3 text-sm text-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </form>
        </div>

        <!-- Key Statistics Cards -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2z" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Vendors</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">{{ $stats['totalVendors'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stats['activeVendors'] }} active</p>
            </div>

            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Total Vendor Revenue</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">RS {{ number_format($stats['totalVendorRevenue'], 0) }}</p>
                <p class="mt-2 text-sm text-slate-500">From all bookings</p>
            </div>

            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm text-slate-600">Active Rate</p>
                <p class="mt-1 text-4xl font-bold text-slate-900">
                    @if ($stats['totalVendors'] > 0)
                        {{ round(($stats['activeVendors'] / $stats['totalVendors']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </p>
                <p class="mt-2 text-sm text-slate-500">Of all vendors</p>
            </div>
        </div>

        <!-- Vendors Table -->
        <div class="overflow-hidden rounded-2xl border border-black/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-slate-50 text-sm text-slate-700">
                        <tr class="border-b border-black/10">
                            <th class="px-6 py-4 font-semibold">Vendor</th>
                            <th class="px-6 py-4 font-semibold">Vehicles</th>
                            <th class="px-6 py-4 font-semibold">Bookings</th>
                            <th class="px-6 py-4 font-semibold">Rates</th>
                            <th class="px-6 py-4 font-semibold">Revenue</th>
                            <th class="px-6 py-4 font-semibold">Rating</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse ($vendors as $vendor)
                            @php
                                $statusPillClass = $vendor['status'] === 'active'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-slate-200 text-slate-700';
                            @endphp

                            <tr class="border-b border-black/10 last:border-none hover:bg-slate-50">
                                <!-- Vendor Info -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.vendors.show', $vendor['id']) }}" class="block hover:text-blue-600 transition">
                                        <p class="text-[16px] font-medium leading-6 text-slate-900 hover:text-blue-600">{{ $vendor['name'] }}</p>
                                        <p class="mt-1 text-[14px] leading-5 text-slate-500">{{ $vendor['email'] }}</p>
                                        <p class="mt-0.5 text-[13px] leading-4 text-slate-400">{{ $vendor['phone'] }}</p>
                                    </a>
                                </td>

                                <!-- Vehicles Count -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-[15px] font-semibold text-slate-900">{{ $vendor['vehicleCount'] }}</p>
                                        <p class="text-[13px] text-slate-500">{{ $vendor['activeVehicles'] }} active</p>
                                    </div>
                                </td>

                                <!-- Bookings Stats -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-[15px] font-semibold text-slate-900">{{ $vendor['totalBookings'] }} total</p>
                                        <div class="flex gap-2 text-[11px] font-medium">
                                            <span class="rounded bg-blue-100 px-2 py-0.5 text-blue-700">
                                                C: {{ $vendor['confirmedBookings'] }}
                                            </span>
                                            <span class="rounded bg-green-100 px-2 py-0.5 text-green-700">
                                                D: {{ $vendor['completedBookings'] }}
                                            </span>
                                            <span class="rounded bg-red-100 px-2 py-0.5 text-red-700">
                                                X: {{ $vendor['cancelledBookings'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Performance Rates -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[12px] text-slate-600">Accept:</span>
                                            <div class="w-20 overflow-hidden rounded-full bg-slate-200 h-1.5">
                                                <div
                                                    class="h-full rounded-full bg-green-500 transition-all"
                                                    style="width: {{ min($vendor['acceptRate'], 100) }}%"
                                                ></div>
                                            </div>
                                            <span class="text-[12px] font-semibold text-slate-900 w-8">{{ $vendor['acceptRate'] }}%</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[12px] text-slate-600">Cancel:</span>
                                            <div class="w-20 overflow-hidden rounded-full bg-slate-200 h-1.5">
                                                <div
                                                    class="h-full rounded-full bg-red-500 transition-all"
                                                    style="width: {{ min($vendor['cancelRate'], 100) }}%"
                                                ></div>
                                            </div>
                                            <span class="text-[12px] font-semibold text-slate-900 w-8">{{ $vendor['cancelRate'] }}%</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Revenue -->
                                <td class="px-6 py-4">
                                    <p class="text-[15px] font-semibold text-slate-900">RS {{ number_format($vendor['totalRevenue'], 2) }}</p>
                                    <p class="mt-1 text-[13px] text-slate-500">
                                        @if ($vendor['totalBookings'] > 0)
                                            Avg: RS {{ number_format($vendor['totalRevenue'] / $vendor['totalBookings'], 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </td>

                                <!-- Rating & Reviews -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-[15px] font-semibold text-slate-900">
                                            @if ($vendor['avgRating'] > 0)
                                                ⭐ {{ $vendor['avgRating'] }}
                                            @else
                                                No rating
                                            @endif
                                        </p>
                                        <p class="text-[13px] text-slate-500">{{ $vendor['reviewCount'] }} reviews</p>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span class="rounded-lg px-3 py-1 text-sm font-medium {{ $statusPillClass }}">
                                        {{ ucfirst($vendor['status']) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('admin.vendors.show', $vendor['id']) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-100 transition"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.status', $vendor['id']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $vendor['status'] === 'active' ? 'inactive' : 'active' }}">
                                            <button
                                                type="submit"
                                                role="switch"
                                                aria-checked="{{ $vendor['status'] === 'active' ? 'true' : 'false' }}"
                                                aria-label="Toggle {{ $vendor['name'] }} status"
                                                @class([
                                                    'relative inline-flex h-7 w-14 items-center rounded-full border transition focus:outline-none focus:ring-2 focus:ring-offset-2',
                                                    'border-green-600 bg-green-500 focus:ring-green-600' => $vendor['status'] === 'active',
                                                    'border-slate-400 bg-slate-300 focus:ring-slate-500' => $vendor['status'] !== 'active',
                                                ])
                                            >
                                                <span class="sr-only">Toggle status</span>
                                                <span
                                                    @class([
                                                        'inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition',
                                                        'translate-x-8' => $vendor['status'] === 'active',
                                                        'translate-x-1' => $vendor['status'] !== 'active',
                                                    ])
                                                ></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-500">
                                    No vendors found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="rounded-lg border border-black/10 bg-slate-50 p-4">
            <p class="text-xs font-medium text-slate-600 mb-2">Legend:</p>
            <div class="grid gap-3 sm:grid-cols-4 text-[13px]">
                <div class="flex items-center gap-2">
                    <span class="rounded bg-blue-100 px-2 py-0.5 text-blue-700 font-medium">C</span>
                    <span class="text-slate-600">Confirmed</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-green-100 px-2 py-0.5 text-green-700 font-medium">D</span>
                    <span class="text-slate-600">Completed</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded bg-red-100 px-2 py-0.5 text-red-700 font-medium">X</span>
                    <span class="text-slate-600">Cancelled</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-600">Avg:</span>
                    <span class="font-medium">Average revenue per booking</span>
                </div>
            </div>
        </div>
    </section>
@endsection
