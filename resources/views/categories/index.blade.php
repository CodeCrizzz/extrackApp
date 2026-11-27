@extends('layouts.app', ['title' => 'Categories'])

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Categories</h1>
            <p class="mt-1 text-sm text-slate-500">Organize your income and spending into meaningful buckets.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14" /></svg>
            Add category
        </a>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        @foreach ([
            ['title' => 'Expense categories', 'categories' => $expenseCategories, 'accent' => 'text-rose-600'],
            ['title' => 'Income categories', 'categories' => $incomeCategories, 'accent' => 'text-emerald-600'],
        ] as $group)
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">{{ $group['title'] }}</h2>
                    <span class="text-xs font-medium {{ $group['accent'] }}">{{ $group['categories']->count() }}</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($group['categories'] as $category)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <span class="size-3 shrink-0 rounded-full" style="background-color: {{ $category->color }}"></span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $category->name }}</p>
                                <p class="text-xs text-slate-500">{{ $category->transactions_count }} {{ Str::plural('transaction', $category->transactions_count) }}</p>
                            </div>
                            <a href="{{ route('categories.edit', $category) }}" class="rounded-md px-2 py-1 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">Edit</a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category? Transactions will become uncategorized.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md px-2 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                            </form>
                        </div>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-slate-500">No categories yet.</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
@endsection
