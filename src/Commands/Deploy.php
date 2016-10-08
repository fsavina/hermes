<?php

namespace Hermes\Commands;


use Hermes\Managers\DeployManager;
use Hermes\Managers\LaravelManager;


class Deploy extends AbstractCommand
{
	
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'hermes:deploy
							{remote : The remote to be deployed}
							{branch=master : The branch to be deployed}
							{--f|force : Skip security confirmation}';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Deploy the given branch to the given remote';
	
	
	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle ()
	{
		$force = $this->option ( 'force' );
		
		if ( ! $force and ! $this->confirm ( 'Do you wish to continue? [y|N]' ) )
		{
			return false;
		}
		
		$remote = $this->argument ( 'remote' );
		$branch = $this->argument ( 'branch' );

		if ( $this->isGroup ( $remote ) )
		{
			$this->info ( "Running deploy on group: {$remote}" );
			$remotes = $this->resolveGroup ( $remote );
		} else
		{
			$remotes = [ $remote ];
		}
		
		foreach ( $remotes as $remote )
		{
			$config = $this->getConfig ( $remote );
			
			$this->warn ( "Pushing branch '{$branch}' to remote: {$config[ 'remote' ]} ({$config['host']})" );
			$this->gitPush ( $config[ 'remote' ], $branch );
			
			$this->warn ( "Deploying branch '{$branch}' to remote path: {$config[ 'root' ]}" );
			$procedure = $this->prepareProcedure ( $config );

			$this->runProcedure ( $remote, $procedure );
		}
		
		return true;
	}
	
	
	/**
	 * @return \Closure
	 */
	protected function writer ()
	{
		return function ( $line )
		{
			$this->info ( $line );
		};
	}
	
	
	/**
	 * @param string $remote
	 * @param string $branch
	 */
	protected function gitPush ( $remote, $branch = 'master' )
	{
		$path = base_path ();
		exec ( "git -C {$path} push {$remote} {$branch}" );
	}


	/**
	 * @param $config
	 * @return LaravelManager
	 */
	protected function prepareProcedure ( $config )
	{
		return ( new LaravelManager( $config[ 'root' ] ) )
			->whileOnMaintenance ( function ( LaravelManager $procedure ) use ( $config )
			{
				$procedure->onRoot ()->gitPull ( $this->argument ( 'branch' ) );

				$tasks =
					( isset( $config[ 'tasks' ] ) and is_array ( $config[ 'tasks' ] ) )
						? $config[ 'tasks' ] : [ ];
				$procedure->pushTask ( $tasks );

				$commands =
					( isset( $config[ 'commands' ] ) and is_array ( $config[ 'commands' ] ) )
						? $config[ 'commands' ] : [ ];
				$procedure->pushCommand ( $commands );
			} );
	}


	protected function runProcedure ( $remote, DeployManager $procedure )
	{
		$this->ssh->into ( $remote )
				  ->run ( $procedure->commands (), $this->writer () );
	}
	
}
