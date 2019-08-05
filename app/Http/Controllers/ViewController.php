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
		return View('home');
    }

	
	public function mobile()
    {
		return View('mobile');
    }    
}
