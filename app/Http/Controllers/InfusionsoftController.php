<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Infusionsoft;

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
            array('data' => Infusionsoft::contacts( $infusionsoft->id , $request->all() ) )
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

}


