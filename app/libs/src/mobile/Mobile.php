<?php

namespace App\Libs\src\mobile;

use Illuminate\Auth\AuthManager;
use App\Team;
use App\Application;
use App\Libs\Facades\Infusionsoft;
use App\Libs\Facades\Trello;
use App\Libs\Facades\Note;

class Mobile
{

    private $auth ;

    public function __construct( AuthManager $auth )
    {
        $this->auth = $auth ;     
    }

    public function create( array $all )
    {
        $user = $this->auth->user() ; 
        $user->load('mobiles') ; 
        $mobiles = $user->mobiles()->create( $all ) ;
        //ajout de l'utilisateur actuele dans le team de l'application 
        Team::create([
            'active' => true ,
            'role' => 'admin',
            'user_id' => $user->id,
            'mobile_id' => $mobiles->id,
        ]);
        return $mobiles ; 
    }

    
    public function index()
    {
        $user = $this->auth->user() ; 
        $user->load('teams') ; 
        $teams = $user->teams()->get() ; 
        //@tod : trouver la meilleur facons de faire avec l'object collection, 
        //mais la on est vraiment dans l'urgence 
        $apps = array() ; 
        foreach( $teams as $team ){
            $team->load('mobile') ;
            $app = $team->mobile()->first() ; 
            if( $app ){
                $app = $app->toArray() ; 
                if( $app['infusionsoft'] ){
                    $infusionsoft = Application::find( $app['infusionsoft'] )->toArray() ; 
                    $app['infusionsoft'] = $infusionsoft ; 
                }
                if( $app['trello'] ){
                    $trello = Application::find( $app['trello'] )->toArray() ; 
                    $app['trello'] = $trello ; 
                }
                $app['role'] = $team->role ; 
                $apps[] = $app ;
            }
             
        }
        return $apps ; 
    }

    public function deleteMobile( \App\Mobile $app )
    {
        return $app->delete() ; 
    }

    public function find( $app , $data )
    {
        return $app ; 
    }

    /**
     * Liste tout les options de cette Mobile
     *  */
    public function assigned( \App\Mobile $mobile , $op = null )
    { 
        $type = 'trello' ; 
        if( isset($op['type']) )
            $type = $op['type'] ; 
        
        $user = $this->auth->user() ; 
        $mobile->load('options') ; 
        $options = $mobile->options()->where(['groupe' => $type." assinged"])->get() ; 
        return $options ;
    }

    /**
     * Ajouter des options a cette mobile
     *  */
    public function assigne( \App\Mobile $mobile , $all )
    {
        $user = $this->auth->user() ; 
        $mobile->load('options') ; 
        $options = $mobile->options() ; 
        $idMembre = isset( $all['idMembers'] ) ? $all['idMembers'] : $all['user_id'] ; 
        
        if( $option = $options->where(['name' => "assinged ".$idMembre])->first() ){
            $option->value = $all; 
            $option->save() ;
            return $option ; 
        }else{
            $type = 'trello' ; 
            if( isset($all['type']) )
                $type = $all['type'] ; 
            
            $new = $options->create([
                'name' => "assinged ".$idMembre ,
                'groupe' => $type.' assinged',
                'value' => $all ,
            ]);
            $new->save() ;
        }
        
        return $new ;
    }

    /**
     * 
     * Détachement d'un assignation 
     */
    public function deassigned( \App\Options $option )
    {
        return $option->delete() ; 
    }

    /**
     * Ajoute de l'option de prioté dans l'option de cette applications 
     *  */
    public function priorty( \App\Mobile $mobile , $all )
    {
        $user = $this->auth->user() ; 
        $mobile->load('options') ; 
        $options = $mobile->options() ; 
        if( $option = $options->where(['name' => "priority ".$all['idLabels']])->first() ){
            $option->value = $all; 
            $option->save() ;
            return $option ; 
        }else{
            $new = $options->create([
                'name' => "priority ".$all['idLabels'] ,
                'groupe' => 'priority',
                'value' => $all ,
            ]);
            $new->save() ;
        }
        
        return $new ;
    }
    
    public function priortyFind( \App\Mobile $mobile , $all )
    {
        $user = $this->auth->user() ; 
        $mobile->load('options') ; 
        $options = $mobile->options() ; 
        return $options->where(['groupe' => "priority"])->get(); 
    }
    
    public function deleteOption( \App\Mobile $mobile )
    {
        $user = $this->auth->user() ; 
        $mobile->load('options') ; 
        $options = $mobile->options()->get() ; 
        foreach( $options as $option ){
            $option->delete() ; 
        }
        return true ; 
    }
    
