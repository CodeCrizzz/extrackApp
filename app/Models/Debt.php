<?php

namespace App\Models;

use App\Enums\DebtStatus;
use Database\Factories\DebtFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'creditor', 'original_amount', 'balance', 'interest_rate', 'minimum_payment', 'due_day', 'status', 'notes'])]
class Debt extends Model
{
    /** @use HasFactory<DebtFactory> */
    use HasFactory;

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', DebtStatus::Active);
    }

    public function paidPercentage(): float
    {
        if ($this->original_amount <= 0) {
            return 0;
        }

        return min(100, max(0, round((($this->original_amount - $this->balance) / $this->original_amount) * 100, 1)));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'original_amount' => 'decimal:2',
            'balance' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'minimum_payment' => 'decimal:2',
            'due_day' => 'integer',
            'status' => DebtStatus::class,
        ];
    }
}
