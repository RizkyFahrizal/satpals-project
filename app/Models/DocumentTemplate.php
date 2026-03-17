<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_template',
        'kategori',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'deskripsi',
    ];

    const KATEGORI_OPTIONS = [
        'surat' => 'Template Surat',
        'rab' => 'Template RAB',
        'proposal' => 'Template Proposal',
        'lpj' => 'Template LPJ',
        'lainnya' => 'Lainnya',
    ];

    public function getKategoriLabelAttribute()
    {
        return self::KATEGORI_OPTIONS[$this->kategori] ?? $this->kategori;
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'pdf' => '📕',
            'docx', 'doc' => '📘',
            'xlsx', 'xls' => '📗',
            default => '📄',
        };
    }
}
