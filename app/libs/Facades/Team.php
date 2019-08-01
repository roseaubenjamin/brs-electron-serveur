<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Team extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\team\Team::class;
    }

}