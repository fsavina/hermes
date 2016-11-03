<?php

namespace Hermes\Commands;


use Hermes\Contracts\RoutineDriver;


class Task extends AbstractCommand
{
	
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'hermes:task
							{remote? : The target remote for the required task}
							{task? : The task to be executed}
							{--f|force : Skip security confirmation}';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Prepare the remote server for the deploy procedure';
	
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
			$remote = trim ( $this->choice ( 'Which remote do you wish to run the task on?', $this->remotes () ), '*' );
		}

		$this->routine = $this->laravel[ 'hermes.routine' ];

		$task = $this->argument ( 'task' );
		if ( is_null ( $task ) )
		{
			$task = $this->choice ( 'Which task do you wish to execute?', $this->routine->provides () );
		}

		if ( $this->isGroup ( $remote ) )
		{
			$this->info ( "Running task '{$task}' on group: {$remote}" );
			$remotes = $this->resolveGroup ( $remote );
		} else
		{
			$remotes = [ $remote ];
		}
		
		foreach ( $remotes as $remote )
		{
			$config = $this->getConfig ( $remote );
			
			$this->info ( "Running task '{$task}' on remote: {$config[ 'remote' ]} ({$config['host']}, {$config['root']})" );
			
			if ( ! $force and ! $this->confirm ( 'Do you wish to continue? [y|N]' ) )
			{
				continue;
			}
			
			$this->routine->reset ()
						  ->setRoot ( $config[ 'root' ] );
			
			if ( isset( $config[ 'sudo' ] ) and $config[ 'sudo' ] )
			{
				$this->routine->sudo ();
			}
			
			$this->routine->task ( $task );
			
			if ( ! count ( $commands = $this->routine->all () ) )
			{
				$this->error ( "Empty commands list. Couldn't run task '{$task}' on remote '{$remote}'. The task is empty or missing." );
				continue;
			}
			
			$this->ssh->into ( $remote )
					  ->run ( $commands, function ( $line )
					  {
						  $this->info ( $line );
					  } );
		}
		
		return true;
	}
	
	
}
