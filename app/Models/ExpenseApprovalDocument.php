<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseApprovalDocument extends Model
{
    protected $table = 'expense_approval_documents';

    protected $fillable = [
        'expense_approval_id',
        'file_path',
        'original_name',
        'document_type',
    ];

    public function approval()
    {
        return $this->belongsTo(ExpenseApproval::class, 'expense_approval_id');
    }
}
