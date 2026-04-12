<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialDashboardController extends Controller
{
    /**
     * Display financial dashboard
     */
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Calculate totals for the period
        $totalIncome = Income::whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $totalExpense = Expense::approved()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $totalBalance = $totalIncome - $totalExpense;

        // Breakdown by type
        $barangExpense = Expense::approved()->barang()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $kegiatanExpense = Expense::approved()->kegiatan()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');

        // Pending items
        $pendingExpense = Expense::pending()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $pendingCount = Expense::pending()->whereBetween('created_at', [$startDate, $endDate])->count();

        // Recent transactions
        $recentExpenses = Expense::approved()
            ->with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentIncomes = Income::with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch all expenses and incomes for display - combined
        $expenses = Expense::with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();
        
        $incomes = Income::with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        // Combine transactions and sort by date
        $allTransactions = collect();
        
        // Add expenses
        foreach ($expenses as $expense) {
            $allTransactions->push([
                'id' => $expense->id,
                'title' => $expense->title,
                'nominal' => $expense->nominal,
                'creator' => $expense->creator,
                'date' => $expense->created_at,
                'type' => 'expense',
                'expense_type' => $expense->type,
                'status' => $expense->status,
                'route' => route('admin.expenses.show', $expense),
                'model' => $expense
            ]);
        }
        
        // Add incomes
        foreach ($incomes as $income) {
            $allTransactions->push([
                'id' => $income->id,
                'title' => $income->title,
                'nominal' => $income->nominal,
                'creator' => $income->creator,
                'date' => $income->created_at,
                'type' => 'income',
                'source' => $income->source,
                'route' => route('admin.income.show', $income),
                'model' => $income
            ]);
        }
        
        // Sort by date descending
        $allTransactions = $allTransactions->sortByDesc('date')->values();

        return view('admin.financial-dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'barangExpense',
            'kegiatanExpense',
            'pendingExpense',
            'pendingCount',
            'startDate',
            'endDate',
            'allTransactions'
        ));
    }
}
