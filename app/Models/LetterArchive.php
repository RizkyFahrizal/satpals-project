<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_surat',
        'nomor_surat',
        'jenis',
        'tanggal_surat',
        'pengirim',
        'penerima',
        'perihal',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    const JENIS_OPTIONS = [
        'masuk' => 'Surat Masuk',
        'keluar' => 'Surat Keluar',
    ];

    public function getJenisLabelAttribute()
    {
        return self::JENIS_OPTIONS[$this->jenis] ?? $this->jenis;
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

    public function getJenisIconAttribute()
    {
        return $this->jenis === 'masuk' ? '📥' : '📤';
    }

    public function getJenisBadgeClassAttribute()
    {
        return $this->jenis === 'masuk' ? 'badge-info' : 'badge-success';
    }
}
