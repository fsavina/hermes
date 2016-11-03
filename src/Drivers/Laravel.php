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
		return $this->on ( $this->root )
					->command ( 'composer install --no-interaction' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function runGulpTask ()
	{
		return $this->on ( $this->root )
					->command ( 'gulp' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function runGulpProductionTask ()
	{
		return $this->on ( $this->root )
					->command ( 'gulp --production' );
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
	protected function runConfigClearTask ()
	{
		return $this->artisanCommand ( 'config:clear' );
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
	protected function runRouteClearTask ()
	{
		return $this->artisanCommand ( 'route:clear' );
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
	protected function runOptimizeTask ()
	{
		return $this->artisanCommand ( 'optimize' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function runClearCompiledTask ()
	{
		return $this->artisanCommand ( 'clear-compiled' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function runStorageLinkTask ()
	{
		return $this->artisanCommand ( 'storage:link' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function runDownTask ()
	{
		return $this->artisanCommand ( 'down' );
	}
	
	
	/**
	 * @return Laravel
	 */
	protected function runUpTask ()
	{
		return $this->artisanCommand ( 'up' );
	}
	
}