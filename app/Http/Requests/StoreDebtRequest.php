<?php

namespace App\Http\Requests;

use App\Enums\DebtStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'creditor' => ['nullable', 'string', 'max:255'],
            'original_amount' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'balance' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'interest_rate' => ['nullable', 'numeric', 'between:0,100'],
            'minimum_payment' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
            'due_day' => ['nullable', 'integer', 'between:1,31'],
            'status' => ['required', Rule::enum(DebtStatus::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
