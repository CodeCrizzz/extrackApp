@php
    $category = $category ?? null;
    $inputClass = 'mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            Please check the highlighted fields and try again.
        </div>
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $category?->name) }}" placeholder="e.g. Groceries"
               class="{{ $inputClass }}">
        @error('name')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="type" class="block text-sm font-medium text-slate-700">Type</label>
            <select id="type" name="type" class="{{ $inputClass }}">
                @foreach (\App\Enums\TransactionType::cases() as $type)
                    <option value="{{ $type->value }}" @selected(old('type', $category?->type->value ?? 'expense') === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
            @error('type')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="color" class="block text-sm font-medium text-slate-700">Color</label>
            <div class="mt-1.5 flex items-center gap-3">
                <input id="color" name="color" type="color" value="{{ old('color', $category?->color ?? '#6366f1') }}"
                       class="size-10 cursor-pointer rounded-lg border border-slate-300 bg-white p-1">
                <span class="text-xs text-slate-500">Used in charts and transaction lists.</span>
            </div>
            @error('color')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
        <a href="{{ route('categories.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            {{ $submitLabel }}
        </button>
    </div>
</form>
