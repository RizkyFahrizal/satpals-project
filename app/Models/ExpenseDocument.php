<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'document_type',
        'file_path',
        'original_name',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
