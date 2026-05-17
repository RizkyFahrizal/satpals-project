<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard page
     */
    public function index(Request $request): View
    {
        // Get filter parameters
        $selectedYear = $request->get('year', now()->year);
        $selectedQuarter = (string) $request->get('quarter', $this->getCurrentQuarter());
        
        // Calculate date range based on selected quarter and year
        $startDate = $this->getQuarterStartDate($selectedYear, $selectedQuarter);
        $endDate = $this->getQuarterEndDate($selectedYear, $selectedQuarter);

        // Get financial summary for selected period
        $totalIncome = Income::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('nominal') ?? 0;
        $totalExpense = Expense::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('nominal') ?? 0;
        $totalBalance = $totalIncome - $totalExpense;

        // Get counts
        // Get counts for selected period
        $incomeCount = Income::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $expenseItemCount = Expense::where('type', 'barang')
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $expenseEventCount = Expense::where('type', 'kegiatan')
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $pendingApproval = Expense::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Get monthly data for charts (last 6 months)
        // Get monthly data for charts within selected quarter
        $monthlyData = $this->getQuarterlyFinancialData($selectedYear, $selectedQuarter);

        // Get expense by category
        $expenseByCategory = [
            'barang' => Expense::where('type', 'barang')
                ->where('status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('nominal') ?? 0,
            'kegiatan' => Expense::where('type', 'kegiatan')
                ->where('status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('nominal') ?? 0,
        ];

        // Get available years from database (only years with transactions)
        $availableYears = $this->getAvailableYears();

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
            'selectedYear' => $selectedYear,
            'selectedQuarter' => $selectedQuarter,
            'availableYears' => $availableYears,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Get start date of quarter
     */
    private function getQuarterStartDate($year, $quarter)
    {
        $quarter = (int) $quarter;
        $month = (($quarter - 1) * 3) + 1;
        return Carbon::createFromDate($year, $month, 1)->startOfDay();
    }

    /**
     * Get end date of quarter
     */
    private function getQuarterEndDate($year, $quarter)
    {
        $quarter = (int) $quarter;
        $month = (($quarter) * 3);
        return Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
    }

    /**
     * Get current quarter number based on current month.
     */
    private function getCurrentQuarter(): int
    {
        return (int) ceil(now()->month / 3);
    }

    /**
     * Get quarterly financial data
     */
    private function getQuarterlyFinancialData($year, $quarter)
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        // Get start and end dates for quarter
        $startDate = $this->getQuarterStartDate($year, $quarter);
        $endDate = $this->getQuarterEndDate($year, $quarter);

        // Get all months in the quarter
        $currentDate = $startDate->clone();
        while ($currentDate <= $endDate) {
            $startOfMonth = $currentDate->clone()->startOfMonth();
            $endOfMonth = $currentDate->clone()->endOfMonth();

            $months[] = $currentDate->format('M Y');

            // Get income for this month
            $income = Income::approved()
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('nominal');
            $incomeData[] = $income;

            // Get approved expense for this month
            $expense = Expense::where('status', 'approved')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('nominal');
            $expenseData[] = $expense;

            $currentDate->addMonth();
        }

        return [
            'months' => $months,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }

    /**
     * Get available years from transactions
     */
    private function getAvailableYears()
    {
        $years = [];

        // Get years from income table (uses model table name: income)
        $incomeYears = Income::approved()
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->toArray();

        // Get years from expense table
        $expenseYears = Expense::query()
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->toArray();

        // Merge and deduplicate
        $years = array_values(array_unique(array_merge($incomeYears, $expenseYears)));
        sort($years);

        // If no years found, return current year
        return !empty($years) ? $years : [now()->year];
    }
}
