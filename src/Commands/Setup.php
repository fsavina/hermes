<?php

namespace Hermes\Commands;


class Setup extends AbstractCommand
{
	
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'hermes:setup
							{remote : The remote to be setup}';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Prepare the remote server for the deploy procedure';
	
	
	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle ()
	{
		$remote = $this->argument ( 'remote' );

		$config = $this->getConfig ( $remote );

		$commands = [ ];

		array_push ( $commands, "mkdir -p {$config['repository']}" );
		array_push ( $commands, "cd {$config['repository']}" );
		array_push ( $commands, "git init --bare" );

		array_push ( $commands, "mkdir -p {$config['root']}" );
		array_push ( $commands, "cd {$config['root']}" );
		array_push ( $commands, "git init" );
		array_push ( $commands, "git remote add origin {$config['repository']}" );

		$this->ssh->into ( $remote )
				  ->run ( $commands, function ( $line )
				  {
					  $this->info ( $line );
				  } );

		return true;
	}
	
	
}
