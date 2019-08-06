<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Note;

class NoteController extends Controller
{
    
    public function create( Request $request )
    {
        $file = $request->file('file') ;
        //upload de notes 
        return response()->json(
            array('data' => Note::create( $request->all() , $file ) )
        );
    }

    public function index( Request $request )
    {
        dd( $request->all() ) ; 
    }

}
