<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patologica extends Model
{
    protected $table = 'patologicas';
    protected $fillable = [
        'idp', 'idae', 'esp'
    ];

    //
}
