<?php

namespace Database\Factories;

use App\Enums\DebtStatus;
use App\Models\Debt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Debt>
 */
class DebtFactory extends Factory
{
    public function definition(): array
    {
        $original = fake()->randomFloat(2, 500, 25000);
        $balance = fake()->randomFloat(2, 0, $original);

        return [
            'name' => ucfirst(fake()->words(2, true)).' Loan',
            'creditor' => fake()->company(),
            'original_amount' => $original,
            'balance' => $balance,
            'interest_rate' => fake()->randomFloat(2, 0, 24),
            'minimum_payment' => fake()->randomFloat(2, 25, 400),
            'due_day' => fake()->numberBetween(1, 28),
            'status' => DebtStatus::Active,
            'notes' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'balance' => 0,
            'status' => DebtStatus::Paid,
        ]);
    }
}
