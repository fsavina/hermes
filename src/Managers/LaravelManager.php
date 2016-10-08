<?php
namespace Hermes\Managers;


class LaravelManager extends DeployManager
{
	
	
	/**
	 * @param string $command
	 * @return LaravelManager
	 */
	protected function pushArtisanCommand ( $command )
	{
		return $this->pushCommand ( "php {$this->root}/artisan {$command}" );
	}


	/**
	 * @param \Closure $callback
	 * @return LaravelManager
	 */
	public function whileOnMaintenance ( \Closure $callback )
	{
		$this->pushArtisanCommand ( 'down' );

		$callback( $this );

		return $this->pushArtisanCommand ( 'up' );
	}


	/**
	 * @return LaravelManager
	 */
	protected function taskComposerInstall ()
	{
		return $this->pushCommand ( 'composer install --no-interaction' );
	}


	/**
	 * @return LaravelManager
	 */
	protected function taskMigrate ()
	{
		return $this->pushArtisanCommand ( 'migrate --force' );
	}


	/**
	 * @return LaravelManager
	 */
	protected function taskConfigCache ()
	{
		return $this->pushArtisanCommand ( 'config:cache' );
	}


	/**
	 * @return LaravelManager
	 */
	protected function taskRouteCache ()
	{
		return $this->pushArtisanCommand ( 'route:cache' );
	}


	/**
	 * @return LaravelManager
	 */
	protected function taskCacheClear ()
	{
		return $this->pushArtisanCommand ( 'cache:clear' );
	}


	/**
	 * @return LaravelManager
	 */
	protected function taskViewClear ()
	{
		return $this->pushArtisanCommand ( 'view:clear' );
	}
	
}