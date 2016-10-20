<?php

namespace Hermes\Commands;


use Hermes\Contracts\RoutineDriver;


class Deploy extends AbstractCommand
{
	
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'hermes:deploy
							{remote? : The remote to be deployed}
							{branch=master : The branch to be deployed}
							{--f|force : Skip security confirmation}';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Deploy the given branch to the given remote';

	/**
	 * @var RoutineDriver
	 */
	protected $routine;
	
	
	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle ()
	{
		$force = $this->option ( 'force' );

		$remote = $this->argument ( 'remote' );

		if ( is_null ( $remote ) )
		{
			$remote = trim ( $this->choice ( 'Which remote do you wish to deploy to?', $this->remotes () ), '*' );
		}

		$branch = $this->argument ( 'branch' );

		$this->routine = $this->laravel[ 'hermes.routine' ];

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
			
			$this->warn ( "Pushing branch '{$branch}' to remote: {$config[ 'remote' ]} ({$config['host']}, {$config['root']})" );

			if ( ! $force and ! $this->confirm ( 'Do you wish to continue? [y|N]' ) )
			{
				continue;
			}

			$this->push ( $config[ 'remote' ], $branch );
			
			$this->warn ( "Deploying branch '{$branch}' to remote path: {$config[ 'root' ]}" );
			$this->prepareRoutine ( $config );

			$this->runRoutine ( $remote, $this->routine );
		}
		
		return true;
	}
	
	
	/**
	 * @param string $remote
	 * @param string $branch
	 */
	protected function push ( $remote, $branch = 'master' )
	{
		$path = base_path ();
		exec ( "git -C {$path} push {$remote} {$branch}" );
	}


	/**
	 * @param $config
	 * @return RoutineDriver
	 */
	protected function prepareRoutine ( $config )
	{
		return $this->routine
			->reset ()
			->setRoot ( $config[ 'root' ] )
			->inMaintenance ( function ( RoutineDriver $procedure ) use ( $config )
			{
				$procedure->pull ( $this->argument ( 'branch' ) );

				$procedure->task ( $this->tasks ( $config ) );

				$procedure->command ( $this->commands ( $config ) );
			} );
	}


	/**
	 * @param array $config
	 * @return array
	 */
	protected function tasks ( $config )
	{
		return ( isset( $config[ 'tasks' ] ) and is_array ( $config[ 'tasks' ] ) ) ? $config[ 'tasks' ] : [ ];
	}


	/**
	 * @param array $config
	 * @return array
	 */
	protected function commands ( $config )
	{
		return ( isset( $config[ 'commands' ] ) and is_array ( $config[ 'commands' ] ) ) ? $config[ 'commands' ] : [ ];
	}


	/**
	 * @param string        $remote
	 * @param RoutineDriver $procedure
	 */
	protected function runRoutine ( $remote, RoutineDriver $procedure )
	{
		$this->ssh->into ( $remote )
				  ->run ( $procedure->all (), function ( $line )
				  {
					  $this->info ( $line );
				  } );
	}
	
}
