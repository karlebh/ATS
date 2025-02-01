<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'po_number',
        'budget',
        'progress',
        'status',
        'current_team',
        'timeline',
    ];

    public function client()
    {
        $this->belongsTo(Client::class);
    }

    public function job()
    {
        $this->belongsTo(Job::class);
    }
}
