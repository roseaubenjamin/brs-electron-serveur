<?php

namespace App\Http\Middleware;

use Closure;

class session
{
    public function handle($request, Closure $next)
    {
        //lancement du session 
        session_start() ; 
        return $next($request);
    }
}
