<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'router_id',
        'po_number',
        'client_name',
        'client_email',
        'client_company_name',
        'budget',
        'progress',
        'status',
        'current_team',
        'start_date',
        'end_date',
        'files',
        'archived',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'float',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'files' => 'array',
            'archived' => 'boolean',
        ];
    }

    protected function budget(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format((float) $value, 2, '.', ''),
            set: fn($value) => round((float) $value, 2)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function () {
            static::forgetAllCache();
        });

        static::created(function () {
            static::forgetAllCache();
        });

        static::updating(function () {
            static::forgetAllCache();
        });

        static::updated(function () {
            static::forgetAllCache();
        });

        static::deleted(function () {
            static::forgetAllCache();
        });
    }

    private static function forgetAllCache()
    {
        $keys = [
            'purchase_orders_count',
            'jobs_completed_count',
            'jobs_in_progress_count',
            'floor_team_jobs',
            'floor_team_members',
            'completed_jobs',
            'purchase_order_jobs_completed_stat',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
