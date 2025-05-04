<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionTravelerItem extends Model
{
    /** @use HasFactory<\Database\Factories\InspectionTravelerItemFactory> */
    use HasFactory;

    protected $fillable = [
        'inspection_traveler_id',
        'uuid',
        'part_number',
        'quantity',
        'description',
        'finish',
        'rev',
        'department',
        'ht_stress',
        'ship_out', //timestamp
        'shipped', //timestamp
        'deburr',
        'tooling_check',
        'process_review',
        'fai_completed'
    ];

    public function travelers(): BelongsTo
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
