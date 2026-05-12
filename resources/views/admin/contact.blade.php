@extends('layouts.admin')

@section('content')
    <section class="mx-auto w-full max-w-6xl space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-4xl font-bold text-slate-900">Contact Messages</h1>
            <p class="text-base text-slate-600">{{ $totalMessages }} total messages from customers and vendors</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-black/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-slate-50 text-sm text-slate-700">
                        <tr class="border-b border-black/10">
                            <th class="px-6 py-4 font-semibold">Sender</th>
                            <th class="px-6 py-4 font-semibold">Type</th>
                            <th class="px-6 py-4 font-semibold">Subject</th>
                            <th class="px-6 py-4 font-semibold">Message</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 text-right font-semibold">Received</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse ($messages as $message)
                            @php
                                $status = strtolower($message['status']);
                                $statusClass = match ($status) {
                                    'unread', 'pending' => 'bg-amber-100 text-amber-700',
                                    'replied' => 'bg-emerald-100 text-emerald-700',
                                    'closed' => 'bg-slate-200 text-slate-700',
                                    default => 'bg-slate-200 text-slate-700',
                                };
                            @endphp
                            <tr class="border-b border-black/10 last:border-none">
                                <td class="px-6 py-4">
                                    <p class="text-[16px] font-medium leading-6 text-slate-900">{{ $message['sender_name'] }}</p>
                                    <p class="mt-1 text-[14px] leading-5 text-slate-500">{{ $message['sender_email'] }}</p>
                                </td>
                                <td class="px-6 py-4 text-[14px] leading-5">{{ $message['source'] }}</td>
                                <td class="px-6 py-4 text-[14px] leading-5">{{ $message['subject'] }}</td>
                                <td class="px-6 py-4 text-[14px] leading-5">
                                    <span class="block max-w-[320px] truncate" title="{{ $message['message'] }}">
                                        {{ $message['message'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-lg px-3 py-1 text-sm font-medium {{ $statusClass }}">
                                        {{ $message['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-[14px] leading-5 text-slate-600">
                                    {{ $message['created_at']?->format('M d, Y h:i A') ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                    No contact messages found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
 