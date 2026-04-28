@extends('layouts.admin')

@section('content')
    <section class="mx-auto w-full max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="space-y-2">
                <h1 class="text-4xl font-bold text-slate-900">User Management</h1>
                <p class="text-base text-slate-600">{{ $totalUsers }} total users</p>
            </div>

            <form method="GET" action="{{ route('admin.users') }}" class="w-full md:w-64">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3-3" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search users..."
                        class="h-10 w-full rounded-lg border border-black bg-white pl-10 pr-3 text-sm text-slate-700 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </form>
        </div>

        @if (session('statusMessage'))
            @php
                $isDangerMessage = session('statusVariant') === 'danger';
            @endphp
            <div class="rounded-lg border px-4 py-3 text-sm {{ $isDangerMessage ? 'border-red-300 bg-red-50 text-red-700' : 'border-green-300 bg-green-50 text-green-700' }}">
                {{ session('statusMessage') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-black/10 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-slate-50 text-sm text-slate-700">
                        <tr class="border-b border-black/10">
                            <th class="px-6 py-4 font-semibold">User</th>
                            <th class="px-6 py-4 font-semibold">Role</th>
                            <th class="px-6 py-4 font-semibold">Phone</th>
                            <th class="px-6 py-4 font-semibold">Joined</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse ($users as $user)
                            @php
                                $role = $user->role->value;
                                $rolePillClass = match ($role) {
                                    'admin' => 'bg-violet-100 text-violet-700',
                                    'vendor' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-green-100 text-green-700',
                                };

                                $statusPillClass = $user->status === 'active'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-slate-200 text-slate-700';
                            @endphp

                            <tr class="border-b border-black/10 last:border-none">
                                <td class="px-6 py-4">
                                    <p class="text-[16px] font-medium leading-6 text-slate-900">{{ $user->name }}</p>
                                    <p class="mt-1 text-[14px] leading-5 text-slate-500">{{ $user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-lg px-3 py-1 text-sm font-medium {{ $rolePillClass }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[14px] leading-5">{{ $user->phone }}</td>
                                <td class="px-6 py-4 text-[14px] leading-5">
                                    {{ \Illuminate\Support\Carbon::parse($user->joined_date ?? $user->created_at)->format('n/j/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-lg px-3 py-1 text-sm font-medium {{ $statusPillClass }}">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if (auth()->id() !== $user->id)
                                            @php
                                                $isActive = ($user->status ?? 'active') === 'active';
                                            @endphp
                                            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $isActive ? 'inactive' : 'active' }}">
                                                <button
                                                    type="submit"
                                                    role="switch"
                                                    aria-checked="{{ $isActive ? 'true' : 'false' }}"
                                                    aria-label="Toggle {{ $user->name }} status"
                                                    @class([
                                                        'relative inline-flex h-7 w-14 items-center rounded-full border transition focus:outline-none focus:ring-2 focus:ring-offset-2',
                                                        'border-green-600 bg-green-500 focus:ring-green-600' => $isActive,
                                                        'border-slate-400 bg-slate-300 focus:ring-slate-500' => ! $isActive,
                                                    ])
                                                >
                                                    <span class="sr-only">Toggle status</span>
                                                    <span
                                                        @class([
                                                            'inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition',
                                                            'translate-x-8' => $isActive,
                                                            'translate-x-1' => ! $isActive,
                                                        ])
                                                    ></span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Current user</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
