<?php

namespace App\Libs\Provider;

use Illuminate\Support\ServiceProvider;

class Infusionsoft extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Infusionsoft', function($app) {
	        return new \App\Libs\src\infusionsoft\Infusionsoft();
	    });
	}

}