<?php

namespace App\Libs\Provider;


use Illuminate\Support\ServiceProvider;

class Option extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Option', function($app) {
	        return new \App\Libs\src\option\Option();
	    });
	}

}