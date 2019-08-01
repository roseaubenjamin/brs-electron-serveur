<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Infusionsoft extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\infusionsoft\Infusionsoft::class;
    }

}