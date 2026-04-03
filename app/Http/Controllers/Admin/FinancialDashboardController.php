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

        // Monthly breakdown for charts
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->locale('id')->translatedFormat('M Y');
            
            $income = Income::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('nominal');
            
            $expense = Expense::approved()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('nominal');
            
            $monthlyData[] = [
                'month' => $monthLabel,
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense
            ];
        }

        return view('admin.financial-dashboard', compact(
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'barangExpense',
            'kegiatanExpense',
            'pendingExpense',
            'pendingCount',
            'recentExpenses',
            'recentIncomes',
            'monthlyData',
            'startDate',
            'endDate'
        ));
    }
}
