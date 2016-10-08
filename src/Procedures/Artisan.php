<?php
namespace Hermes\Procedures;


class Artisan extends AbstractProcedure
{
	
	
	public function whileOnMaintenance ( \Closure $callback )
	{
		$this->on ( $this->root )->pushCommand ( 'php artisan down' );
		
		$callback( $this );
		
		$this->on ( $this->root )->pushCommand ( 'php artisan up' );
		
		return $this;
	}
	
	
	public function migrate ()
	{
		return $this->pushCommand ( 'php artisan migrate --force' );
	}
	
	
	public function clearCache ()
	{
		return $this->pushCommand ( [
										'php artisan cache:clear',
										'php artisan view:clear'
									] );
	}
	
	
	public function cacheConfig ()
	{
		return $this->pushCommand ( 'php artisan config:cache' );
	}
	
	
	public function cacheRoutes ()
	{
		return $this->pushCommand ( 'php artisan route:cache' );
	}
	
}