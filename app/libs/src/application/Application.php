<?php

namespace App\Libs\src\application;

use Illuminate\Auth\AuthManager;
use App\Team;
use App\Libs\Facades\Infusionsoft;
use App\Libs\Facades\Trello;

class Application
{

    private $auth ;

    public function __construct( AuthManager $auth )
    {
        $this->auth = $auth ;     
    }

    public function show( $id )
    {
        //@TODO: Ajouter ICI la possibilité de liste tout les notes de ce compte en statistique 
        $app = \App\Application::find( $id ) ;
        return $app ; 
    }

    public function showByUnique( $unique )
    {
        //@TODO: Ajouter ICI la possibilité de liste tout les notes de ce compte en statistique 
        $app = \App\Application::where( array( 'unique' => $unique ) )->get()->first() ;
        return $app ; 
    }

    public function index()
    {
        $user = $this->auth->user() ; 
        $user->load('teams') ; 
        $teams = $user->teams()->get() ; 
        //@tod : trouver la meilleur facons de faire avec l'object collection, 
        //mais la on est vraiment dans l'urgence 
        $apps = array() ; 
        foreach( $teams as $team ){
            $team->load('application') ;
            $ex = $team->application()->first() ; 
            if( $ex ){
                $app = $ex->toArray() ; 
                $app['role'] = $team->role ; 
                $apps[] = $app ; 
            }
        }
        return $apps ; 
    }

    public function update( $app , array $data )
    {
        $app->update( $data ) ;
        $app->save() ;     
        return $app ; 
    }


    /**
     * Création d'application ( Trello on Infusionsft )
     *  */
    public function create( array $data )
    {
        $user = $this->auth->user() ; 
        //création de l'applications
        $data['unique'] = uniqid() ; 
        $app = $user
            ->applications()
            ->create( $data ) ;
        //création du team de l'applications 
        $team = Team::create([
            'active' => true ,
            'role' => 'admin',
            'user_id' => $user->id,
            'application_id' => $app->id,
        ]);
        //@todo : Trouver une meilleur facon de l'enrgistre 
        //attacher le team a l'utilisateur et a l'applications
        if($data['type']=='infusionsoft')
            $url = Infusionsoft::makeAuthUrl( $app->id ) ; 
        else
            $url = Trello::makeAuthUrl( $app->id )  ; 
        //création URL en fonction si c'est un IFS ou un Trello
        return $url ; 
    }

    /**
     * Récupératop, de Token trello apres redirection 
     *  */
    public function trelloauth( array $info )
    {
        $accessToken = Trello::findAccessToken( $info['id'] , $info['oauth_token'] , $info['oauth_verifier'] )  ; 
        $app = \App\Application::find( $info['id'] ) ;
        if( $app ){
            $app->accessToken = $accessToken ; 
            $app->save() ; 
            //récupération des information de l'utilisateur qui a le session 
            //pour enregistre l'ID de cette utilisateur au session ID 
            $userNative = Trello::userAuth( $info['id'] , $accessToken ) ; 
            $user = $this->auth->user() ; 
            $user->load('teams') ; 
            $team = $user->teams()->where('application_id',$info['id'])->get()->first() ;
            $team->native_id = $userNative->id ; 
            $team->save() ; 
            echo "<script>window.close();</script>";
            return ;
        }   
        return "ERREUR" ; 

    }

    /**
     * Récupération de token Infusonsoft, apres redirection
     *  */
    public function ifsauth( array $info )
    {
        $code = Infusionsoft::findAccessToken( $info['id'] , $info['code'] )  ; 
        $app = \App\Application::find( $info['id'] ) ;
        if( $app ){
            $app->accessToken = $code ; 
            $app->url = $info['url'] ; 
            $app->save() ; 
            //récupération des information de l'utilisateur qui a le session 
            //pour enregistre l'ID de cette utilisateur au session ID 
            $userNative = Infusionsoft::userAuth( $info['id'] ,  $code ) ; 
            $user = $this->auth->user() ; 
            $user->load('teams') ; 
            $team = $user->teams()->where('application_id',$info['id'])->get()->first() ;
            $team->native_id = $userNative['id'] ; 
            $team->save() ; 
            echo "<script>window.close();</script>";
            return ;
        }
        return "ERREUR" ; 

    }


    /**
     *  formation des url infusionsoft et vérifier si cette élement existe dans la base de donner 
     * */
    public function checkIfs( $id , array $info = array())
    {
        $user = $this->auth->user() ; 
        $app = \App\Application::where( [ 'url' => $id.'.infusionsoft.com' ] )->first() ;
        $app->load('teams') ; 
        $team = $app->teams()->where( 'user_id' , $user->id )->first() ; 
        if( $team )
            return $app ;
        return false ;
    }

    public function checkTrello( int $id , array $info = array())
    {
        

    }

    
    
}
