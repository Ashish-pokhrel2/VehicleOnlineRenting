@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800">Admin Dashboard</h1>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
            <p class="text-slate-700">Welcome, Admin.</p>
            <p class="mt-2 text-sm text-slate-500">
                Held settlements: {{ $heldSettlements->count() }}
            </p>
        </div>
    </section>
@endsection
