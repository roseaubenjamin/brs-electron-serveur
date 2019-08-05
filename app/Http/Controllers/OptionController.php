<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Option;

class OptionController extends Controller
{
    
    public function find( $name )
    {
        dd( $name ) ; 
    }

    public function create( Request $request )
    {
        $name = $request->get('name'); 
        $value = $request->get('value'); 
        $groupe = $request->get('groupe');
        return response()->json(
            array('data' => Option::create( $name , $value , $groupe ) )
        ); 
    }
}
