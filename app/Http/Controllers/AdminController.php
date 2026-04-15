<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard page
     */
    public function index(): View
    {
        // Get financial summary
        $totalIncome = Income::sum('nominal') ?? 0;
        $totalExpense = Expense::where('status', 'approved')->sum('nominal') ?? 0;
        $totalBalance = $totalIncome - $totalExpense;

        // Get counts
        $incomeCount = Income::count();
        $expenseItemCount = Expense::where('type', 'barang')->where('status', 'approved')->count();
        $expenseEventCount = Expense::where('type', 'kegiatan')->where('status', 'approved')->count();
        $pendingApproval = Expense::where('status', 'pending')->count();

        // Get monthly data for charts (last 6 months)
        $monthlyData = $this->getMontlyFinancialData();

        // Get expense by category
        $expenseByCategory = [
            'barang' => Expense::where('type', 'barang')->where('status', 'approved')->sum('nominal') ?? 0,
            'kegiatan' => Expense::where('type', 'kegiatan')->where('status', 'approved')->sum('nominal') ?? 0,
        ];

        return view('admin.index', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalBalance' => $totalBalance,
            'incomeCount' => $incomeCount,
            'expenseItemCount' => $expenseItemCount,
            'expenseEventCount' => $expenseEventCount,
            'pendingApproval' => $pendingApproval,
            'monthlyData' => $monthlyData,
            'expenseByCategory' => $expenseByCategory,
        ]);
    }

    /**
     * Get monthly financial data for last 6 months
     */
    private function getMontlyFinancialData()
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->clone()->startOfMonth();
            $endOfMonth = $date->clone()->endOfMonth();

            $months[] = $date->format('M Y'); // e.g., "Dec 2024"

            // Get income for this month (using created_at)
            $income = Income::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('nominal');
            $incomeData[] = $income;

            // Get approved expense for this month (using expense_date)
            $expense = Expense::where('status', 'approved')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('nominal');
            $expenseData[] = $expense;
        }

        return [
            'months' => $months,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
}
