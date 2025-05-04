<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaterialInventory extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialInventoryFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'title',
        'quantity',
        'status',
        'description',
        'purchased_at',
        'finished_at',
        'ordered_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }
}
