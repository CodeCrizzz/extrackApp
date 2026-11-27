@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('F Y') }} overview of your money.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Add transaction
            </a>
            <a href="{{ route('debts.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Add debt
            </a>
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Income this month</p>
                <span class="flex size-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 19V5m0 0l-6 6m6-6l6 6" /></svg>
                </span>
            </div>
            <x-money :amount="$monthIncome" class="mt-3 block text-2xl font-semibold text-slate-900" />
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Expenses this month</p>
                <span class="flex size-9 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14m0 0l6-6m-6 6l-6-6" /></svg>
                </span>
            </div>
            <x-money :amount="$monthExpenses" class="mt-3 block text-2xl font-semibold text-slate-900" />
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Net cash flow</p>
                <span @class([
                    'flex size-9 items-center justify-center rounded-full',
                    'bg-emerald-50 text-emerald-600' => $monthNet >= 0,
                    'bg-amber-50 text-amber-600' => $monthNet < 0,
                ])>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 12h16m0 0l-5-5m5 5l-5 5" /></svg>
                </span>
            </div>
            <x-money :amount="abs($monthNet)" class="mt-3 block text-2xl font-semibold {{ $monthNet >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" />
            <p class="mt-1 text-xs text-slate-500">{{ $monthNet >= 0 ? 'Surplus' : 'Deficit' }} for the month</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total debt</p>
                <span class="flex size-9 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M3 10h18M5 10v10h14V10M12 3v7m0 0l-3-3m3 3l3-3" /></svg>
                </span>
            </div>
            <x-money :amount="$totalDebt" class="mt-3 block text-2xl font-semibold text-slate-900" />
            <p class="mt-1 text-xs text-slate-500">{{ $activeDebts }} active {{ Str::plural('debt', $activeDebts) }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Last 6 months</h2>
                    <p class="text-xs text-slate-500">Income versus expenses</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-emerald-500"></span> Income</span>
                    <span class="flex items-center gap-1.5"><span class="size-2 rounded-full bg-rose-500"></span> Expenses</span>
                </div>
            </div>

            <div class="mt-6 flex h-48 items-end justify-between gap-3 sm:gap-6">
                @foreach ($monthlyTrend as $month)
                    <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <div class="flex h-full w-full items-end justify-center gap-1.5">
                            <div class="w-1/3 max-w-8 rounded-t-md bg-emerald-500 transition group-hover:bg-emerald-400"
                                 style="height: {{ max(2, ($month['income'] / $trendMax) * 100) }}%"
                                 title="Income: {{ config('extrack.currency_symbol') }}{{ number_format($month['income'], 2) }}"></div>
                            <div class="w-1/3 max-w-8 rounded-t-md bg-rose-500 transition group-hover:bg-rose-400"
                                 style="height: {{ max(2, ($month['expenses'] / $trendMax) * 100) }}%"
                                 title="Expenses: {{ config('extrack.currency_symbol') }}{{ number_format($month['expenses'], 2) }}"></div>
                        </div>
                        <span class="text-xs font-medium text-slate-500">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Spending by category</h2>
                    <p class="text-xs text-slate-500">This month</p>
                </div>
                <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Manage</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($categoryBreakdown as $row)
                    @php
                        $percentage = $breakdownTotal > 0 ? ($row->total / $breakdownTotal) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 font-medium text-slate-700">
                                <span class="size-2.5 rounded-full" style="background-color: {{ $row->category?->color ?? '#94a3b8' }}"></span>
                                {{ $row->category?->name ?? 'Uncategorized' }}
                            </span>
                            <x-money :amount="$row->total" class="text-slate-600" />
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full" style="width: {{ $percentage }}%; background-color: {{ $row->category?->color ?? '#94a3b8' }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-slate-500">No expenses recorded this month.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">Recent transactions</h2>
            <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">View all</a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($recentTransactions as $transaction)
                <div class="flex items-center gap-4 px-5 py-3.5">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                          style="background-color: {{ ($transaction->category?->color ?? '#94a3b8') }}20; color: {{ $transaction->category?->color ?? '#64748b' }}">
                        {{ Str::upper(Str::substr($transaction->description, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-800">{{ $transaction->description }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $transaction->category?->name ?? 'Uncategorized' }} · {{ $transaction->transaction_date->format('M j, Y') }}
                        </p>
                    </div>
                    <p @class([
                        'text-sm font-semibold',
                        'text-emerald-600' => $transaction->type === \App\Enums\TransactionType::Income,
                        'text-slate-800' => $transaction->type === \App\Enums\TransactionType::Expense,
                    ])>
                        {{ $transaction->type === \App\Enums\TransactionType::Income ? '+' : '−' }}<x-money :amount="$transaction->amount" />
                    </p>
                </div>
            @empty
                <p class="px-5 py-10 text-center text-sm text-slate-500">Nothing here yet. Add your first transaction to get started.</p>
            @endforelse
        </div>
    </section>
@endsection
