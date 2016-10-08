<?php
namespace Hermes\Drivers;


use Hermes\Contracts\RoutineDriver;
use Illuminate\Support\Str;


abstract class AbstractDriver implements RoutineDriver
{
	
	/**
	 * @var string
	 */
	protected $root;
	
	/**
	 * @var array
	 */
	protected $commands = [ ];
	
	
	/**
	 * @param string $root
	 * @return AbstractDriver
	 */
	public function setRoot ( $root )
	{
		$this->root = $root;
		return $this;
	}
	
	
	/**
	 * @param string|array $command
	 * @return AbstractDriver
	 */
	public function command ( $command )
	{
		if ( is_array ( $command ) )
		{
			$this->commands = array_merge ( $this->commands, $command );
		} else
		{
			array_push ( $this->commands, $command );
		}
		return $this;
	}
	
	
	/**
	 * @param string $task
	 * @return string
	 */
	protected function resolveTask ( $task )
	{
		$method = 'task' . Str::studly ( str_replace ( [ ':', '-' ], '_', $task ) );
		
		return method_exists ( $this, $method ) ? $method : null;
	}
	
	
	/**
	 * @param string|array $task
	 * @return AbstractDriver
	 */
	public function task ( $task )
	{
		$tasks = (array) $task;
		foreach ( $tasks as $task )
		{
			if ( $method = $this->resolveTask ( $task ) )
			{
				$this->$method();
			}
		}
		return $this;
	}
	
	
	/**
	 * @return array
	 */
	public function all ()
	{
		return $this->commands;
	}
	
	
	/**
	 * @return AbstractDriver
	 */
	public function reset ()
	{
		$this->root = null;
		$this->commands = [ ];
		return $this;
	}
	
	
	/**
	 * @param string $branch
	 * @param string $remote
	 * @return AbstractDriver
	 */
	public function pull ( $branch = 'master', $remote = 'origin' )
	{
		return $this->on ( $this->root )->command ( "git pull $remote $branch" );
	}
	
	
	/**
	 * @param string $path
	 * @return AbstractDriver
	 */
	protected function on ( $path )
	{
		$path = ( substr ( $path, 0, 1 ) == '/' ) ? $path : "{$this->root}/{$path}";
		return $this->command ( "cd {$path}" );
	}
	
}