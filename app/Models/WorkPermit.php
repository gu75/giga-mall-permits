<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkPermit extends Model
{
    protected $fillable = [
        'tenant_id',
        'outlet_name',
        'floor_location',
        'site_incharge_name',
        'site_incharge_cell_no',
        'site_incharge_cnic',
        'nature_of_work',
        'requested_by',
        'requested_by_cell_no',
        'valid_from',
        'valid_to',
        'daytime_work_requested',
        'daytime_work_reason',
        'status',
        'operations_approved_by',
        'operations_approved_at',
        'operations_remarks',
        'hse_approved_by',
        'hse_approved_at',
        'hse_remarks',
        'security_approved_by',
        'security_approved_at',
        'security_remarks',
        'rejected_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_to' => 'date',
            'daytime_work_requested' => 'boolean',
            'operations_approved_at' => 'datetime',
            'hse_approved_at' => 'datetime',
            'security_approved_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function workers(): HasMany
    {
        return $this->hasMany(WorkPermitWorker::class);
    }

    public function operationsApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operations_approved_by');
    }

    public function hseApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hse_approved_by');
    }

    public function securityApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'security_approved_by');
    }
        public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Which role's action is needed next, or null if resolved (approved/rejected).
     */
    public function nextApprovalRole(): ?string
    {
        return match ($this->status) {
            'pending_operations' => 'operations',
            'pending_hse' => 'hse',
            'pending_security' => 'security',
            default => null,
        };
    }

    public function approve(User $approver, ?string $remarks = null): void
    {
        match ($this->status) {
            'pending_operations' => $this->update([
                'operations_approved_by' => $approver->id,
                'operations_approved_at' => now(),
                'operations_remarks' => $remarks,
                'status' => 'pending_hse',
            ]),
            'pending_hse' => $this->update([
                'hse_approved_by' => $approver->id,
                'hse_approved_at' => now(),
                'hse_remarks' => $remarks,
                'status' => 'pending_security',
            ]),
            'pending_security' => $this->update([
                'security_approved_by' => $approver->id,
                'security_approved_at' => now(),
                'security_remarks' => $remarks,
                'status' => 'approved',
            ]),
            default => null,
        };
    }

    public function reject(User $rejector, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_by' => $rejector->id,
            'rejection_reason' => $reason,
        ]);
    }

    public function isFullyApproved(): bool
    {
        return $this->status === 'approved';
    }
}
