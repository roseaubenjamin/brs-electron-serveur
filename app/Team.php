<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    
    protected $fillable = [
        'role','active','application_id','user_id','mobile_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class) ; 
    }

    
    public function application()
    {
        return $this->belongsTo(Application::class) ; 
    }

    public function mobile()
    {
        return $this->belongsTo(Mobile::class) ; 
    }

}
