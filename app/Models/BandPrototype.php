<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandPrototype extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'title',
        'youtube_url',
        'description',
    ];

    /**
     * Get the band this prototype belongs to
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    /**
     * Extract video ID from YouTube URL
     */
    public function getYoutubeEmbedUrl()
    {
        $url = $this->youtube_url;
        
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $url;
        }
        
        return null;
    }
}
