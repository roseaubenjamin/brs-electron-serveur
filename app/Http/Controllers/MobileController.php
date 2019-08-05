<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Mobile;
use App\Application;

class MobileController extends Controller
{
    /**
     * Creation d'application  
     */
    public function create( Request $request )
    {
        return response()->json(
            array('data' => Mobile::create( $request->all()) )
        );
    }

    public function index( Request $request )
    {
        return response()->json(
            array('data' => Mobile::index( $request->all()) )
        );
    }

    public function assigned( Request $request , \App\Mobile $app )
    {
        return response()->json(
            array('data' => Mobile::assigned( $app , $request->all() ) )
        );
    }

    public function assigne( Request $request , \App\Mobile $app )
    {
        return response()->json(
            array('data' => Mobile::assigne( $app , $request->all() ) )
        );
    }
    
    public function priorty( Request $request , \App\Mobile $app )
    {
        return response()->json(
            array('data' => Mobile::priorty( $app , $request->all() ) )
        );
    }

    public function priortyFind( Request $request , \App\Mobile $app )
    {
        return response()->json(
            array('data' => Mobile::priortyFind( $app , $request->all() ) )
        );
    }
    
    
}
