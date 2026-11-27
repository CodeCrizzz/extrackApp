@extends('layouts.app', ['title' => 'Edit category'])

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">← Back to categories</a>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Edit category</h1>
            <p class="mt-1 text-sm text-slate-500">Changes apply to every transaction in this category.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @include('categories._form', [
                'action' => route('categories.update', $category),
                'method' => 'PUT',
                'submitLabel' => 'Update category',
                'category' => $category,
            ])
        </div>

        <div class="mt-6 rounded-2xl border border-rose-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900">Delete category</h2>
            <p class="mt-1 text-xs text-slate-500">Transactions in this category will become uncategorized, not deleted.</p>
            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="mt-3" onsubmit="return confirm('Delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-rose-300 px-3.5 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
