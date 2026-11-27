@extends('layouts.app', ['title' => 'New debt'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('debts.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">← Back to debts</a>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Add debt</h1>
            <p class="mt-1 text-sm text-slate-500">Track a loan, credit card, or any money you owe.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('debts._form', [
                'action' => route('debts.store'),
                'method' => 'POST',
                'submitLabel' => 'Save debt',
            ])
        </div>
    </div>
@endsection
