<?php
namespace Hermes;


use Illuminate\Support\Manager;


class RoutineManager extends Manager
{
	
	/**
	 * Get the default driver name.
	 * @return string
	 */
	public function getDefaultDriver ()
	{
		return 'laravel';
	}
	
	
	/**
	 * @return Drivers\Laravel
	 */
	public function createLaravelDriver ()
	{
		return new Drivers\Laravel();
	}
	
}