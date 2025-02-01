<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
