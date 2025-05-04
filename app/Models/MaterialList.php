<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialList extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialListFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'router_id',
        'description',
        'quantity',
        'price',
        'vendor_email',
        'invoice_id',
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = str()->uuid();
        });
    }
}