    /**
     * Récupération des mobiles probable pour l'unique en paramètre
     *  */
    public function mobileUnique( $unique )
    {
        //selectionne la note 
        //selectionne native ID 
        $note = \App\Note::where('unique',$unique)->first() ;
        if( !$note )
            return false ; 
        $app = $note->application_id ;

        $user = $this->auth->user() ; 
        $user->load('teams') ; 
        $teams = $user->teams()->where('mobile_id','!=',null)->get() ; 
        $mobiles = [] ; 
        foreach ( $teams as $team ){
            $mobile = $team->load('mobile')->mobile()->first() ;
            if( $mobile->infusionsoft )
                $mobiles[] = $mobile ;  
        }
        //de tout mes team, on selectionne
        return $mobiles ; 

    }

    public function createVocal( \App\Mobile $app , $data )
    {
        $assigned = $data['assigned'] ; 
        if( $assigned ){
            //récupération de l'option assigned 
            $assigned = \App\Options::where( 'id', $assigned )->first() ;
        }
         
        if( 
            $data['compte'] == "infusionsoft" &&
            $assigned && isset( $assigned->value['user_id'] ) &&
            $assigned->value['user_id'] == "note" &&
            $app->infusionsoft 
        ){
            //récupératon de l'application infusionsot 
            $ifs = \App\Application::find( $app->infusionsoft ) ; 
            if( !$ifs )
                return 'mobile vocal error ifs' ;
            //note infusionsoft 
            try {
                $body = env('APP_URL').'/read/'.$data['unique'] ; 
                $title = $data['title'] ; 
                $contact_id = $data['contact_id'] ; 
                $note = Infusionsoft::noteCreate( $app->infusionsoft , $ifs->accessToken , compact('contact_id','title','body')) ; 
            } catch (\Exception $e) {}
            
            if( !$ifs )
                return 'mobile vocal error note create' ;
            
            $native_id = $note->id ; 
            $attache = 'note'; 
            $application_id = $app->infusionsoft ; 

        }
        else if(
            $data['compte'] == "infusionsoft" &&
            $assigned && isset( $assigned->value['user_id'] ) && 
            $app->infusionsoft 
        ){
            //tache infusionsoft 
            $ifs = \App\Application::find( $app->infusionsoft ) ; 
            if( !$ifs )
                return 'mobile vocal error ifs' ;
            try {
                $description = env('APP_URL').'/read/'.$data['unique'] ; 
                $title = $data['title'] ; 
                $priority = $data['prioriter'] ; 
                $user_id = $assigned->value['user_id'] ; 
                $contact = array( 'id' => $data['contact_id'] ) ; 
                $task = Infusionsoft::taskCreate( $app->infusionsoft , $ifs->accessToken , compact('contact_id','title','description')) ; 
            } catch (\Exception $e) {}

            if( !$ifs )
                return 'mobile vocal error task create' ;

            $native_id = $task->id ; 
            $attache = 'task';
            $application_id = $app->infusionsoft ; 
        
        }
        else if( $data['compte'] == "trello" && $app->trello ){
            //card trello 
            $trello = \App\Application::find( $app->trello ) ; 
            if( !$trello )
                return 'mobile vocal error trello' ;

            try {
                $date = null ; 
                $idLabels = null ; 
                $idList = null ; 
                $idMembers = null ; 
                $priority = $data['prioriter'] ; 
                if( $priority ){
                    //récupération de l'option assigned 
                    $priority = \App\Options::where( 'id', $priority )->first() ;
                    $idLabels = $priority->value["idLabels"] ;
                    $date = $priority->value["date"] ;
                }

                if( $assigned ){
                    $idList = $assigned->value["idList"] ;
                    $idMembers = $assigned->value["idMembers"] == "default" ? null : $assigned->value["idMembers"] ;
                }
                
                $desc = $data['description'] ; 
                $name = $data['title'] ; 
                $date = $data["date"] ;

                if( !$idList )
                    return 'mobile vocal error trello list' ;
                 
                $card = Trello::addCard( $app->trello , $trello->accessToken , compact('idMembers','idList','idLabels','date','name','desc')) ; 
            } catch (\Exception $e) {}

            $native_id = $card->id ; 
            $attache = 'card';
            $application_id = $app->trello ;
        }
        
        $mobile_id = $app->id ;
        $unique = $data['unique'] ; 
        $dataNote = compact('unique','native_id','attache','application_id','mobile_id') ; 
        if( $newvocal = Note::create( $dataNote , $data['file'] ) ){
            return $newvocal ; 
        }
        return 'mobile vocal create unknow' ;

    }
}
