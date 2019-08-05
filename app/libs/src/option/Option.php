<?php

namespace App\Libs\src\option;

use Illuminate\Auth\AuthManager;
use App\Team;
use App\Application;

class Option
{

    private $auth ;

    public function __construct( AuthManager $auth )
    {
        $this->auth = $auth ;     
    }

    public function create( $name , $value , $groupe )
    {
        $op = \App\Options::where('name', $name )->get()->first() ; 
        if( $op ){
            $op->update(compact('value','groupe')) ;  
            $op->save() ;
            return $op ;   
        }else{
            return \App\Options::create(compact('name','value','groupe')) ;    
        }

    }

    public function find( $name )
    {
        return \App\Options::where('name', $name )->get()->first() ;    
    }

    
}
