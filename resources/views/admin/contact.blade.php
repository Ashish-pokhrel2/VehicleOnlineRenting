@extends('layouts.admin')

@section('content')
    <section class="flex flex-col items-start gap-6">
        <div class="flex flex-col items-start gap-2">
            <h1 class="text-[30px] font-bold leading-9" style="color: #101828;">Contact Messages</h1>
            <p class="text-base leading-6" style="color: #4A5565;">Manage and respond to customer inquiries</p>
        </div>

        {{-- <div class="w-full overflow-hidden rounded-[14px] border border-black/10 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead style="background: #F9FAFB;">
                        <tr class="border-b border-black/10">
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Name</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Email</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Message</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold leading-5" style="color: #364153;">Received At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr class="border-b border-black/10 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm" style="color: #4A5565;">{{ $contact->name }}</td>
                                <td class="px-6 py-4 text-sm" style="color: #4A5565;">{{ $contact->email }}</td>
                                <td class="px-6 py-4 text-sm" style="color: #4A5565; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $contact->message }}">{{ $contact->message }}</td>
                                <td class="px-6 py-4 text-sm text-right" style="color: #4A5565;">{{ $contact->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm" style="color: #4A5565;">No contact messages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>      
    </div>
        --}}

    </section>
@endsection
 