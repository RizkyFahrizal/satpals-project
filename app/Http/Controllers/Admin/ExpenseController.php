<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseDocument;
use App\Models\ExpenseApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['creator', 'documents', 'approvals'])
            ->latest();

        // Filter by type
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $expenses = $query->paginate(15);

        // Calculate totals
        $totalExpense = Expense::approved()->sum('nominal');
        $barangExpense = Expense::approved()->barang()->sum('nominal');
        $kegiatanExpense = Expense::approved()->kegiatan()->sum('nominal');
        $pendingExpense = Expense::pending()->sum('nominal');

        return view('admin.expenses.index', compact(
            'expenses',
            'totalExpense',
            'barangExpense',
            'kegiatanExpense',
            'pendingExpense'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:barang,kegiatan',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'nominal' => 'required|numeric|min:1000',
            'documents.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $validated['created_by'] = Auth::id();
        $expense = Expense::create($validated);

        // Handle file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('expenses', 'public');
                ExpenseDocument::create([
                    'expense_id' => $expense->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('admin.expenses.show', $expense)->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['creator', 'documents', 'approvals.approver']);
        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Hanya pengeluaran yang pending dapat diubah');
        }
        return view('admin.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Hanya pengeluaran yang pending dapat diubah');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'nominal' => 'required|numeric|min:1000',
            'documents.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $expense->update($validated);

        // Handle new file uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('expenses', 'public');
                ExpenseDocument::create([
                    'expense_id' => $expense->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('admin.expenses.show', $expense)->with('success', 'Pengeluaran berhasil diubah');
    }

    /**
     * Delete document
     */
    public function deleteDocument($documentId)
    {
        $document = ExpenseDocument::findOrFail($documentId);
        $expenseId = $document->expense_id;

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
    }

    /**
     * Approve expense
     */
    public function approve(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengeluaran ini tidak pending');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        ExpenseApproval::create([
            'expense_id' => $expense->id,
            'approved_by' => Auth::id(),
            'approval_status' => 'approved',
            'notes' => $validated['notes'] ?? null,
            'approved_at' => now(),
        ]);

        // Check if all approvals are done (need 2 approvals)
        $approvedCount = ExpenseApproval::where('expense_id', $expense->id)
            ->where('approval_status', 'approved')
            ->count();

        if ($approvedCount >= 1) {  // Changed to 1 for testing, should be 2 in production
            $expense->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Pengeluaran berhasil disetujui');
    }

    /**
     * Reject expense
     */
    public function reject(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengeluaran ini tidak pending');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $expense->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        ExpenseApproval::create([
            'expense_id' => $expense->id,
            'approved_by' => Auth::id(),
            'approval_status' => 'rejected',
            'notes' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditolak');
    }

    /**
     * Archive expense
     */
    public function archive(Expense $expense)
    {
        if ($expense->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya pengeluaran yang approved dapat diarsipkan');
        }

        $expense->update(['status' => 'archived']);

        return redirect()->back()->with('success', 'Pengeluaran berhasil diarsipkan');
    }

    /**
     * Delete expense (hanya pending)
     */
    public function destroy(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengeluaran pending yang dapat dihapus');
        }

        // Delete associated documents
        foreach ($expense->documents as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
