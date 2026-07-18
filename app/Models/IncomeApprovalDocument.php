<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeApprovalDocument extends Model
{
    protected $table = 'income_approval_documents';

    protected $fillable = [
        'income_approval_id',
        'file_path',
        'original_name',
        'document_type',
    ];

    public function approval()
    {
        return $this->belongsTo(IncomeApproval::class, 'income_approval_id');
    }
}
