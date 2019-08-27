<?php

namespace App\Libs\src\note;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\AuthManager;
use App\Application;
use App\Team;

class Note
{

    private $auth ;

    public function __construct( AuthManager $auth )
    {
        $this->auth = $auth ;     
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
        $drive = Storage::disk(env('FILE_DRIVER')) ; 
        if( $drive->exists( '/files/'.$app->id.'/'.$data['unique'].'.wav' ) ){
            $drive->delete( '/files/'.$app->id.'/'.$data['unique'].'.wav' );
        }
        $new = $drive->move('/files/'.$app->id.'/'.basename($filei), '/files/'.$app->id.'/'.$data['unique'].'.wav' );
        $path = $drive->url('/files/'.$app->id.'/'.$data['unique'].'.wav');
        $data['path'] = $path ;
        unset($data['file']) ;
        $note = \App\Note::create( $data ) ;
        return $note ; 

    }

    public function createConvert( $data )
    {
        $note = \App\Note::where('id',$data['note'])->first() ; 
        $note->load('application') ;
        $application = $note->application()->first()  ;  
        //copie du nouveaux fichier 
        $drive = Storage::disk(env('FILE_DRIVER')) ; 
        if( $drive->exists( '/files/'.$application->id.'/'.$note->unique.'.wav' ) ){
            $drive->copy( '/files/'.$application->id.'/'.$note->unique.'.wav' , '/files/'.$application->id.'/'.$data['unique'].'.wav' );
            $path = $drive->url('/files/'.$application->id.'/'.$data['unique'].'.wav');
            $data['path'] = $path ; 
        }
        return \App\Note::create( $data ) ; 
    }

    public function update( $data , $n , $file )
    {
        $note =\App\Note::find( $n ) ;
        if( $file && $note ) {
            $bipath = 'files/'.$note->application_id.'/'.basename($note->path) ; 
            //delete file if existe 
            $drive = Storage::disk(env('FILE_DRIVER')) ; 
            $drive->delete('/files/'.$note->application_id.'/'.$note->unique.'.wav');
            //reupload new files 
            $filei = $file->storePublicly( 'files/'.$note->application_id ,['disk'=>env('FILE_DRIVER')]);
            $drive->move('/files/'.$note->application_id.'/'.basename($filei), '/files/'.$note->application_id.'/'.$note->unique.'.wav' );
            $note->save() ; 
        } 
        return $note ; 

    }
    

    public function index( $data , $app )
    {
        //selection de l'application 
        $app = Application::find( $app );
        if( !$app )
            return false ; 
        $app->load('notes') ;
        $notes = $app->notes()->get() ; 
        return $notes ; 
    }

    public function attache( $unique , $native_id , $attache )
    {
        $note = \App\Note::where('unique',$unique)->where('attache','!=',($attache=='task'?'note':'task'))->get()->first() ; 
        if( $note ){
            $note->native_id = $native_id ;
            $note->save() ; 
        }
        return $note; 
    }
    
    public function audio( $unique )
    {
        $note = \App\Note::where('unique',$unique)->with('application')->first() ; 
        $user = $this->auth->user() ; 
        //@TODO : vérification que l'utilisateur a le droit d'écouter la note 
        //pour ajouter plus de décurité 
        //&& Team::where(array( 'user_id' => $user->id , 'application_id' => $note->application_id ))->first()
        if( $note  ){
            //récupération application 
            return '/files/'.$note->application_id.'/'.basename($note->unique).'.wav' ; 
        }
        return false ;

    }

    public function find( $all )
    {
        $note = \App\Note::where( $all )->first() ; 
        return $note ; 
    }
    
}

