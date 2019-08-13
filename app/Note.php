<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'unique','path','attache','native_id','user_id','application_id','mobile_id'
    ];

    
    public function forms()
    {
        return $this->hasMany(Form::class) ; 
    }

    public function application()
    {
        return $this->belongsTo(Note::class) ; 
    }
    
}
