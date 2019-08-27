<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    } 

    public function showLoginForm()
    {
        //on veux effacher le token de l'utilisateur dans l'extension chrome 
        $tokenclear = session('tokenclear') ;
        return view('auth.login' , $tokenclear?['tokenclear' => array( 'deleteApiKey' => true ) ]:[] );
    }
    
    /**
     * Création du token d'authentification
     *  */
    protected function authenticated( $request, $user)
    {
        $token = $user->createToken('My Token')->accessToken;
        $tok = [
            'access_token' => $token ,
        ];
        return redirect()->route('home')
            ->with( 'token' , $tok );
    }

    /**
     * Redirection 
     */
    public function redirectPath()
    {
        return  '/';
    }

    /**
     * Connexion form URL
     */
    protected function authenticatedByUrl( Request $request , $email , $password )
    {
 
        if( Auth::check() )
            return redirect('/') ; 

        if ( Auth::attempt( compact('email','password') ) ) {
            $user = Auth::user() ; 
            // Authentication passed...
            $token = $user->createToken('My Token')->accessToken;
            $tok = [
                'access_token' => $token ,
            ];
            return redirect()->route('home')
                ->with( 'token' , $tok );
        }
        return redirect('/') ;
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login')
                ->with( 'tokenclear' , true );
    }

}
