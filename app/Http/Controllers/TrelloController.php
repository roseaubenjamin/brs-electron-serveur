<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application;
use App\Libs\Facades\Trello;

class TrelloController extends Controller
{
    
    /**
     * Récupération des board de trelle se fait ICI
     */
    public function boards( Request $request , $app )
    {
        $trello = \App\Application::find( $app ) ;
        if( !$trello ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Trello::boards( $trello->id , $trello->accessToken ) )
        );
    }

    public function lists( Request $request , $app )
    {
        $trello = \App\Application::find( $app ) ;
        if( !$trello ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Trello::lists( $trello->id , $trello->accessToken ) )
        );
    }

    public function card( Request $request , $app , $card )
    {
        $trello = \App\Application::find( $app ) ;
        if( !$trello ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Trello::card( $trello->id , $trello->accessToken , $card ) )
        );
    }

    public function membres( Request $request , $app )
    {
        $trello = \App\Application::find( $app ) ;
        if( !$trello ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Trello::membres( $trello->id , $trello->accessToken ) )
        );
    }

    public function labels( Request $request , $app )
    {
        $trello = \App\Application::find( $app ) ;
        if( !$trello ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Trello::labels( $trello->id , $trello->accessToken ) )
        );
    }
    
    public function deletecard( Request $request , $app , $card )
    {
        $trello = \App\Application::find( $app ) ;
        if( !$trello ){
            return response()->json(
                array('data' => [] )
            );    
        }
        return response()->json(
            array('data' => Trello::removeCard( $trello->id , $trello->accessToken , $card ) )
        );
    }
    
}
