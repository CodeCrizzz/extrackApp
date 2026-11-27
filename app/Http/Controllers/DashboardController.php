<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthIncome = (float) Transaction::query()
            ->ofType(TransactionType::Income)
            ->betweenDates($startOfMonth->toDateString(), $endOfMonth->toDateString())
            ->sum('amount');

        $monthExpenses = (float) Transaction::query()
            ->ofType(TransactionType::Expense)
            ->betweenDates($startOfMonth->toDateString(), $endOfMonth->toDateString())
            ->sum('amount');

        $totalDebt = (float) Debt::query()->active()->sum('balance');
        $activeDebts = Debt::query()->active()->count();

        $recentTransactions = Transaction::query()
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->take(8)
            ->get();

        $categoryBreakdown = Transaction::query()
            ->ofType(TransactionType::Expense)
            ->betweenDates($startOfMonth->toDateString(), $endOfMonth->toDateString())
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        $breakdownTotal = (float) $categoryBreakdown->sum('total');

        $monthlyTrend = collect(range(5, 0))->map(function (int $monthsAgo): array {
            $start = now()->startOfMonth()->subMonths($monthsAgo);
            $end = $start->copy()->endOfMonth();

            return [
                'label' => $start->format('M'),
                'income' => (float) Transaction::query()
                    ->ofType(TransactionType::Income)
                    ->betweenDates($start->toDateString(), $end->toDateString())
                    ->sum('amount'),
                'expenses' => (float) Transaction::query()
                    ->ofType(TransactionType::Expense)
                    ->betweenDates($start->toDateString(), $end->toDateString())
                    ->sum('amount'),
            ];
        });

        $trendMax = max(1, $monthlyTrend->max(fn (array $month): float => max($month['income'], $month['expenses'])));

        return view('dashboard', [
            'monthIncome' => $monthIncome,
            'monthExpenses' => $monthExpenses,
            'monthNet' => $monthIncome - $monthExpenses,
            'totalDebt' => $totalDebt,
            'activeDebts' => $activeDebts,
            'recentTransactions' => $recentTransactions,
            'categoryBreakdown' => $categoryBreakdown,
            'breakdownTotal' => $breakdownTotal,
            'monthlyTrend' => $monthlyTrend,
            'trendMax' => $trendMax,
        ]);
    }
}
