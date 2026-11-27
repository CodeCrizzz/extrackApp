@extends('layouts.app', ['title' => 'Transactions'])

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Transactions</h1>
            <p class="mt-1 text-sm text-slate-500">All of your income and expenses in one place.</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14" /></svg>
            Add transaction
        </a>
    </div>

    <form method="GET" action="{{ route('transactions.index') }}" class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <div class="lg:col-span-2">
                <label for="search" class="block text-xs font-medium text-slate-500">Search</label>
                <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Description..."
                       class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label for="type" class="block text-xs font-medium text-slate-500">Type</label>
                <select id="type" name="type" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">All</option>
                    @foreach (\App\Enums\TransactionType::cases() as $type)
                        <option value="{{ $type->value }}" @selected(($filters['type'] ?? '') === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="category_id" class="block text-xs font-medium text-slate-500">Category</label>
                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="from" class="block text-xs font-medium text-slate-500">From</label>
                <input id="from" name="from" type="date" value="{{ $filters['from'] ?? '' }}"
                       class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label for="to" class="block text-xs font-medium text-slate-500">To</label>
                <input id="to" name="to" type="date" value="{{ $filters['to'] ?? '' }}"
                       class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>
        <div class="mt-3 flex items-center justify-end gap-2">
            <a href="{{ route('transactions.index') }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-100">Reset</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500">Apply filters</button>
        </div>
    </form>

    <div class="mt-4 grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Filtered income</p>
            <x-money :amount="$filteredIncome" class="mt-1 block text-lg font-semibold text-emerald-600" />
        </div>
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Filtered expenses</p>
            <x-money :amount="$filteredExpenses" class="mt-1 block text-lg font-semibold text-rose-600" />
        </div>
        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Net</p>
            <x-money :amount="abs($filteredIncome - $filteredExpenses)" class="mt-1 block text-lg font-semibold {{ $filteredIncome - $filteredExpenses >= 0 ? 'text-emerald-600' : 'text-rose-600' }}" />
        </div>
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $transaction->transaction_date->format('M j, Y') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-800">{{ $transaction->description }}</p>
                                @if ($transaction->notes)
                                    <p class="mt-0.5 max-w-xs truncate text-xs text-slate-400">{{ $transaction->notes }}</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex items-center gap-2 text-slate-600">
                                    <span class="size-2 rounded-full" style="background-color: {{ $transaction->category?->color ?? '#94a3b8' }}"></span>
                                    {{ $transaction->category?->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-emerald-50 text-emerald-700' => $transaction->type === \App\Enums\TransactionType::Income,
                                    'bg-rose-50 text-rose-700' => $transaction->type === \App\Enums\TransactionType::Expense,
                                ])>
                                    {{ $transaction->type->label() }}
                                </span>
                            </td>
                            <td @class([
                                'whitespace-nowrap px-4 py-3 text-right font-semibold',
                                'text-emerald-600' => $transaction->type === \App\Enums\TransactionType::Income,
                                'text-slate-800' => $transaction->type === \App\Enums\TransactionType::Expense,
                            ])>
                                {{ $transaction->type === \App\Enums\TransactionType::Income ? '+' : '−' }}<x-money :amount="$transaction->amount" />
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="rounded-md px-2 py-1 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">Edit</a>
                                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Delete this transaction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">
                                No transactions match your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
