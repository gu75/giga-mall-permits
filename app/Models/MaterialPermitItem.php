<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialPermitItem extends Model
{
    protected $fillable = [
        'material_permit_id',
        'description',
        'quantity',
        'remarks',
    ];

    public function materialPermit(): BelongsTo
    {
        return $this->belongsTo(MaterialPermit::class);
    }
}
