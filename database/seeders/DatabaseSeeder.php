<?php

namespace Database\Seeders;

use App\Enums\DebtStatus;
use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Salary', 'type' => TransactionType::Income, 'color' => '#10b981'],
            ['name' => 'Freelance', 'type' => TransactionType::Income, 'color' => '#0ea5e9'],
            ['name' => 'Investments', 'type' => TransactionType::Income, 'color' => '#8b5cf6'],
            ['name' => 'Rent', 'type' => TransactionType::Expense, 'color' => '#6366f1'],
            ['name' => 'Groceries', 'type' => TransactionType::Expense, 'color' => '#f59e0b'],
            ['name' => 'Dining Out', 'type' => TransactionType::Expense, 'color' => '#f43f5e'],
            ['name' => 'Transport', 'type' => TransactionType::Expense, 'color' => '#06b6d4'],
            ['name' => 'Utilities', 'type' => TransactionType::Expense, 'color' => '#64748b'],
            ['name' => 'Entertainment', 'type' => TransactionType::Expense, 'color' => '#ec4899'],
            ['name' => 'Shopping', 'type' => TransactionType::Expense, 'color' => '#a855f7'],
            ['name' => 'Health', 'type' => TransactionType::Expense, 'color' => '#14b8a6'],
        ])->mapWithKeys(fn (array $category): array => [
            $category['name'] => Category::query()->firstOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                ['color' => $category['color']],
            ),
        ]);

        if (Transaction::query()->doesntExist()) {
            $this->seedTransactions($categories);
        }

        if (Debt::query()->doesntExist()) {
            $this->seedDebts();
        }
    }

    /**
     * @param  Collection<string, Category>  $categories
     */
    private function seedTransactions($categories): void
    {
        $entries = [
            ['category' => 'Salary', 'description' => 'Monthly salary', 'amount' => 5200, 'days' => [1], 'type' => TransactionType::Income],
            ['category' => 'Freelance', 'description' => 'Freelance project', 'amount' => [250, 850], 'days' => [12], 'type' => TransactionType::Income],
            ['category' => 'Rent', 'description' => 'Apartment rent', 'amount' => 1400, 'days' => [2], 'type' => TransactionType::Expense],
            ['category' => 'Utilities', 'description' => 'Electricity & water', 'amount' => [110, 230], 'days' => [5], 'type' => TransactionType::Expense],
            ['category' => 'Groceries', 'description' => 'Weekly grocery run', 'amount' => [55, 165], 'days' => [3, 10, 17, 24], 'type' => TransactionType::Expense],
            ['category' => 'Dining Out', 'description' => 'Dinner out', 'amount' => [25, 95], 'days' => [6, 15, 22], 'type' => TransactionType::Expense],
            ['category' => 'Transport', 'description' => 'Fuel & transit', 'amount' => [20, 75], 'days' => [8, 18], 'type' => TransactionType::Expense],
            ['category' => 'Entertainment', 'description' => 'Movie night', 'amount' => [15, 65], 'days' => [21], 'type' => TransactionType::Expense],
            ['category' => 'Shopping', 'description' => 'Household shopping', 'amount' => [30, 210], 'days' => [25], 'type' => TransactionType::Expense],
        ];

        foreach (range(3, 0) as $monthsAgo) {
            foreach ($entries as $entry) {
                foreach ($entry['days'] as $day) {
                    $date = now()->startOfMonth()->subMonths($monthsAgo)->day($day);

                    if ($date->isFuture()) {
                        continue;
                    }

                    $amount = is_array($entry['amount'])
                        ? fake()->randomFloat(2, $entry['amount'][0], $entry['amount'][1])
                        : $entry['amount'];

                    Transaction::query()->create([
                        'category_id' => $categories[$entry['category']]->id,
                        'type' => $entry['type'],
                        'description' => $entry['description'],
                        'amount' => $amount,
                        'transaction_date' => $date->toDateString(),
                    ]);
                }
            }
        }
    }

    private function seedDebts(): void
    {
        $debts = [
            [
                'name' => 'Car loan',
                'creditor' => 'First National Bank',
                'original_amount' => 15000,
                'balance' => 8400,
                'interest_rate' => 6.9,
                'minimum_payment' => 320,
                'due_day' => 15,
            ],
            [
                'name' => 'Student loan',
                'creditor' => 'Edu Finance',
                'original_amount' => 20000,
                'balance' => 12500,
                'interest_rate' => 4.5,
                'minimum_payment' => 280,
                'due_day' => 5,
            ],
            [
                'name' => 'Credit card',
                'creditor' => 'Visa',
                'original_amount' => 3000,
                'balance' => 1850,
                'interest_rate' => 22.9,
                'minimum_payment' => 75,
                'due_day' => 20,
            ],
        ];

        foreach ($debts as $debt) {
            Debt::query()->create([
                ...$debt,
                'status' => DebtStatus::Active,
            ]);
        }
    }
}
