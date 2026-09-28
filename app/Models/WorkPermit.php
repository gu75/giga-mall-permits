<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkPermit extends Model
{
    // Departments required to approve AFTER Operations, in parallel.
    // Add a new department here later — no migration needed.
    const REQUIRED_DEPARTMENTS = ['hse', 'security'];

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
        'valid_from_time',
        'valid_to',
        'valid_to_time',
        'daytime_work_requested',
        'daytime_work_reason',
        'status',
        'operations_approved_by',
        'operations_approved_at',
        'operations_remarks',
        'rejected_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_from_time' => 'datetime:H:i',
            'valid_to' => 'date',
            'valid_to_time' => 'datetime:H:i',
            'daytime_work_requested' => 'boolean',
            'operations_approved_at' => 'datetime',
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

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function departmentApprovals(): HasMany
    {
        return $this->hasMany(WorkPermitDepartmentApproval::class);
    }

    public function departmentStatus(string $department): ?string
    {
        return $this->departmentApprovals->firstWhere('department', $department)?->status;
    }

    /** Departments that still need to approve (excludes already-approved ones). */
    public function pendingDepartments(): array
    {
        return array_values(array_filter(
            self::REQUIRED_DEPARTMENTS,
            fn ($dept) => $this->departmentStatus($dept) !== 'approved'
        ));
    }

    public function canDepartmentAct(string $department): bool
    {
        if ($department === 'operations') {
            return $this->status === 'pending_operations';
        }

        if (in_array($department, self::REQUIRED_DEPARTMENTS, true)) {
            return $this->status === 'in_review' && $this->departmentStatus($department) === null;
        }

        return false;
    }

    public function approve(User $approver, ?string $remarks = null): void
    {
        if ($approver->isAdmin()) {
            // Refresh so we're checking real, current department statuses
            // before deciding what admin needs to fill in.
            $this->load('departmentApprovals');

            if (is_null($this->operations_approved_at)) {
                $this->update([
                    'operations_approved_by' => $approver->id,
                    'operations_approved_at' => now(),
                    'operations_remarks' => $this->operations_remarks ?? $remarks,
                ]);
            }

            foreach (self::REQUIRED_DEPARTMENTS as $dept) {
                // Skip any department that already has a real approval —
                // never overwrite HSE's/Security's own signature with admin's.
                if ($this->departmentStatus($dept) === 'approved') {
                    continue;
                }

                $this->departmentApprovals()->updateOrCreate(
                    ['department' => $dept],
                    [
                        'status' => 'approved',
                        'actioned_by' => $approver->id,
                        'actioned_at' => now(),
                        'remarks' => $remarks,
                    ]
                );
            }

            $this->update(['status' => 'approved']);

            return;
        }

        if ($approver->role === 'operations') {
            $this->update([
                'operations_approved_by' => $approver->id,
                'operations_approved_at' => now(),
                'operations_remarks' => $remarks,
                'status' => 'in_review',
            ]);

            return;
        }

        if (in_array($approver->role, self::REQUIRED_DEPARTMENTS, true)) {
            $this->departmentApprovals()->updateOrCreate(
                ['department' => $approver->role],
                [
                    'status' => 'approved',
                    'actioned_by' => $approver->id,
                    'actioned_at' => now(),
                    'remarks' => $remarks,
                ]
            );

            $this->load('departmentApprovals');

            if (empty($this->pendingDepartments())) {
                $this->update(['status' => 'approved']);
            }
        }
    }

    public function reject(User $rejector, string $reason): void
    {
        if (! $rejector->isAdmin() && in_array($rejector->role, self::REQUIRED_DEPARTMENTS, true)) {
            $this->departmentApprovals()->updateOrCreate(
                ['department' => $rejector->role],
                [
                    'status' => 'rejected',
                    'actioned_by' => $rejector->id,
                    'actioned_at' => now(),
                    'remarks' => $reason,
                ]
            );
        }

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
