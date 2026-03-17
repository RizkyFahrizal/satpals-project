<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    const JUARA_OPTIONS = [
        'juara_1' => 'Juara 1',
        'juara_2' => 'Juara 2',
        'juara_3' => 'Juara 3',
        'juara_harapan_1' => 'Juara Harapan 1',
        'juara_harapan_2' => 'Juara Harapan 2',
        'juara_harapan_3' => 'Juara Harapan 3',
        'finalis' => 'Finalis',
        'semifinalis' => 'Semifinalis',
        'best_performance' => 'Best Performance',
        'best_vocal' => 'Best Vocal',
        'best_musician' => 'Best Musician',
        'favorit' => 'Juara Favorit',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'judul_lomba',
        'juara',
        'deskripsi',
        'nama_band',
        'anggota',
        'tanggal_lomba',
        'tempat_lomba',
        'penyelenggara',
        'foto_1',
        'foto_2',
        'foto_3',
        'is_published',
    ];

    protected $casts = [
        'anggota' => 'array',
        'tanggal_lomba' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Get juara label
     */
    public function getJuaraLabelAttribute(): string
    {
        return self::JUARA_OPTIONS[$this->juara] ?? $this->juara;
    }

    /**
     * Get anggota as comma-separated string
     */
    public function getAnggotaStringAttribute(): string
    {
        if (!$this->anggota || count($this->anggota) === 0) {
            return '-';
        }
        return implode(', ', $this->anggota);
    }

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
     * Scope for published achievements
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope order by date descending
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_lomba', 'desc');
    }
}
