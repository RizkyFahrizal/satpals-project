<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\IncomeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'income_date' => 'required|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_types.*' => 'nullable|string|max:100',
        ]);

        $validated['created_by'] = Auth::id();
        $income = Income::create($validated);

        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('incomes', 'public');
                IncomeDocument::create([
                    'income_id' => $income->id,
                    'file_path' => $path,
                    'document_type' => $request->input('document_types.' . $index),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('admin.income.show', $income)->with('success', 'Pemasukan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $income->load('creator', 'documents');
        return view('admin.income.show', compact('income'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(Income $income)
    {
        $income->load('documents');
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
            'income_date' => 'required|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_types.*' => 'nullable|string|max:100',
        ]);

        $income->update($validated);

        // Handle new file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('incomes', 'public');
                IncomeDocument::create([
                    'income_id' => $income->id,
                    'file_path' => $path,
                    'document_type' => $request->input('document_types.' . $index),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('admin.income.show', $income)->with('success', 'Pemasukan berhasil diubah');
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Income $income)
    {
        // Delete all associated documents
        foreach ($income->documents as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }
        
        $income->delete();

        return redirect()->route('admin.income.index')->with('success', 'Pemasukan berhasil dihapus');
    }

    /**
     * Delete a specific document.
     */
    public function deleteDocument(IncomeDocument $document)
    {
        $income = $document->income;
        
        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);
        
        // Delete record
        $document->delete();

        return redirect()->route('admin.income.show', $income)->with('success', 'Dokumen berhasil dihapus');
    }
}
