<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'type' => ['nullable', Rule::enum(TransactionType::class)],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'search' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $query = Transaction::query()
            ->with('category')
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->ofType(TransactionType::from($type)))
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('description', 'like', "%{$search}%"))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('transaction_date', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('transaction_date', '<=', $to));

        $filteredIncome = (float) (clone $query)->ofType(TransactionType::Income)->sum('amount');
        $filteredExpenses = (float) (clone $query)->ofType(TransactionType::Expense)->sum('amount');

        return view('transactions.index', [
            'transactions' => $query->latest('transaction_date')->latest('id')->paginate(15)->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'filters' => $filters,
            'filteredIncome' => $filteredIncome,
            'filteredExpenses' => $filteredExpenses,
        ]);
    }

    public function create(): View
    {
        return view('transactions.create', [
            'categories' => Category::query()->orderBy('type')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        Transaction::query()->create($request->validated());

        return to_route('transactions.index')->with('status', 'Transaction added.');
    }

    public function show(Transaction $transaction): void
    {
        abort(404);
    }

    public function edit(Transaction $transaction): View
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => Category::query()->orderBy('type')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($request->validated());

        return to_route('transactions.index')->with('status', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return to_route('transactions.index')->with('status', 'Transaction deleted.');
    }
}
