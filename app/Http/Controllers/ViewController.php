<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

use App\Libs\Facades\Trello;

class ViewController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function home()
    {
		$token = session('token') ;
		$tokenclear = session('tokenclear') ;
		return View('home', $token?['tokenauth' => array( 'setApiKey' => $token )]:[]);
    }

	
	public function mobile()
    {
		return View('mobile');
	}
	
	public function read( Request $request , $unique )
    {
		$state = $request->get('state') ; 
		$redirect = $request->get('redirect') ; 
		if( $state )
			return redirect( '/read/'.$unique.'?redirect='.$redirect )->with(array('state'=>'new','redirect'=>$redirect?$redirect:''));
		return View('read');

	}
}
