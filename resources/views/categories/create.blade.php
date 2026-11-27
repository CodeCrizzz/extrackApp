@extends('layouts.app', ['title' => 'New category'])

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">← Back to categories</a>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Add category</h1>
            <p class="mt-1 text-sm text-slate-500">Create a bucket for a type of income or expense.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('categories._form', [
                'action' => route('categories.store'),
                'method' => 'POST',
                'submitLabel' => 'Save category',
            ])
        </div>
    </div>
@endsection
