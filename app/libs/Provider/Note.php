<?php

namespace App\Libs\Provider;

use Illuminate\Support\ServiceProvider;

class Note extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('note', function($app) {
	        return new \App\Libs\src\note\Note();
	    });
	}

}