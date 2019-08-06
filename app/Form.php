<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //
    protected $fillable = [
        'type','name','value' 
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

}
