<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Team;
use App\Application;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Liste des team d'un application
     */
    public function index( Request $request , Application $app )
    {
        return response()->json(
            array('data' => Team::index( $app ) )
        );
    }

    /**
     * Ajout de l'utilisateur dans la session actuele au team de l'application dans le paramètre 
      */
    public function create( Request $request , $unique )
    {
        if( \App\Application::where( 'id' , $unique )->first() )
            return View('home');
        if( Auth::check() ){
            if( $add = Team::create( $unique ) ){ 
                if( \is_numeric( $add ) )
                    return redirect( '/team/'.$add );
                else
                    return View('home')->with( 'newteam' , true );
            } 
        }else{
            return redirect('/login') ; 
        }
        //operateur
        return View('404');
    }

    /**
     * 
     * Récupération des informations de team de cette utilisateur 
     */
    public function info( Request $request , $app )
    {
        return response()->json(
            array('data' => Team::info( $app ) )
        );
    }
    

}
