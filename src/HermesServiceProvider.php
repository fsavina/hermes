<?php

namespace Hermes;


use Illuminate\Support\ServiceProvider;


class HermesServiceProvider extends ServiceProvider
{

	protected $defer = true;

	
	public function boot ()
	{
	}
	
	
	public function register ()
	{
		$this->registerRoutineManager ();
		
		$this->registerRoutineDriver ();
		
		$this->registerCommands ();
	}
	
	
	protected function registerRoutineManager ()
	{
		$this->app->singleton ( 'hermes', function ( $app )
		{
			return new RoutineManager( $app );
		} );
	}
	
	
	protected function registerRoutineDriver ()
	{
		$this->app->singleton ( 'hermes.routine', function ( $app )
		{
			return $app[ 'hermes' ]->driver ();
		} );
	}
	
	
	protected function registerCommands ()
	{
		$this->commands ( [
							  //Commands\Setup::class,
							  Commands\Deploy::class
						  ] );
	}


	public function provides ()
	{
		return [ 'hermes', 'hermes.routine' ];
	}

}
