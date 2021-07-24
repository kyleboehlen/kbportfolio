<?php

namespace App\Models\Photography;

use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    protected $fillable = [
        'caption', 'shoot_id',
    ];
}
