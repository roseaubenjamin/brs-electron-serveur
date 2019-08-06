<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Form;

class FormController extends Controller
{
    public function create( Request $request , $note )
    {
        return response()->json(
            array('data' => Form::create( $request->all() , $note ) )
        );
    }

    public function index( Request $request )
    {
        dd( $request->all() ) ; 
    }
}
