<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Part extends Model
{
    /** @use HasFactory<\Database\Factories\PartFactory> */
    use HasFactory;

    protected $cast = [];

    protected $fillable = [
        'uuid',
        'purchase_order_id',
        'number',
        'name',
        'quantity',
        'price',
        'finish',
        'rev',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = str()->uuid();
        });
    }
}
