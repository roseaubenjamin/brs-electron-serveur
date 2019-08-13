<?php

namespace App\Libs\src\infusionsoft;

use App\Libs\Facades\Option;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App\Libs\Facades\Application;

class Infusionsoft

{

    private $infusionsoft ; 

    function __construct()
    {
        $this->infusionsoft = null ;   
    }

    public function getInfusionsoft( $id , $accessToken = null )
    {
        if ( $this->infusionsoft )
            return $this->infusionsoft ; 
        
        $this->infusionsoft = new \Infusionsoft\Infusionsoft(array(
            'clientId'     => env( 'INFUSIONSOFT_KEY' ),
            'clientSecret' => env( 'INFUSIONSOFT_SECRET' ),
            'redirectUri'  => env( 'APP_URL' ) . '/app/ifs/auth/'.$id,
        ));

        if ( $accessToken ){

            $this->infusionsoft->setToken( $accessToken );
            $now = Carbon::now() ;
            $date = Carbon::parse( $accessToken->endOfLife ) ;

            if( $now > $date ){
                $t = $this->infusionsoft->refreshAccessToken();
                Application::update( $id , array( 'accessToken' => $t )) ; 
            }
            //on update le plugin ici         
        }
        return $this->infusionsoft ; 
    }

    public function makeAuthUrl( int $id )
    {
        $infusionsoft = $this->getInfusionsoft( $id ) ;
        $infusionsoft->getToken() ;
        return $infusionsoft->getAuthorizationUrl() ;
    }

    public function findAccessToken( int $id , string $code )
    {
        $infusionsoft = $this->getInfusionsoft( $id ) ;
        return $infusionsoft->requestAccessToken( $code ) ; 
    }

    /**
     *  Récupération des ID de l'utilisateur qui a crée le token 
     *  Dans le compte infusionsoft a crée 
     *  */
    public function userAuth( int $id , $accessToken )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $users = $this->users( $id , $accessToken ) ; 
        $auth = $infusionsoft->userinfo()->get() ; 

        foreach( $users as $user){
            if( $user['global_user_id'] == $auth['global_user_id'] ){
                return $user ; 
            }
        }

