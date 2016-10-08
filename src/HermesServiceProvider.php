<?php

namespace Hermes;


use Illuminate\Support\ServiceProvider;


class HermesServiceProvider extends ServiceProvider
{
	
	public function boot ()
	{
	}
	
	
	public function register ()
	{
		$this->commands ( [
							  Commands\Setup::class,
							  Commands\Deploy::class
						  ] );
	}
}
