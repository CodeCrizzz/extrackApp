@extends('layouts.app', ['title' => 'Edit transaction'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">← Back to transactions</a>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Edit transaction</h1>
            <p class="mt-1 text-sm text-slate-500">Update the details of this entry.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('transactions._form', [
                'action' => route('transactions.update', $transaction),
                'method' => 'PUT',
                'submitLabel' => 'Update transaction',
                'categories' => $categories,
                'transaction' => $transaction,
            ])
        </div>

        <div class="mt-6 rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900">Delete transaction</h2>
            <p class="mt-1 text-xs text-slate-500">This permanently removes the entry. This action cannot be undone.</p>
            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="mt-3" onsubmit="return confirm('Delete this transaction?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-rose-300 px-3.5 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
