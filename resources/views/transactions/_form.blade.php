@php
    $transaction = $transaction ?? null;
    $selectedType = old('type', $transaction?->type->value ?? 'expense');
    $selectedCategory = old('category_id', $transaction?->category_id);
    $inputClass = 'mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            Please check the highlighted fields and try again.
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="type" class="block text-sm font-medium text-slate-700">Type</label>
            <select id="type" name="type" data-transaction-type class="{{ $inputClass }}">
                @foreach (\App\Enums\TransactionType::cases() as $type)
                    <option value="{{ $type->value }}" @selected($selectedType === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
            @error('type')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-700">Category</label>
            <select id="category_id" name="category_id" data-category-select class="{{ $inputClass }}">
                <option value="">Uncategorized</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" data-type="{{ $category->type->value }}" @selected((string) $selectedCategory === (string) $category->id)>
                        {{ $category->name }} ({{ $category->type->label() }})
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <input id="description" name="description" type="text" value="{{ old('description', $transaction?->description) }}"
                   placeholder="e.g. Grocery run at the market" class="{{ $inputClass }}">
            @error('description')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="amount" class="block text-sm font-medium text-slate-700">Amount</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">{{ config('extrack.currency_symbol') }}</span>
                <input id="amount" name="amount" type="number" step="0.01" min="0.01" value="{{ old('amount', $transaction?->amount) }}"
                       class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-8 pr-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            @error('amount')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="transaction_date" class="block text-sm font-medium text-slate-700">Date</label>
            <input id="transaction_date" name="transaction_date" type="date"
                   value="{{ old('transaction_date', $transaction?->transaction_date?->format('Y-m-d') ?? now()->toDateString()) }}" class="{{ $inputClass }}">
            @error('transaction_date')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="notes" class="block text-sm font-medium text-slate-700">Notes <span class="font-normal text-slate-400">(optional)</span></label>
            <textarea id="notes" name="notes" rows="3" class="{{ $inputClass }}">{{ old('notes', $transaction?->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
        <a href="{{ route('transactions.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            {{ $submitLabel }}
        </button>
    </div>
</form>
