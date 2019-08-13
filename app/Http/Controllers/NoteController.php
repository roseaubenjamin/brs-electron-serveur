<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Facades\Note;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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

    /**
     * Convert Note to task
     *  */
    public function createConvert( Request $request )
    {
        //upload de notes 
        return response()->json(
            array('data' => Note::createConvert( $request->all() ) )
        );
    }

    public function update( Request $request , $note )
    {
        $file = $request->file('file') ;
        //upload de notes 
        return response()->json(
            array('data' => Note::update( $request->all() , $note , $file ) )
        );
    }
    
    public function index( Request $request , $app )
    {
        return response()->json(
            array('data' => Note::index( $request->all() , $app ) )
        ); 
    }

    public function find( Request $request )
    {
        return response()->json(
            array('data' => Note::find( $request->all() ) )
        ); 
    }

    public function attache( Request $request , $unique , $nativeId , $attache )
    {
        return response()->json(
            array('data' => Note::attache( $unique , $nativeId , $attache  ) )
        ); 
    }

    public function audio( Request $request , $unique )
    {
        if( ! $path = Note::audio( $unique ) ){
            echo "FICHIER PAS TROUVER";
            return ; 
        } 
        $file = Storage::disk(env('FILE_DRIVER'))->get( $path );
        $realpath = Storage::disk(env('FILE_DRIVER'))->path( $path );
        $filesize = Storage::disk(env('FILE_DRIVER'))->size( $path );

        $size   = $filesize;  
        $length = $size;           
        $start  = 0;               
        $end    = $size - 1;        

        $headersArray=[
            'Accept-Ranges' => "bytes",
            'Accept-Encoding' => "gzip, deflate",
            'Pragma' => 'public',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => ' inline; filename='.basename($path),
            'Content-Length' => $filesize,
            'Content-Type' => "audio/mpeg",
            'Connection' => "Keep-Alive",
            'Content-Range' => 'bytes 0-'.$end .'/'.$size,
            'X-Pad' => 'avoid browser bug',
            'Etag' => basename($path),
        ];

        return response()->file($realpath, $headersArray);

    }

    
}
