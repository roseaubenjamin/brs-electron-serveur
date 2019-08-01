<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Trello extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\trello\Trello::class;
    }

}