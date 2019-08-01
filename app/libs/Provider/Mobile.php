<?php

namespace App\Libs\Provider;

use Illuminate\Support\ServiceProvider;

class Mobile extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Mobile', function($app) {
	        return new \App\Libs\src\mobile\Mobile();
	    });
	}

}