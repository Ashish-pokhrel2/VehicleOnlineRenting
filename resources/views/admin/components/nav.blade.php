<header class="fixed inset-x-0 top-0 z-30 border-b border-black/10 bg-white">
    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="h-8 w-auto">
        </a>

        <div class="flex items-center gap-3">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
