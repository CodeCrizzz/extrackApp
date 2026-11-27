@extends('layouts.app', ['title' => 'New transaction'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">← Back to transactions</a>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Add transaction</h1>
            <p class="mt-1 text-sm text-slate-500">Record money coming in or going out.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('transactions._form', [
                'action' => route('transactions.store'),
                'method' => 'POST',
                'submitLabel' => 'Save transaction',
                'categories' => $categories,
            ])
        </div>
    </div>
@endsection
