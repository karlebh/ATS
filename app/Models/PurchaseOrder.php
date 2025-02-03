<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'po_number',
        'job_number',
        'client_name',
        'client_email',
        'client_company_name',
        'budget',
        'progress',
        'status',
        'current_team',
        'timeline',
    ];

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function job()
    {
        $this->belongsTo(Job::class);
    }
}
