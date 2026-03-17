<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentTemplate::query();

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('nama_template', 'like', '%' . $request->search . '%');
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'kategori' => 'required|string|in:surat,rab,proposal,lpj,lainnya',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Max 10MB
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama_template.required' => 'Nama template wajib diisi',
            'kategori.required' => 'Kategori wajib dipilih',
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'Format file harus PDF, Word (doc/docx), atau Excel (xls/xlsx)',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        
        // Store file
        $filePath = $file->store('document-templates', 'public');

        DocumentTemplate::create([
            'nama_template' => $request->nama_template,
            'kategori' => $request->kategori,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => strtolower($fileType),
            'file_size' => $fileSize,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil ditambahkan!');
    }

    public function update(Request $request, DocumentTemplate $template)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'kategori' => 'required|string|in:surat,rab,proposal,lpj,lainnya',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        $data = [
            'nama_template' => $request->nama_template,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
        ];

        // If new file uploaded
        if ($request->hasFile('file')) {
            // Delete old file
            if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
                Storage::disk('public')->delete($template->file_path);
            }

            $file = $request->file('file');
            $data['file_path'] = $file->store('document-templates', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = strtolower($file->getClientOriginalExtension());
            $data['file_size'] = $file->getSize();
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui!');
    }

    public function destroy(DocumentTemplate $template)
    {
        // Delete file from storage
        if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil dihapus!');
    }

    public function download(DocumentTemplate $template)
    {
        if (!Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return Storage::disk('public')->download($template->file_path, $template->file_name);
    }

    public function preview(DocumentTemplate $template)
    {
        if (!Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        // For PDF, return file for browser preview
        if ($template->file_type === 'pdf') {
            return response()->file(Storage::disk('public')->path($template->file_path));
        }

        // For other files, show preview page with download option
        return view('admin.templates.preview', compact('template'));
    }
}
