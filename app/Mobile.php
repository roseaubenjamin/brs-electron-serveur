<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    protected $fillable = [
        'name','active','trello','infusionsoft','user_id'
    ];

    public function options()
    {
        return $this->morphMany(Options::class,'options');
    }

}
