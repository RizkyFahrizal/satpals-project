<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Income::with('creator')->latest();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('source', 'like', "%{$request->search}%");
            });
        }

        $incomes = $query->paginate(15);

        // Calculate totals
        $totalIncome = Income::sum('nominal');
        $thisMonthIncome = Income::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('nominal');

        return view('admin.income.index', compact(
            'incomes',
            'totalIncome',
            'thisMonthIncome'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.income.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nominal' => 'required|numeric|min:1000',
            'source' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = Auth::id();
        $income = Income::create($validated);

        return redirect()->route('admin.income.show', $income)->with('success', 'Pemasukan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $income->load('creator');
        return view('admin.income.show', compact('income'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(Income $income)
    {
        return view('admin.income.edit', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nominal' => 'required|numeric|min:1000',
            'source' => 'nullable|string|max:255',
        ]);

        $income->update($validated);

        return redirect()->route('admin.income.show', $income)->with('success', 'Pemasukan berhasil diubah');
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()->route('admin.income.index')->with('success', 'Pemasukan berhasil dihapus');
    }
}
