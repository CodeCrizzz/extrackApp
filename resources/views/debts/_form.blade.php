@php
    $debt = $debt ?? null;
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
            <label for="name" class="block text-sm font-medium text-slate-700">Debt name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $debt?->name) }}" placeholder="e.g. Car loan"
                   class="{{ $inputClass }}">
            @error('name')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="creditor" class="block text-sm font-medium text-slate-700">Creditor <span class="font-normal text-slate-400">(optional)</span></label>
            <input id="creditor" name="creditor" type="text" value="{{ old('creditor', $debt?->creditor) }}" placeholder="e.g. First National Bank"
                   class="{{ $inputClass }}">
            @error('creditor')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="original_amount" class="block text-sm font-medium text-slate-700">Original amount</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">{{ config('extrack.currency_symbol') }}</span>
                <input id="original_amount" name="original_amount" type="number" step="0.01" min="0.01"
                       value="{{ old('original_amount', $debt?->original_amount) }}"
                       class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-8 pr-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            @error('original_amount')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="balance" class="block text-sm font-medium text-slate-700">Current balance</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">{{ config('extrack.currency_symbol') }}</span>
                <input id="balance" name="balance" type="number" step="0.01" min="0"
                       value="{{ old('balance', $debt?->balance) }}"
                       class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-8 pr-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            @error('balance')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="interest_rate" class="block text-sm font-medium text-slate-700">Interest rate <span class="font-normal text-slate-400">(annual %)</span></label>
            <div class="relative mt-1.5">
                <input id="interest_rate" name="interest_rate" type="number" step="0.01" min="0" max="100"
                       value="{{ old('interest_rate', $debt?->interest_rate ?? 0) }}"
                       class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-slate-400">%</span>
            </div>
            @error('interest_rate')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="minimum_payment" class="block text-sm font-medium text-slate-700">Minimum payment</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">{{ config('extrack.currency_symbol') }}</span>
                <input id="minimum_payment" name="minimum_payment" type="number" step="0.01" min="0"
                       value="{{ old('minimum_payment', $debt?->minimum_payment ?? 0) }}"
                       class="block w-full rounded-lg border border-slate-300 bg-white py-2 pl-8 pr-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            @error('minimum_payment')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="due_day" class="block text-sm font-medium text-slate-700">Due day <span class="font-normal text-slate-400">(optional)</span></label>
            <input id="due_day" name="due_day" type="number" min="1" max="31" value="{{ old('due_day', $debt?->due_day) }}"
                   placeholder="e.g. 15" class="{{ $inputClass }}">
            @error('due_day')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
            <select id="status" name="status" class="{{ $inputClass }}">
                @foreach (\App\Enums\DebtStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $debt?->status->value ?? 'active') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="notes" class="block text-sm font-medium text-slate-700">Notes <span class="font-normal text-slate-400">(optional)</span></label>
            <textarea id="notes" name="notes" rows="3" class="{{ $inputClass }}">{{ old('notes', $debt?->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
        <a href="{{ route('debts.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            {{ $submitLabel }}
        </button>
    </div>
</form>
