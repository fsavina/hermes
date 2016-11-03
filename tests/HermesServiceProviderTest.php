<?php


use Hermes\HermesServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery\MockInterface;


class HermesServiceProviderTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @var MockInterface|\Illuminate\Contracts\Foundation\Application
	 */
	protected $app;
	
	/**
	 * @var HermesServiceProvider
	 */
	protected $provider;
	
	
	protected function setUp ()
	{
		parent::setUp ();
		
		$this->app = Mockery::mock ( ArrayAccess::class );
		
		$this->provider = new HermesServiceProvider( $this->app );
	}
	
	
	public function testRegister ()
	{
		$this->app->shouldReceive ( 'register' )->once ();
		$this->app->shouldReceive ( 'singleton' )->twice ();
		
		$this->provider->register ();
		
		$this->assertTrue ( true );
	}
	
	
	public function testBoot ()
	{
		$this->app->shouldReceive ( 'runningInConsole' )->once ()->andReturn ( true );
		
		$this->app->shouldReceive ( 'offsetGet' )
				  ->zeroOrMoreTimes ()
				  ->with ( 'events' )
				  ->andReturn ( $this->mockEvents () );
		
		$this->provider->boot ();
		
		$this->assertTrue ( true );
	}
	
	
	public function testProvides ()
	{
		$this->assertEquals ( [ 'hermes', 'hermes.routine' ], $this->provider->provides () );
	}
	
	
	/**
	 * @return Dispatcher
	 */
	protected function mockEvents ()
	{
		$events = Mockery::mock ( Dispatcher::class );
		$events->shouldReceive ( 'listen' )
			   ->zeroOrMoreTimes ()
			   ->withAnyArgs ()
			   ->andReturnUndefined ();
		return $events;
	}
	
}
