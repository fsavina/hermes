<?php

namespace Hermes\Commands;


use Collective\Remote\RemoteManager;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as ConfigContract;


class AbstractCommand extends Command
{
	
	/**
	 * @var RemoteManager
	 */
	protected $ssh;
	
	/**
	 * @var ConfigContract
	 */
	protected $config;
	
	
	/**
	 * Create a new command instance.
	 * @param ConfigContract $config
	 * @param RemoteManager  $ssh
	 */
	public function __construct ( ConfigContract $config, RemoteManager $ssh )
	{
		parent::__construct ();
		
		$this->config = $config;
		$this->ssh = $ssh;
	}
	
	
	/**
	 * @param string $remote
	 * @return array
	 */
	protected function getConfig ( $remote )
	{
		return $this->config->get ( "remote.connections.$remote" );
	}
	
	
	/**
	 * @param string $group
	 * @return bool
	 */
	protected function isGroup ( $group )
	{
		return $this->config->has ( "remote.groups.$group" );
	}
	
	
	/**
	 * @param string $group
	 * @return array
	 */
	protected function resolveGroup ( $group )
	{
		return (array) $this->config->get ( "remote.groups.$group" );
	}
	
	
	/**
	 * @return array
	 */
	protected function remotes ()
	{
		$remotes = array_keys ( $this->config->get ( 'remote.connections' ) );
		
		foreach ( $this->config->get ( 'remote.groups' ) as $group => $groupRemotes )
		{
			array_push ( $remotes, "$group ==> " . implode ( ', ', $groupRemotes ) );
		}
		
		return $remotes;
	}
	
}