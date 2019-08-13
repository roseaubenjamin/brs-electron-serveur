<?php

namespace App\Libs\src\team;

use App\Application;
use Illuminate\Auth\AuthManager;

class Team
{

    private $auth ;

    public function __construct( AuthManager $auth )
    {
        $this->auth = $auth ;     
    }

    public function index( Application $app )
    {
        $app->load('teams') ; 
        $teams = $app->teams()->get() ;
        $users = array() ; 
        foreach( $teams as $team ){
            $team->load('user') ;
            $user = $team->user()->get()->first()->toArray() ;
            $user['role'] = $team->role; 
            $users[] = $user ;  
        }
        return $users ;  
    }

    /**
     * Ajout de l'utilisateur courant au team 
     *  */
    public function create( string $unique )
    {
        $user = $this->auth->user() ; 
        $app = Application::where( compact('unique') )->orWhere('id',$unique)->get()->first() ; 
        if( !$app )
            return false ; 
        if( \App\Team::where(['application_id' => $app->id,'user_id' => $user->id])->get()->first() )
            return $app->id ; 
        $user->load('teams') ; 
        return $user->teams()->create([
            'role' => 'user' , 
            'application_id' => $app->id ,
        ]) ; 
    }

    /**
     * Récupération des informatiosn du teams 
     */
    public function info( $ap )
    {
        $user = $this->auth->user() ; 
        $app = Application::where( 'id' , $ap )->get()->first() ; 
        if( !$app )
            return false ;
        return \App\Team::where(['application_id' => $app->id,'user_id' => $user->id])->first() ; 
    }
}
