<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $fillable = [
        'name', 'groupe', 'value',
    ];

    public function options()
    {
    	return $this->morphTo();
    }

    //création de setter et de getter pour chaque élément pour facilité la récupétation 
    public function setValueAttribute($value)
    {
        if( is_array($value) ){
            $this->attributes['value'] = \serialize( $value );        
        }else{
            $this->attributes['value'] = $value;        
        }
    }

    private static function is_serial($string) {
        return (@unserialize($string) !== false);
    }

    public function getValueAttribute($value)
    {
        if( $this->is_serial( $value ) ){
            return unserialize($value) ; 
        }
        return $value ; 
    }
}
