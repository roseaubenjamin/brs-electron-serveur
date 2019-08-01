<?php

namespace App\Libs\Provider;


use Illuminate\Support\ServiceProvider;

class Application extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Application', function($app) {
	        return new \App\Libs\src\application\Application();
	    });
	}

}