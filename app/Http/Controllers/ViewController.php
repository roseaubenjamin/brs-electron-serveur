<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ; 

class ViewController extends Controller
{
    public function home()
    {
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
