<?php

namespace App\Libs\Provider;


use Illuminate\Support\ServiceProvider;

class Team extends ServiceProvider
{
	public function register()
	{
	    $this->app->singleton('Team', function($app) {
	        return new \App\Libs\src\team\Team();
	    });
	}

}