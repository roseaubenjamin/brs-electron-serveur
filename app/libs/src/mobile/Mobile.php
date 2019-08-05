<?php

namespace App\Libs\src\mobile;

use Illuminate\Auth\AuthManager;
use App\Team;
use App\Application;

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
    
    
    
}
