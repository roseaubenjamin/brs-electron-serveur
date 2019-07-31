<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $fillable = [
        'name', 'value', 'autoload',
    ];
}
