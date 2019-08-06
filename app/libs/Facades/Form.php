<?php

namespace App\Libs\Facades;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
	
	protected static function getFacadeAccessor()
    {
        return \App\Libs\src\form\Form::class;
    }

}