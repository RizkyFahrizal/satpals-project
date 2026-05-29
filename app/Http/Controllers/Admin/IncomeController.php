<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\IncomeDocument;
use App\Models\IncomeApproval;
use App\Models\IncomeApprovalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
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
        $validated['creator_name'] = Auth::user()->name;  // Nama admin yang membuat
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

        return redirect()->route('admin.financial.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $income->load('creator', 'documents');
        
        // Check authorization
        $canManage = $this->canManageIncome();
        $canEdit = $canManage;
        $canDelete = $canManage;
        
        return view('admin.income.show', compact('income', 'canManage', 'canEdit', 'canDelete'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(Income $income)
    {
        if (!$this->canManageIncome()) {
            return redirect()->route('admin.income.show', $income)
                ->with('error', 'Anda tidak memiliki otorisasi untuk mengubah pemasukan');
        }

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

        // Jaga creator_name tidak berubah saat update
        $validated['creator_name'] = $income->creator_name;
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

        return redirect()->route('admin.financial.index')->with('success', 'Pemasukan berhasil diubah');
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(Income $income)
    {
        if (!$this->canManageIncome()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki otorisasi untuk menghapus pemasukan');
        }

        // Delete all associated documents
        foreach ($income->documents as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }
        
        $income->delete();

        return redirect()->route('admin.financial.index')->with('success', 'Pemasukan berhasil dihapus');
    }

    /**
     * Delete a specific document.
     */
    public function deleteDocument(IncomeDocument $document)
    {
        $income = $document->income;

        if (!$this->canManageIncome()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki otorisasi untuk menghapus dokumen pemasukan');
        }
        
        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);
        
        // Delete record
        $document->delete();

        return redirect()->route('admin.income.show', $income)->with('success', 'Dokumen berhasil dihapus');
    }

    /**
     * Check if user can manage income (admin or bendahara only)
     */
    private function canManageIncome()
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

    /**
     * Approve income
     */
    public function approve(Request $request, Income $income)
    {
        // Check authorization: only super_admin, bendahara, wakil_ketua_umum, ketua_umum can approve
        if (!$this->canManageIncome()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui pemasukan. Hanya Bendahara, Wakil Ketua Umum, dan Ketua Umum yang dapat menyetujui.');
        }

        if ($income->status !== 'pending') {
            return redirect()->back()->with('error', 'Pemasukan ini tidak pending');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $approval = IncomeApproval::create([
            'income_id' => $income->id,
            'approved_by' => Auth::id(),
            'approval_status' => 'approved',
            'notes' => $validated['notes'] ?? null,
            'approved_at' => now(),
        ]);

        // Save approval document if uploaded
        if ($request->hasFile('bukti_dokumen')) {
            try {
                $file = $request->file('bukti_dokumen');
                $path = $file->store('income-approvals', 'public');
                
                IncomeApprovalDocument::create([
                    'income_approval_id' => $approval->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'document_type' => $validated['document_type'] ?? 'bukti transfer',
                ]);
            } catch (\Exception $e) {
                \Log::error('Error uploading approval document', ['error' => $e->getMessage()]);
            }
        }

        // Check if all approvals are done (need 1 approval for income)
        $approvedCount = IncomeApproval::where('income_id', $income->id)
            ->where('approval_status', 'approved')
            ->count();

        if ($approvedCount >= 1) {
            $income->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Pemasukan berhasil disetujui');
    }

    /**
     * Reject income
     */
    public function reject(Request $request, Income $income)
    {
        // Check authorization: only super_admin, bendahara, wakil_ketua_umum, ketua_umum can reject
        if (!$this->canManageIncome()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak pemasukan. Hanya Bendahara, Wakil Ketua Umum, dan Ketua Umum yang dapat menolak.');
        }

        if ($income->status !== 'pending') {
            return redirect()->back()->with('error', 'Pemasukan ini tidak pending');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $income->update([
            'status' => 'rejected',
        ]);

        IncomeApproval::create([
            'income_id' => $income->id,
            'approved_by' => Auth::id(),
            'approval_status' => 'rejected',
            'notes' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Pemasukan berhasil ditolak');
    }
}
