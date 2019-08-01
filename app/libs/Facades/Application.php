<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Application extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\application\Application::class;
    }

}