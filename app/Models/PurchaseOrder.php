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
        'start_date',
        'end_date',
    ];

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->po_number = time() . mt_rand(1000, 9999);
            $model->job_number = time() . mt_rand(100, 999);
        });
    }
}
