<?php

namespace Hermes\Commands;


use Illuminate\Console\Command;


class Setup extends Command
{
	
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'hermes:setup
							{remote=stage : The remote to be setup}';
	
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
		//$server = $this->argument ( 'remote' );
		return true;
	}
	
	
}
