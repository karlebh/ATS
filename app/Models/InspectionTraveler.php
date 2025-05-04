<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionTraveler extends Model
{
    /** @use HasFactory<\Database\Factories\InspectionTravelerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_email',
        'traveler_number',
        'start_at',
        'status',
        'due_at',
        'completed_at',
        'files',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'files' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionTravelerItem::class);
    }

    public function operations(): HasMany
    {
        return $this->hasMany(InspectionTravelerOperation::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }
}
