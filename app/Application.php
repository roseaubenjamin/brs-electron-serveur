<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use function Opis\Closure\unserialize;

class Application extends Model
{
    protected $fillable = [
        'name','url','unique','accessToken','type'
    ];

    protected $hidden = [
        'accessToken',
    ];

    /**
     * Sérialisation automatique des donners de l'access Token si c'est en infusionsoft 
     *  */
    public function setAccessTokenAttribute($value)
    {
        if($this->attributes['type']=='infusionsoft'){
            $this->attributes['accessToken'] = serialize($value);;
        }else{
            $this->attributes['accessToken'] = $value;
        }
    }

    public function getAccessTokenAttribute($value)
    {
        if($this->attributes['type']=='infusionsoft'){
            return unserialize($value);
        }else{
            return $value ; 
        }
    }

    public function teams()
    {
        return $this->hasMany(Team::class) ; 
    }

}
