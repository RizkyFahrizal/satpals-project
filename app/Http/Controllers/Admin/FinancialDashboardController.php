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
        $searchTerm = trim((string) $request->get('search', ''));
        $searchBy = 'all'; // Always search across all fields (title, description, creator)

        // Calculate totals for the period
        $totalIncome = Income::approved()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $totalExpense = Expense::approved()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $totalBalance = $totalIncome - $totalExpense;

        // Breakdown by type
        $barangExpense = Expense::approved()->barang()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        $kegiatanExpense = Expense::approved()->kegiatan()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');

        // Pending items
        $pendingExpenseCount = Expense::pending()->whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingExpense = Expense::pending()->whereBetween('created_at', [$startDate, $endDate])->sum('nominal');
        
        $pendingIncomeCount = Income::pending()->whereBetween('created_at', [$startDate, $endDate])->count();

        // Recent transactions
        $recentExpenses = Expense::approved()
            ->with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentIncomes = Income::approved()
            ->with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch all expenses and incomes for display - combined
        // Note: Show ALL expenses (not filtered by status) but calculations only count approved
        $expenses = Expense::with('creator')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();
        
        // Note: Show ALL incomes (pending + approved + rejected) but calculations only count approved
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
                'description' => $expense->description,
                'nominal' => $expense->nominal,
                'creator' => $expense->creator,
                'creator_name' => $expense->creator_name ?? $expense->creator?->name ?? 'Admin',
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
                'description' => $income->description,
                'nominal' => $income->nominal,
                'creator' => $income->creator,
                'creator_name' => $income->creator_name ?? $income->creator?->name ?? 'Admin',
                'date' => $income->created_at,
                'type' => 'income',
                'source' => $income->source ?? 'Lainnya',
                'status' => $income->status ?? 'pending',
                'route' => route('admin.income.show', $income),
                'model' => $income
            ]);
        }
        
        // Sort by date descending
        $allTransactions = $allTransactions->sortByDesc('date')->values();

        // Apply filters to transactions
        // Filter by type (income/expense)
        if ($request->filter_type && $request->filter_type !== 'all') {
            $allTransactions = $allTransactions->filter(function ($transaction) use ($request) {
                return $transaction['type'] === $request->filter_type;
            });
        }

        // Filter by status
        if ($request->filter_status && $request->filter_status !== 'all') {
            $allTransactions = $allTransactions->filter(function ($transaction) use ($request) {
                return $transaction['status'] === $request->filter_status;
            });
        }

        // Filter by expense type (barang/kegiatan) - only for expenses
        if ($request->filter_expense_type && $request->filter_expense_type !== 'all') {
            $allTransactions = $allTransactions->filter(function ($transaction) use ($request) {
                if ($transaction['type'] === 'expense') {
                    return $transaction['expense_type'] === $request->filter_expense_type;
                }
                return false;
            });
        }

        // Reset indices after filtering
        if ($searchTerm !== '') {
            $normalizedSearch = mb_strtolower($searchTerm);

            $allTransactions = $allTransactions->filter(function ($transaction) use ($normalizedSearch, $searchBy) {
                $fields = [];

                if ($searchBy === 'all' || $searchBy === 'title') {
                    $fields[] = $transaction['title'] ?? '';
                }

                if ($searchBy === 'all' || $searchBy === 'description') {
                    $fields[] = $transaction['description'] ?? '';
                }

                if ($searchBy === 'all' || $searchBy === 'creator') {
                    $fields[] = $transaction['creator_name'] ?? '';
                }

                foreach ($fields as $field) {
                    if ($field !== '' && str_contains(mb_strtolower((string) $field), $normalizedSearch)) {
                        return true;
                    }
                }

                return false;
            });
        }

        $allTransactions = $allTransactions->values();

        return view('admin.financial.financial-dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'barangExpense',
            'kegiatanExpense',
            'pendingExpense',
            'pendingExpenseCount',
            'pendingIncomeCount',
            'startDate',
            'endDate',
            'allTransactions',
            'searchTerm',
            'searchBy'
        ));
    }
}
