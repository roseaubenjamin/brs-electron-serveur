<?php

namespace App\Libs\src\trello;

use Illuminate\Session\Middleware\StartSession;
use App\Libs\Facades\Option;

class Trello
{

    private $trello ; 

    function __construct()
    {
        $this->trello = null ;   
    }

    //https://github.com/cdaguerre/php-trello-api
    public function getTrello( int $id , $accessToken = null )
    {
        if ( $this->trello )
            return $this->trello ; 

        $this->trello = new \Stevenmaguire\Services\Trello\Client(array(
            'callbackUrl' => env( 'APP_URL' ) . '/app/trello/auth/'.$id ,
            'domain' => 'https://trello.com',
            'expiration' => 'never',
            'key' => env( 'TRELLO_KEY' ),
            'name' => 'Vocal Note APP',
            'secret' => env('TRELLO_SECRET'),
            'scope' => 'read,write',
            'version' => '1',
        ));

        if ( $accessToken )
            $this->trello->addConfig( 'token' , $accessToken );
        
        return $this->trello ; 
    }
    
    public function delTrello(){
        $this->trello = null ; 
    }

    /**
     * Création de l'URL d'authentification a l'application 
     */
    public function makeAuthUrl( int $id )
    {
        $trello = $this->getTrello( $id ) ; 
        return $trello->getAuthorizationUrl();
    }

    /**
     * Récupération de l'access TOKEN   
     */
    public function findAccessToken( int $id , string $oauth_token , string $oauth_verifier )
    {
        $trello = $this->getTrello( $id ) ; 
        $credentials = $trello->getAccessToken($oauth_token, $oauth_verifier );
        $actk = $credentials->getIdentifier();
        $this->delTrello() ; 
        return $actk ;
    }

    /**
     * Récupération de l'utilisateur courant 
     *  */
    public function userAuth( int $id , string $accessToken )
    {
        $trello = $this->getTrello( $id ,  $accessToken ) ; 
        $user = $trello->getCurrentUser();
        return $user ; 
    }

    /**
     * Récupération de board de l'utilisateur courant  
     *  */
    public function boards( int $id , string $accessToken )
    {
        $trello = $this->getTrello( $id , $accessToken ) ; 
        $board = $trello->getCurrentUserBoards();
        $collection = collect($board);
        $collection = $collection->map(function ($item, $key) {
            $id = $item->id ; 
            $url = $item->url ; 
            $shortUrl = $item->shortUrl ; 
            $name = $item->name ;
            return compact('shortUrl' , 'url' , 'id' , 'name');
        });
        return $collection ; 
    }


    public function lists( int $id , string $accessToken )
    {
        $board = Option::find("application_${id}_native") ; 
        if( !$board->value )
            return [] ; 
        
        $trello = $this->getTrello( $id , $accessToken ) ; 
        $lists = $trello->getBoardLists( $board->value );
        $collection = collect($lists);
        $collection = $collection
            ->filter(function ($item, $key) {
                return $item->closed == false ? true : false ;  
            })
            ->map(function ($item, $key) {
                $id = $item->id ; 
                $name = $item->name ;
                return compact( 'id' , 'name');
            });
        return $collection ; 
    }


    public function membres( int $id , string $accessToken )
    {
        $board = Option::find("application_${id}_native") ; 
        if( !$board->value )
            return [] ;             
        $trello = $this->getTrello( $id , $accessToken ) ; 
        $lists = $trello->getBoardMembers( $board->value );
        $collection = collect($lists);
        $collection = $collection
            ->map(function ($item, $key) {
                $id = $item->id ; 
                $name = $item->fullName ;
                return compact( 'id' , 'name');
            });
        return $collection ; 
    }

    public function labels( int $id , string $accessToken )
    {
        $board = Option::find("application_${id}_native") ; 
        if( !$board->value )
            return [] ;             
        $trello = $this->getTrello( $id , $accessToken ) ; 
        $labels = $trello->getBoardLabels( $board->value );
        $collection = collect($labels);
        $collection = $collection
            ->map(function ($item, $key) {
                $id = $item->id ; 
                $name = $item->name ;
                $color = $item->color ;
                return compact( 'id' , 'name' ,'color' );
            });
        return $collection ; 
    }

    public function cards( int $id , string $accessToken )
    {
        $board = Option::find("application_${id}_native") ; 
        if( !$board->value )
            return [] ;             
        $trello = $this->getTrello( $id , $accessToken ) ; 
        $cars = $trello->getBoardCards( $board->value ); 
        $collection = collect($cars);
        $collection = $collection
            ->map(function ($item, $key) {
                $id = $item->id ; 
                $idList = $item->idList ;
                $shortUrl = $item->shortUrl ;
                $url = $item->url ;
                return compact( 'id' , 'idList' ,'shortUrl'  ,'url' );
            });
        return $collection ; 
    }

    public function card( int $id , string $accessToken , $card )
    {
        $board = Option::find("application_${id}_native") ; 
        if( !$board->value )
            return [] ;             
        $trello = $this->getTrello( $id , $accessToken ) ; 
        return $trello->getCard( $card ); 
    }
    
    public function removeCard( $id , $accessToken , $card )
    {
        $trello = $this->getTrello( $id ,  $accessToken ) ; 
        return $trello->deleteCard( $card ) ; 
    }
    

}

