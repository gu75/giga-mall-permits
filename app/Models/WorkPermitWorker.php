<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkPermitWorker extends Model
{
    protected $fillable = [
        'work_permit_id',
        'worker_name',
        'job_description',
        'cnic_number',
    ];

    public function workPermit(): BelongsTo
    {
        return $this->belongsTo(WorkPermit::class);
    }
}
