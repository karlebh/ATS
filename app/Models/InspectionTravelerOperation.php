<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionTravelerOperation extends Model
{
    /** @use HasFactory<\Database\Factories\InspectionTravelerOperationFactory> */
    use HasFactory;

    protected $fillable = [
        'inspection_traveler_id',
        'uuid',
        'outside_ops',
        'vendor',
        'out_by',
        'back_by',
    ];

    public function traveler(): BelongsTo
    {
        return $this->belongsTo(InspectionTraveler::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = str()->uuid();
        });
    }
}
