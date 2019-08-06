<?php

namespace App\Libs\src\note;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use App\Application;

class Note
{

    function __construct()
    {
    }

    /** 
     * Création de notes 
     * */
    public function create( $data , $file )
    {
        
        //selection de l'application 
        $app = Application::find($data['application_id']);
        if( !$app )
            return false ; 
        $filei = $file->storePublicly( 'files/'.$app->id ,['disk'=>env('FILE_DRIVER')]);
        $path = Storage::disk(env('FILE_DRIVER'))->url('/files/'.$app->id.'/'.basename($filei));
        $data['path'] = $path ;
        return \App\Note::create( $data ) ; 

    }

}

