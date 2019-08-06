<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Note extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\note\Note::class;
    }

}