        return null ; 
    }

    /**
     * Récupération de la liste de l'intégralité de l'utilisateur ICI 
     * les membres de l'équipe dans IFS 
     */
    public function users( int $id , $accessToken )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $user = $infusionsoft->restfulRequest('GET',$infusionsoft->getBaseUrl().'/rest/v1/users') ;
        if( isset($user['users']) )
            return $user['users'] ; 
        return [] ;
    }


    public function contacts( int $id , $query = array() )
    {
        $page = 1 ; 
        if( isset($query['page']) ){
            $page = (int) $query['page'] ; 
        }

        $search = "" ; 
        if( isset($query['search']) ){
            $search = $query['search'] ; 
        }
                
        $precached = Cache::get( "contact${id}" , collect( [] )) ; 
        $limit = 10 ; 
        $show = ( $page - 1 ) * $limit ; 

        $filterd = $precached->filter(function ($value, $key) use( $search ) {
            if( $search == "" )
                return true ;
            return ( ( strpos( $value['full_name'] , $search ) !== false ) || ( strpos( $value['emailText'] , $search ) !== false ) ) ? true : false ; 
        });

        $count = $filterd->count(); 

        $responce = $filterd->slice( $show , $limit )->values();

        return array('data' => $responce , 'total' => $count , 'search' => $search , 'maxpage' => ceil ( ( $count / $limit ) )  ) ;
    
    }

    public function fetchContact( int $id , $accessToken )
    {
        ini_set('max_execution_time', 180);
        
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $offset = 0 ; 
        $option = Option::find( "fetchcontact${id}") ; 
        $limit = 1000 ; 
        if( $option ){
            $offset = $option->value['offset'] + $limit ; 
            $count = $option->value['total']  ; 
            if( $offset > $count )
                return array('success'=>true) ; 
        }else{
            $count = $infusionsoft->contacts()->count() ; 
        }
        $precached = Cache::get( "contact${id}" , collect( [] )) ; 

        $contact = $infusionsoft->contacts()->where('limit', $limit )->where('offset', $offset )->get();
  
        $percent = ($offset+$limit)/$count;
        $nemb = number_format( $percent * 100, 1 ) ; 
        $percent_friendly = $nemb>100?100:$nemb;

        $collection = collect($contact)->map(function ($item, $key) {
            $email = collect( $item['email_addresses'] )->map->email->values() ; 
            $emailText = $email->implode(' '); 
            $given_name = $item['given_name'] ; 
            $family_name = $item['family_name'] ; 
            $full_name = $family_name. ' ' .$given_name  ; 
            $id = $item['id'] ; 
            return compact('email','given_name','family_name','id','full_name','emailText');
        })->values() ;

        Cache::forever( "contact${id}" , $collection->concat( $precached ) ) ; 

        return Option::create( "fetchcontact${id}" , array( "total" => $count , "offset" => $offset , "percenty" => $percent_friendly ) , null ) ;

    }

    
    /**
     * Récupération des notes 
     */
    public function notes(int $id , int $note , $accessToken  )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $noteNative = $infusionsoft->notes()->find( $note ) ; 
        return $noteNative ;  
    }

     /**
      * Récupération des taches 
      */
    public function tasks( int $id , int $task , $accessToken )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $taskNative = $infusionsoft->tasks()->find( $task ) ; 
        return $taskNative ;  
    }

    /**
     * Récupération des notes 
     */
    public function note(int $id , int $note , $accessToken  )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $noteNative = $infusionsoft->notes()->find( $note ) ; 
        return $noteNative ;  
    }

     /**
      * Récupération des taches 
      */
    public function task( int $id , int $task , $accessToken , $query )
    {
        ini_set('max_execution_time', 360 );
        
        //récupération des membres de l'équipe
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $taskNative = null ; 
        try {
            $taskNative = $infusionsoft->tasks()->find( $task ) ;//find( $task ) 
        } catch (\Exception $e) {}
        
        if( $taskNative && \count( $taskNative ) )
            return $taskNative ;
        
        $users = $this->users(  $id , $accessToken ) ; 
        $count = 0 ; 
        do{
            //@todo: voire si l'utilisateur a plus de 1000 tache attacher a son compte
            $temps = $infusionsoft->tasks()->where( 'user_id' , $users[$count]["id"] )->get() ; 
            $col = collect( $temps ) ; 
            $col = $col->filter(function ($value, $key) use( $task ) {
                return $value['id'] == $task ? true : false ; 
            })->values()->toArray();
            if( \count( $col ) ){
                $taskNative = $col[0] ; 
                break ; 
            }
            $count++ ; 
            if( $count >= \count( $users ) ){
                break ; 
            }
        }while( 1 ) ; 
        
        return $taskNative ; 
        
            
    }


    public function checkMembre( $apps )
    {
        $ids = [] ; 
        foreach ( $apps as $app ){
            $precached = Cache::get( "contact".$app['id'] , collect( [] )) ; 
            $option = Option::find( "fetchcontact".$app['id'] ) ; 
            if( count( $precached ) == 0 || ($option&&$option->value['percenty'] < 100) )
                $ids[] = $app['id'] ; 
        }
        return $ids ; 
    }
    
    /**
     * Création de NOTE
     */
    public function taskCreate(int $id , $accessToken , $task )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $newtask = $infusionsoft->tasks()->create( $task ) ; 
        return $newtask ;  
    }


     /**
      * Création de tache 
      */

    public function noteCreate(int $id , $accessToken , $note )
    {
        $infusionsoft = $this->getInfusionsoft( $id , $accessToken ) ;
        $newtask = $infusionsoft->notes()->create( $note ) ; 
        return $newtask ; 
    }


}
