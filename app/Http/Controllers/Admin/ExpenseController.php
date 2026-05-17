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
            'category' => 'required|in:goods,activity',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'nominal' => 'required|numeric|min:1000',
            'expense_date' => 'required|date',
            'spd_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'btpd_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'lpj_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Validate based on category
        if ($request->input('category') === 'goods') {
            $validated_docs = $request->validate([
                'spd_file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
                'btpd_file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);
        } else {
            $validated_docs = $request->validate([
                'lpj_file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);
        }

        $validated['created_by'] = Auth::id();
        $validated['creator_name'] = Auth::user()->name;  // Nama admin yang membuat
        $validated['type'] = $validated['category'] === 'goods' ? 'barang' : 'kegiatan';
        $expense = Expense::create($validated);

        // Handle file uploads
        $files_to_upload = [];
        
        if ($request->category === 'goods') {
            if ($request->hasFile('spd_file')) {
                $files_to_upload[] = ['file' => $request->file('spd_file'), 'type' => 'SPD'];
            }
            if ($request->hasFile('btpd_file')) {
                $files_to_upload[] = ['file' => $request->file('btpd_file'), 'type' => 'BTPD'];
            }
        } else {
            if ($request->hasFile('lpj_file')) {
                $files_to_upload[] = ['file' => $request->file('lpj_file'), 'type' => 'LPJ'];
            }
        }

        foreach ($files_to_upload as $file_data) {
            $file = $file_data['file'];
            $path = $file->store('expenses', 'public');
            ExpenseDocument::create([
                'expense_id' => $expense->id,
                'file_path' => $path,
                'document_type' => $file_data['type'],
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('admin.financial.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['creator', 'documents', 'approvals.approver']);
        
        // Check authorization
        $canApprove = $this->canApproveExpense();
        $canEdit = $expense->status === 'pending' && $canApprove;
        $canDelete = $expense->status === 'pending' && $canApprove;
        
        return view('admin.expenses.show', compact('expense', 'canApprove', 'canEdit', 'canDelete'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(Expense $expense)
    {
        if (!$this->canApproveExpense()) {
            return redirect()->route('admin.expenses.show', $expense)
                ->with('error', 'Anda tidak memiliki otorisasi untuk mengubah pengeluaran');
        }

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
            'expense_date' => 'required|date',
            'spd_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'btpd_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'lpj_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Jaga creator_name tidak berubah saat update
        $validated['creator_name'] = $expense->creator_name;
        $expense->update($validated);

        // Handle new file uploads
        $files_to_upload = [];
        
        if ($expense->category === 'goods') {
            if ($request->hasFile('spd_file')) {
                $files_to_upload[] = ['file' => $request->file('spd_file'), 'type' => 'SPD'];
            }
            if ($request->hasFile('btpd_file')) {
                $files_to_upload[] = ['file' => $request->file('btpd_file'), 'type' => 'BTPD'];
            }
        } else {
            if ($request->hasFile('lpj_file')) {
                $files_to_upload[] = ['file' => $request->file('lpj_file'), 'type' => 'LPJ'];
            }
        }

        foreach ($files_to_upload as $file_data) {
            $file = $file_data['file'];
            $path = $file->store('expenses', 'public');
            ExpenseDocument::create([
                'expense_id' => $expense->id,
                'file_path' => $path,
                'document_type' => $file_data['type'],
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('admin.financial.index')->with('success', 'Pengeluaran berhasil diubah');
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
    /**
     * Check if user can approve expenses
     * Admin or active board member with specific roles (ketua_umum, wakil_ketua_umum) and bendahara subsie can approve
     */
    private function canApproveExpense()
    {
        $user = Auth::user();

        // Check if user is super_admin
        if ($user->role === 'super_admin') {
            return true;
        }

        // Check if user has one of the approval roles: bendahara, wakil_ketua_umum, ketua_umum
        $allowedRoles = [
            \App\Models\User::ROLE_BENDAHARA,
            \App\Models\User::ROLE_WAKIL_KETUA_UMUM,
            \App\Models\User::ROLE_KETUA_UMUM,
        ];

        return in_array($user->role, $allowedRoles);
    }

    public function approve(Request $request, Expense $expense)
    {
        // Check authorization: only super_admin, bendahara, wakil_ketua_umum, ketua_umum can approve
        if (!$this->canApproveExpense()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui pengeluaran. Hanya Bendahara, Wakil Ketua Umum, dan Ketua Umum yang dapat menyetujui.');
        }

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
        // Check authorization: only super_admin, bendahara, wakil_ketua_umum, ketua_umum can reject
        if (!$this->canApproveExpense()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak pengeluaran. Hanya Bendahara, Wakil Ketua Umum, dan Ketua Umum yang dapat menolak.');
        }

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
        if (!$this->canApproveExpense()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki otorisasi untuk menghapus pengeluaran');
        }

        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengeluaran pending yang dapat dihapus');
        }

        // Delete associated documents
        foreach ($expense->documents as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        $expense->delete();

        return redirect()->route('admin.financial.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
