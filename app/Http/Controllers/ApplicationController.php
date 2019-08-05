<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\libs\Facades\Application;



class ApplicationController extends Controller
{
    /**
     * Liste des applications d'un utilisateur en session 
     * NOTE : Liste des app crée ou don lutilisateur en est membres 
     */
    public function index()
    {
        return response()->json(
            array('data' => Application::index() )
        );
    }

    /**
     * Affichange d'un application en particulier  
     */
    public function show($id)
    {
        return response()->json(
            array('data' => Application::show( $id ) )
        );
    }

    public function showByUnique($unique)
    {
        return response()->json(
            array('data' => Application::showByUnique( $unique ) )
        );
    }
    
    /**
     * Creation d'application  
     */
    public function create( Request $request )
    {
        return response()->json(
            array('data' => Application::create( $request->all()) )
        );
    }

    public function update( Request $request , \App\Application $id )
    {
        return response()->json(
            array('data' => Application::update( $id , $request->all() ) )
        );
    }
    

    /**
     * Redirection d'authentification trello  
     */
    public function trelloauth( Request $request , $id )
    {
        $oauth_token = $request->get('oauth_token') ; 
        $oauth_verifier = $request->get('oauth_verifier') ; 
        return Application::trelloauth( compact( 'oauth_token' , 'id' , 'oauth_verifier' ) ) ; 
    }

    /**
     * Redirection d'infusionsoft trello  
     */
    public function ifsauth( Request $request , $id  )
    {
        $code = $request->get('code') ; 
        $scope = explode( '|' , $request->get('scope') ); 
        $url = null ; 
        if( isset($scope[1]) ){
            $url = $scope[1] ; 
        }
        $state = $request->get('state') ; 
        return Application::ifsauth( compact('code' , 'scope' , 'state' , 'url' , 'id' ) ) ;
    }


}
