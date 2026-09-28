<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialPermit extends Model
{
    protected $fillable = [
        'tenant_id',
        'shop_type',
        'shop_details',
        'manager_sup_name',
        'manager_sup_cell_no',
        'cnic_no',
        'dated',
        'time',
        'direction',
        'status',
        'operations_approved_by',
        'operations_approved_at',
        'operations_remarks',
        'rejected_by',
        'rejection_reason',
        'gate_logged_by',
        'gate_logged_at',
        'gate_remarks',
    ];

    protected function casts(): array
    {
        return [
            'dated' => 'date',
            'time' => 'datetime:H:i',
            'operations_approved_at' => 'datetime',
            'gate_logged_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialPermitItem::class);
    }

    public function operationsApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operations_approved_by');
    }

    public function gateLogger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gate_logged_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function approve(User $approver, ?string $remarks = null): void
    {
        $this->update([
            'operations_approved_by' => $approver->id,
            'operations_approved_at' => now(),
            'operations_remarks' => $remarks,
            'status' => 'approved',
        ]);
    }

    public function reject(User $rejector, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_by' => $rejector->id,
            'rejection_reason' => $reason,
        ]);
    }

    public function logGatePass(User $securityUser, ?string $remarks = null): void
    {
        $this->update([
            'gate_logged_by' => $securityUser->id,
            'gate_logged_at' => now(),
            'gate_remarks' => $remarks,
            'status' => 'gate_cleared',
        ]);
    }
}
