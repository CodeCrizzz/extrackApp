<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDebtRequest;
use App\Http\Requests\UpdateDebtRequest;
use App\Models\Debt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DebtController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['active', 'paid', 'all'])],
        ]);

        $status = $filters['status'] ?? 'active';

        $debts = Debt::query()
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->orderByDesc('balance')
            ->paginate(10)
            ->withQueryString();

        return view('debts.index', [
            'debts' => $debts,
            'filters' => $filters,
            'status' => $status,
            'activeTotal' => (float) Debt::query()->active()->sum('balance'),
            'activeMinimums' => (float) Debt::query()->active()->sum('minimum_payment'),
            'activeCount' => Debt::query()->active()->count(),
        ]);
    }

    public function create(): View
    {
        return view('debts.create');
    }

    public function store(StoreDebtRequest $request): RedirectResponse
    {
        Debt::query()->create($request->validated());

        return to_route('debts.index')->with('status', 'Debt added.');
    }

    public function show(Debt $debt): void
    {
        abort(404);
    }

    public function edit(Debt $debt): View
    {
        return view('debts.edit', [
            'debt' => $debt,
        ]);
    }

    public function update(UpdateDebtRequest $request, Debt $debt): RedirectResponse
    {
        $debt->update($request->validated());

        return to_route('debts.index')->with('status', 'Debt updated.');
    }

    public function destroy(Debt $debt): RedirectResponse
    {
        $debt->delete();

        return to_route('debts.index')->with('status', 'Debt deleted.');
    }
}
