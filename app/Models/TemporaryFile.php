<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{

    protected $fillable = [
        'filename',
        'folder',
        'user_id',
        'upload_id',
    ];
}
