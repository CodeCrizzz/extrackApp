<?php

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists transactions', function () {
    $transaction = Transaction::factory()->expense()->create(['description' => 'Weekly groceries']);

    $this->get(route('transactions.index'))
        ->assertOk()
        ->assertSee('Weekly groceries')
        ->assertSee($transaction->category->name);
});

it('filters transactions by type and search term', function () {
    $income = Transaction::factory()->income()->create(['description' => 'Paycheck']);
    Transaction::factory()->expense()->create(['description' => 'Groceries']);

    $this->get(route('transactions.index', ['type' => 'income', 'search' => 'Pay']))
        ->assertOk()
        ->assertSee('Paycheck')
        ->assertDontSee('Groceries');
});

it('creates a transaction', function () {
    $category = Category::factory()->expense()->create();

    $response = $this->post(route('transactions.store'), [
        'type' => 'expense',
        'category_id' => $category->id,
        'description' => 'Coffee',
        'amount' => 4.50,
        'transaction_date' => now()->toDateString(),
        'notes' => 'With a friend',
    ]);

    $response->assertRedirect(route('transactions.index'));

    $this->assertDatabaseHas('transactions', [
        'description' => 'Coffee',
        'amount' => 4.50,
        'type' => 'expense',
        'category_id' => $category->id,
    ]);
});

it('rejects a category that does not match the transaction type', function () {
    $category = Category::factory()->expense()->create();

    $this->post(route('transactions.store'), [
        'type' => 'income',
        'category_id' => $category->id,
        'description' => 'Mismatch',
        'amount' => 100,
        'transaction_date' => now()->toDateString(),
    ])->assertSessionHasErrors('category_id');

    $this->assertDatabaseCount('transactions', 0);
});

it('validates required transaction fields', function () {
    $this->post(route('transactions.store'), [])
        ->assertSessionHasErrors(['type', 'description', 'amount', 'transaction_date']);
});

it('updates a transaction', function () {
    $transaction = Transaction::factory()->expense()->create(['description' => 'Old name']);
    $category = Category::factory()->expense()->create();

    $this->put(route('transactions.update', $transaction), [
        'type' => 'expense',
        'category_id' => $category->id,
        'description' => 'New name',
        'amount' => 75,
        'transaction_date' => now()->toDateString(),
    ])->assertRedirect(route('transactions.index'));

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'description' => 'New name',
        'category_id' => $category->id,
    ]);
});

it('deletes a transaction', function () {
    $transaction = Transaction::factory()->create();

    $this->delete(route('transactions.destroy', $transaction))
        ->assertRedirect(route('transactions.index'));

    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
});
