<?php

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists expense and income categories', function () {
    Category::factory()->expense()->create(['name' => 'Groceries']);
    Category::factory()->income()->create(['name' => 'Salary']);

    $this->get(route('categories.index'))
        ->assertOk()
        ->assertSee('Groceries')
        ->assertSee('Salary')
        ->assertSee('Expense categories')
        ->assertSee('Income categories');
});

it('creates a category', function () {
    $this->post(route('categories.store'), [
        'name' => 'Travel',
        'type' => 'expense',
        'color' => '#0ea5e9',
    ])->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Travel',
        'type' => 'expense',
        'color' => '#0ea5e9',
    ]);
});

it('validates a category color', function () {
    $this->post(route('categories.store'), [
        'name' => 'Travel',
        'type' => 'expense',
        'color' => 'blue',
    ])->assertSessionHasErrors('color');
});

it('updates a category', function () {
    $category = Category::factory()->expense()->create(['name' => 'Food']);

    $this->put(route('categories.update', $category), [
        'name' => 'Dining',
        'type' => 'expense',
        'color' => '#f43f5e',
    ])->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Dining']);
});

it('uncategorizes transactions when a category is deleted', function () {
    $category = Category::factory()->expense()->create();
    $transaction = Transaction::factory()->forCategory($category)->create();

    $this->delete(route('categories.destroy', $category))
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'category_id' => null,
    ]);
});
