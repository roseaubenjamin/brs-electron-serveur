<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    
    public function handle($request, Closure $next)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Authorization,X-PINGOTHER,X-Requested-With,content-type");
        header("Access-Control-Allow-Credentials: true");
        return $next($request);
    }
}
