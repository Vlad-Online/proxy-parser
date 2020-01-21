<?php

namespace App\Parser;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $fillable = [
        'ip',
        'port',
        'type',
        'level'
    ];
}
