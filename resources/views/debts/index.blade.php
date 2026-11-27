@extends('layouts.app', ['title' => 'Debts'])

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Debts</h1>
            <p class="mt-1 text-sm text-slate-500">Keep an eye on what you owe and how close you are to paying it off.</p>
        </div>
        <a href="{{ route('debts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14" /></svg>
            Add debt
        </a>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Outstanding balance</p>
            <x-money :amount="$activeTotal" class="mt-2 block text-2xl font-semibold text-slate-900" />
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Monthly minimums</p>
            <x-money :amount="$activeMinimums" class="mt-2 block text-2xl font-semibold text-slate-900" />
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Active debts</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $activeCount }}</p>
        </div>
    </div>

    <div class="mt-6 flex gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm sm:w-fit">
        @foreach (['active' => 'Active', 'paid' => 'Paid off', 'all' => 'All'] as $value => $label)
            <a href="{{ route('debts.index', ['status' => $value]) }}"
               @class([
                   'flex-1 rounded-lg px-4 py-2 text-center text-sm font-medium transition sm:flex-none',
                   'bg-slate-900 text-white' => $status === $value,
                   'text-slate-600 hover:bg-slate-100' => $status !== $value,
               ])>
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="mt-4 space-y-3">
        @forelse ($debts as $debt)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-base font-semibold text-slate-900">{{ $debt->name }}</h2>
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-emerald-50 text-emerald-700' => $debt->status === \App\Enums\DebtStatus::Active,
                                'bg-slate-100 text-slate-600' => $debt->status === \App\Enums\DebtStatus::Paid,
                            ])>
                                {{ $debt->status->label() }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $debt->creditor ?: 'No creditor' }}
                            @if ($debt->due_day)
                                · Due on day {{ $debt->due_day }}
                            @endif
                            @if ($debt->interest_rate > 0)
                                · {{ rtrim(rtrim(number_format((float) $debt->interest_rate, 2), '0'), '.') }}% APR
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('debts.edit', $debt) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                        <form method="POST" action="{{ route('debts.destroy', $debt) }}" onsubmit="return confirm('Delete this debt?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-xs text-slate-500">Balance</p>
                        <x-money :amount="$debt->balance" class="mt-0.5 block text-lg font-semibold text-slate-900" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Original amount</p>
                        <x-money :amount="$debt->original_amount" class="mt-0.5 block text-lg font-semibold text-slate-600" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Minimum payment</p>
                        <x-money :amount="$debt->minimum_payment" class="mt-0.5 block text-lg font-semibold text-slate-600" />
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $debt->paidPercentage() }}% paid off</span>
                        <span>{{ $debt->status === \App\Enums\DebtStatus::Paid ? 'Complete' : 'In progress' }}</span>
                    </div>
                    <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $debt->paidPercentage() }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 px-6 py-12 text-center">
                <p class="text-sm font-medium text-slate-700">No debts here.</p>
                <p class="mt-1 text-sm text-slate-500">Add a debt to start tracking your payoff progress.</p>
                <a href="{{ route('debts.create') }}" class="mt-4 inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Add debt</a>
            </div>
        @endforelse
    </div>

    @if ($debts->hasPages())
        <div class="mt-4">{{ $debts->links() }}</div>
    @endif
@endsection
