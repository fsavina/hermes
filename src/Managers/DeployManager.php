<?php
namespace Hermes\Managers;


use Illuminate\Support\Str;


abstract class DeployManager
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
	protected $tasks = [ ];
	
	
	/**
	 * @param string $root
	 */
	public function __construct ( $root )
	{
		$this->root = $root;
	}
	
	
	/**
	 * @param string|array $command
	 * @return DeployManager
	 */
	public function pushCommand ( $command )
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
	 * @return DeployManager
	 */
	public function pushTask ( $task )
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
	public function commands ()
	{
		return $this->commands;
	}
	
	
	/**
	 * @return DeployManager
	 */
	public function reset ()
	{
		$this->commands = [ ];
		return $this;
	}
	
	
	/**
	 * @param string $path
	 * @return DeployManager
	 */
	public function on ( $path )
	{
		$path = ( substr ( $path, 0, 1 ) == '/' ) ? $path : "{$this->root}/{$path}";
		return $this->pushCommand ( "cd {$path}" );
	}
	
	
	/**
	 * @return DeployManager
	 */
	public function onRoot ()
	{
		return $this->on ( $this->root );
	}
	
	
	/**
	 * @param string $branch
	 * @param string $remote
	 * @return DeployManager
	 */
	public function gitPull ( $branch = 'master', $remote = 'origin' )
	{
		return $this->pushCommand ( "git pull $remote $branch" );
	}
	
}