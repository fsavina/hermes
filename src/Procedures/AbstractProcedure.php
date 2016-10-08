<?php
namespace Hermes\Procedures;


use Illuminate\Support\Str;


abstract class AbstractProcedure
{
	
	protected $root;
	
	/**
	 * @var array
	 */
	protected $commands = [ ];
	
	
	/**
	 * @param string $root
	 */
	public function __construct ( $root )
	{
		$this->root = $root;
	}
	
	
	/**
	 * @param string $command
	 * @return AbstractProcedure
	 */
	protected function pushCommand ( $command )
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
	 * @return array
	 */
	public function commands ()
	{
		return $this->commands;
	}
	
	
	/**
	 * @return AbstractProcedure
	 */
	public function reset ()
	{
		$this->commands = [ ];
		return $this;
	}
	
	
	/**
	 * @param string $path
	 * @return AbstractProcedure
	 */
	public function on ( $path )
	{
		$path = ( substr ( $path, 0, 1 ) == '/' ) ? $path : "{$this->root}/{$path}";
		return $this->pushCommand ( "cd {$path}" );
	}
	
	
	/**
	 * @return AbstractProcedure
	 */
	public function onRoot ()
	{
		return $this->on ( $this->root );
	}
	
	
	/**
	 * @param string|array $path
	 * @param int          $mode
	 * @param bool         $recursive
	 * @return AbstractProcedure
	 */
	public function setPermissions ( $path, $mode, $recursive = true )
	{
		$path = (array) $path;
		$options = $recursive ? '-R' : '';
		
		foreach ( $path as $folder )
		{
			$this->pushCommand ( "chmod {$options} {$mode} {$folder}" );
		}
		return $this;
	}
	
	
	/**
	 * @param string $branch
	 * @param string $remote
	 * @return AbstractProcedure
	 */
	public function gitPull ( $branch = 'master', $remote = 'origin' )
	{
		return $this->pushCommand ( "git pull $remote $branch" );
	}
	
	
	/**
	 * @return AbstractProcedure
	 */
	public function gitIgnoreFileMode ()
	{
		return $this->pushCommand ( 'git config core.fileMode false' );
	}
	
	
	/**
	 * @param bool $noInteraction
	 * @return AbstractProcedure
	 */
	public function composerInstall ( $noInteraction = true )
	{
		return $this->pushCommand ( 'composer install ' . ( $noInteraction ? '--no-interaction' : '' ) );
	}
	
	
	/**
	 * @return AbstractProcedure
	 */
	public function bowerInstall ()
	{
		return $this->pushCommand ( 'bower install' );
	}
	
}