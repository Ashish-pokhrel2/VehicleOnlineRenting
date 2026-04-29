@php
    $links = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Users', 'route' => 'admin.users'],
        ['label' => 'Vehicles', 'route' => 'admin.vehicles'],
        ['label' => 'Bookings', 'route' => 'admin.bookings'],
    ];
@endphp

<aside class="w-64 shrink-0 border-r border-black/10 bg-white">
    <nav class="flex flex-col gap-1 px-4 pt-4">
        @foreach ($links as $link)
            @php
                $isActive = request()->routeIs($link['route']);
            @endphp

            <a
                href="{{ route($link['route']) }}"
                class="{{ $isActive ? 'bg-blue-50 text-blue-600 font-medium' : 'text-slate-700 font-normal hover:bg-slate-50' }} flex h-12 items-center gap-3 rounded-[10px] pl-4 text-base transition"
            >
                @if ($link['route'] === 'admin.dashboard')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4zM14 4h6v4h-6zM14 12h6v8h-6zM4 14h6v6H4z" />
                    </svg>
                @elseif ($link['route'] === 'admin.users')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                @elseif ($link['route'] === 'admin.vehicles')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M4.5 8.25V16.5L12 21l7.5-4.5V8.25M4.5 8.25 12 12.75l7.5-4.5" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="16" rx="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 3v4M8 3v4M3 11h18" />
                    </svg>
                @endif
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
</aside>
