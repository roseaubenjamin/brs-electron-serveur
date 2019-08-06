<?php

namespace App\Libs\Provider;

use Illuminate\Support\ServiceProvider;

class Form extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Form', function($app) {
	        return new \App\Libs\src\form\Form();
	    });
	}

}