<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Option extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\option\Option::class;
    }

}