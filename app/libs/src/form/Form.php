<?php

namespace App\Libs\src\form;

use Illuminate\Session\Middleware\StartSession;
use App\Application;
use App\Note;

class Form
{

    function __construct()
    {
    }

    /**
     * Ajoute de chacun de ces form dans la base de donner de notre application
     * Cest valeur seron utilisé pour la valeur des formulaires de la page home 
     *  */
    public function create( $data , $id )
    {
        $app = Note::find( $id );
        $app->load('forms') ; 
        $qforms = $app->forms() ; 
        $forms = json_decode( $data['forms'] , true ); 
        foreach( $forms as $form ){
            $data = $qforms->where(['name'=>$form['name']])->get()->first();
            if( $data ){
                //ici on fait l'update de cette element 
                $data->update( $form ) ;
                $data->save() ;  
            }else{
                //création de l'élement form
                $qforms->create( $form ) ; 
            }
        }
        return $forms ; 
    }

    /**
     * Liste de tout les formulaires de cette application 
     */
    public function index( $id )
    {
        $app = Note::find( $id );
        $app->load('forms') ; 
        $qforms = $app->forms() ; 
        return $qforms->get() ; ; 
    }

}

