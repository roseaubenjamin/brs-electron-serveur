<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Infusionsoft;
use App\Libs\Facades\Application;

class InfusionsoftController extends Controller
{

    /**
     * Récupération de la liste de membre de infusionsoft  
     *  */
    public function membres( Request $request , $id )
    {
        $infusionsoft = \App\Application::find( $id ) ;
        if( !$infusionsoft ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Infusionsoft::users( $infusionsoft->id , $infusionsoft->accessToken ) )
        );
    }

    public function contacts( Request $request , $id )
    {
        $infusionsoft = \App\Application::find( $id ) ;
        if( !$infusionsoft ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            Infusionsoft::contacts( $infusionsoft->id , $request->all() ) 
        );
    }


    /**
     * Récupération de l'intégralite des contacts d'infusionsoft 
     *  */
    public function fetchContact( Request $request , $id )
    {
        $infusionsoft = \App\Application::find( $id ) ;
        if( !$infusionsoft ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Infusionsoft::fetchContact( $infusionsoft->id , $infusionsoft->accessToken ) )
        );
    }


    /**
     * Récupération de note de l'application 
     *  */
    public function note( Request $request , $id , $note )
    {
        $infusionsoft = \App\Application::find( $id ) ;
        if( !$infusionsoft ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Infusionsoft::note( $infusionsoft->id , $note , $infusionsoft->accessToken ) )
        );
    }


    /**
     * Récupération de tache de l'applications 
     *  */
    public function task( Request $request , $id , $task )
    {
        $infusionsoft = \App\Application::find( $id ) ;
        if( !$infusionsoft ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Infusionsoft::task( $infusionsoft->id , $task , $infusionsoft->accessToken ,  $request->all() ) )
        );
    }


    /**
     * Récupération des application qui n'on pas encore de membre crée  
     */
    public function checkMembre( Request $request )
    {
        $apps = collect( Application::index() )  
            ->filter(function ($value, $key) {
                return $value["type"] == "infusionsoft" ;
            })
            ->values()
            ->toArray() ;
        return response()->json(
            array('data' => Infusionsoft::checkMembre( $apps ) )
        );
    }


}


