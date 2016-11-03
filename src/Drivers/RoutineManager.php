<?php
namespace Hermes\Drivers;


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
	 * @return Laravel
	 */
	protected function createLaravelDriver ()
	{
		return new Laravel();
	}
	
}