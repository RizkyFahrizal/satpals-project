<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_kegiatan',
        'tujuan_kegiatan',
        'deskripsi',
        'ketua_pelaksana',
        'divisi',
        'tanggal_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'tempat_kegiatan',
        'foto_1',
        'foto_2',
        'foto_3',
        'is_published',
    ];

    protected $casts = [
        'divisi' => 'array',
        'tanggal_kegiatan' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Get all photos as array
     */
    public function getPhotosAttribute(): array
    {
        $photos = [];
        if ($this->foto_1) $photos[] = $this->foto_1;
        if ($this->foto_2) $photos[] = $this->foto_2;
        if ($this->foto_3) $photos[] = $this->foto_3;
        return $photos;
    }

    /**
     * Get formatted waktu
     */
    public function getWaktuFormattedAttribute(): string
    {
        if ($this->waktu_mulai && $this->waktu_selesai) {
            return substr($this->waktu_mulai, 0, 5) . ' - ' . substr($this->waktu_selesai, 0, 5) . ' WIB';
        } elseif ($this->waktu_mulai) {
            return substr($this->waktu_mulai, 0, 5) . ' WIB';
        }
        return '-';
    }

    /**
     * Scope for published activities
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for ordering by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_kegiatan', 'desc');
    }
}
