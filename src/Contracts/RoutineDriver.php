<?php
namespace Hermes\Contracts;


interface RoutineDriver
{
	
	/**
	 * @param string $root
	 * @return RoutineDriver
	 */
	public function setRoot ( $root );
	
	
	/**
	 * @return RoutineDriver
	 */
	public function sudo ();
	
	
	/**
	 * @param string|array $command
	 * @return RoutineDriver
	 */
	public function command ( $command );
	
	
	/**
	 * @param string   $task
	 * @param \Closure $callback
	 * @return RoutineDriver
	 */
	public function extend ( $task, \Closure $callback );
	
	
	/**
	 * @param string|array $task
	 * @return RoutineDriver
	 */
	public function task ( $task );
	
	
	/**
	 * @return array
	 */
	public function all ();
	
	
	/**
	 * @return RoutineDriver
	 */
	public function reset ();
	
	
	/**
	 * @param \Closure $callback
	 * @return RoutineDriver
	 */
	public function inMaintenance ( \Closure $callback );
	
	
	/**
	 * @param string $branch
	 * @param string $remote
	 * @return RoutineDriver
	 */
	public function pull ( $branch = 'master', $remote = 'origin' );
	
	
	/**
	 * @return array
	 */
	public function provides ();
	
}