<?php
namespace Hermes\Drivers;


class Laravel extends AbstractDriver
{
	
	
	/**
	 * @param string $command
	 * @return Laravel
	 */
	protected function artisanCommand ( $command )
	{
		return $this->command ( "php {$this->root}/artisan {$command}" );
	}


	/**
	 * @return Laravel
	 */
	protected function runComposerInstallTask ()
	{
		return $this->command ( 'composer install --no-interaction' );
	}


	/**
	 * @return Laravel
	 */
	protected function runMigrateTask ()
	{
		return $this->artisanCommand ( 'migrate --force' );
	}


	/**
	 * @return Laravel
	 */
	protected function runConfigCacheTask ()
	{
		return $this->artisanCommand ( 'config:cache' );
	}


	/**
	 * @return Laravel
	 */
	protected function runRouteCacheTask ()
	{
		return $this->artisanCommand ( 'route:cache' );
	}


	/**
	 * @return Laravel
	 */
	protected function runCacheClearTask ()
	{
		return $this->artisanCommand ( 'cache:clear' );
	}


	/**
	 * @return Laravel
	 */
	protected function runViewClearTask ()
	{
		return $this->artisanCommand ( 'view:clear' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function down ()
	{
		return $this->artisanCommand ( 'down' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function up ()
	{
		return $this->artisanCommand ( 'up' );
	}
}