<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Mobile extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\mobile\Mobile::class;
    }

}