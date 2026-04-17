<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeApproval extends Model
{
    use HasFactory;

    protected $table = 'income_approvals';

    protected $fillable = [
        'income_id',
        'approved_by',
        'approval_status',
        'notes',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
