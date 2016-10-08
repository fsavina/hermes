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
	
	
	protected function getConfig ( $server )
	{
		return $this->config->get ( "remote.connections.$server" );
	}
	
	
	protected function isGroup ( $server )
	{
		return $this->config->has ( "remote.groups.$server" );
	}
	
	
	protected function resolveGroup ( $server )
	{
		return (array) $this->config->get ( "remote.groups.$server" );
	}
	
}