<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkPermitDepartmentApproval extends Model
{
    protected $fillable = [
        'work_permit_id',
        'department',
        'status',
        'actioned_by',
        'actioned_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return ['actioned_at' => 'datetime'];
    }

    public function workPermit(): BelongsTo
    {
        return $this->belongsTo(WorkPermit::class);
    }

    public function actionedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by');
    }
}