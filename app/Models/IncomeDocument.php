<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeDocument extends Model
{
    protected $fillable = [
        'income_id',
        'file_path',
        'document_type',
        'original_name',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }
}
