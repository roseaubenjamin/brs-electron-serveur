<?php

namespace App\Libs\src\infusionsoft;

use App\Libs\Facades\Option;
use Illuminate\Support\Facades\Cache;

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

        if ( $accessToken )
            $this->infusionsoft->setToken( $accessToken );
        
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

        return array('data' => $responce , 'total' => $count , 'maxpage' => ceil ( ( $count / $limit ) )  ) ;
    
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
        $nemb = number_format( $percent * 100, 2 ) ; 
        $percent_friendly = $nemb>100?'100%':$nemb.'%';

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

    
    
}
