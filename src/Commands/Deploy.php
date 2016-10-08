<?php

namespace Hermes\Commands;


use Hermes\Procedures\Artisan;


class Deploy extends AbstractCommand
{
	
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'hermes:deploy {server=stage} {branch=master} {--f|force}';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Deploy command';
	
	
	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle ()
	{
		$server = $this->argument ( 'server' );
		
		$force = $this->option ( 'force' );

		if ( ! $force and ! $this->confirm ( 'Do you wish to continue? [y|N]' ) )
		{
			return false;
		}
		
		if ( $this->isGroup ( $server ) )
		{
			$this->info ( "Running deploy on group: {$server}" );
			$servers = $this->resolveGroup ( $server );
		} else
		{
			$servers = [ $server ];
		}
		
		foreach ( $servers as $server )
		{
			$config = $this->getConfig ( $server );

			$branch = $this->argument ( 'branch' );

			$this->warn ( "Pushing branch '{$branch}' to remote: {$config[ 'remote' ]} ({$config['host']})" );

			$this->gitPush ( $config[ 'remote' ], $branch );

			$this->warn ( "Deploying branch '{$branch}' to remote path: {$config[ 'webroot' ]}" );
			$procedure = $this->prepareProcedure ( $config );

			$this->ssh->into ( $server )
					  ->run ( $procedure->commands (), $this->writer () );
		}
		
		return true;
	}


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
	 * @param array $config
	 * @return Artisan
	 */
	protected function prepareProcedure ( array $config )
	{
		return ( new Artisan( $config[ 'webroot' ] ) )
			->whileOnMaintenance ( function ( Artisan $procedure )
			{
				$procedure
					->onRoot ()
					->gitPull ( $this->argument ( 'branch' ) )
					->composerInstall ()
					->bowerInstall ()
					->migrate ()
					->clearCache ();

				$procedure
					->setPermissions ( 'bootstrap/cache', 775 )
					->setPermissions ( [
										   'app',
										   'config',
										   'resources',
										   'public/assets/vendor',
										   'public/build',
										   'public/images',
										   'routes',
										   'vendor'
									   ], 755 );

				$procedure->on ( 'vendor/finnegan/cds' )->gitIgnoreFileMode ();
			} );
	}
	
}
