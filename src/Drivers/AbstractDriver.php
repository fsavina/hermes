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
	 * @var array
	 */
	protected $customTasks = [ ];
	
	
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
	 * @param string   $task
	 * @param \Closure $callback
	 * @return RoutineDriver
	 */
	public function extend ( $task, \Closure $callback )
	{
		$this->customTasks[ $task ] = $callback;
		
		return $this;
	}
	
	
	/**
	 * @param string $task
	 * @return RoutineDriver
	 */
	protected function callCustomTask ( $task )
	{
		return $this->customTasks[ $task ]( $this );
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
			$method = 'run' . Str::studly ( str_replace ( [ ':', '-' ], '_', $task ) ) . 'Task';
			
			if ( isset( $this->customTasks[ $task ] ) )
			{
				$this->callCustomTask ( $task );
			} elseif ( method_exists ( $this, $method ) )
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