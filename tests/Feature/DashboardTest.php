<?php

use App\Models\Category;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the dashboard with a monthly summary', function () {
    $category = Category::factory()->expense()->create();
    Transaction::factory()->forCategory($category)->create([
        'amount' => 125.50,
        'transaction_date' => now()->toDateString(),
    ]);
    Debt::factory()->create(['balance' => 2500]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Expenses this month')
        ->assertSee('Total debt');
});
