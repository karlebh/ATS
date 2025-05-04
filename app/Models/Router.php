<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Router extends Model
{
    /** @use HasFactory<\Database\Factories\RouterFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'instruction',
    ];

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function materialLists(): HasMany
    {
        return $this->hasMany(MaterialList::class);
    }
}
