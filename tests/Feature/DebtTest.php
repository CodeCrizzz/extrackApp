<?php

use App\Enums\DebtStatus;
use App\Models\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists active debts by default', function () {
    Debt::factory()->create(['name' => 'Car loan', 'status' => DebtStatus::Active]);
    Debt::factory()->paid()->create(['name' => 'Old card']);

    $this->get(route('debts.index'))
        ->assertOk()
        ->assertSee('Car loan')
        ->assertDontSee('Old card');
});

it('lists paid debts when filtering by status', function () {
    Debt::factory()->create(['name' => 'Car loan']);
    Debt::factory()->paid()->create(['name' => 'Old card']);

    $this->get(route('debts.index', ['status' => 'paid']))
        ->assertOk()
        ->assertSee('Old card')
        ->assertDontSee('Car loan');
});

it('creates a debt', function () {
    $this->post(route('debts.store'), [
        'name' => 'Credit card',
        'creditor' => 'Visa',
        'original_amount' => 3000,
        'balance' => 1850.50,
        'interest_rate' => 22.9,
        'minimum_payment' => 75,
        'due_day' => 20,
        'status' => 'active',
    ])->assertRedirect(route('debts.index'));

    $this->assertDatabaseHas('debts', [
        'name' => 'Credit card',
        'balance' => 1850.50,
        'status' => 'active',
    ]);
});

it('validates debt fields', function () {
    $this->post(route('debts.store'), [])
        ->assertSessionHasErrors(['name', 'original_amount', 'balance', 'status']);
});

it('updates a debt balance', function () {
    $debt = Debt::factory()->create(['balance' => 1000]);

    $this->put(route('debts.update', $debt), [
        'name' => $debt->name,
        'original_amount' => $debt->original_amount,
        'balance' => 400,
        'status' => 'active',
    ])->assertRedirect(route('debts.index'));

    $this->assertDatabaseHas('debts', ['id' => $debt->id, 'balance' => 400]);
});

it('deletes a debt', function () {
    $debt = Debt::factory()->create();

    $this->delete(route('debts.destroy', $debt))
        ->assertRedirect(route('debts.index'));

    $this->assertDatabaseMissing('debts', ['id' => $debt->id]);
});
