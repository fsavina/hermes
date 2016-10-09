<?php

namespace Hermes;


use Collective\Remote\RemoteServiceProvider;
use Illuminate\Support\ServiceProvider;


class HermesServiceProvider extends ServiceProvider
{
	
	
	public function boot ()
	{
	}
	
	
	public function register ()
	{
		$this->registerRemoteProvider ();
		
		$this->registerRoutineManager ();
		
		$this->registerRoutineDriver ();
		
		$this->registerCommands ();
	}
	
	
	protected function registerRemoteProvider ()
	{
		$this->app->register ( RemoteServiceProvider::class );
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
							  Commands\Deploy::class,
							  Commands\Setup::class,
							  Commands\Task::class
						  ] );
	}
	
	
	public function provides ()
	{
		return [ 'hermes', 'hermes.routine' ];
	}
	
}
