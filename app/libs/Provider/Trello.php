<?php

namespace App\Libs\Provider;

use Illuminate\Support\ServiceProvider;

class Trello extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Trello', function($app) {
	        return new \App\Libs\src\trello\Trello();
	    });
	}

}