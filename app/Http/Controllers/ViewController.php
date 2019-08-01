<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;
use App\Libs\Facades\Application;
use App\Libs\Facades\Infusionsoft;
use App\Libs\Facades\Trello;
use App\Libs\Facades\Team;
use App\Libs\Facades\Mobile;

class ViewController extends Controller
{
    public function home()
    {

		dd( Application::Test() , Infusionsoft::Test() , Trello::Test() , Team::Test() , Mobile::Test() ) ; 
    	//si l'utilisateur n'est pas connecter, on le redireige vers la page 
    	//login page de l'application
    	if ( Auth::check() )
    		return View('home');
 		else 
    		return redirect('login');
    }

    public function publicPage()
    {
    	return View('public');
    }    
}
