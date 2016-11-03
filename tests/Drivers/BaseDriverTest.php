<?php

use Hermes\Drivers\AbstractDriver;


class BaseDriverTest extends DriverTestCase
{
	
	
	public function testConstruction ()
	{
		$this->assertInstanceOf ( AbstractDriver::class, $this->routine );
	}
	
	
	public function testProvidedTasks ()
	{
		$tasks = $this->routine->provides ();
		
		$this->assertInternalType ( 'array', $tasks );
		$this->assertGreaterThan ( 0, count ( $tasks ) );
	}
	
	
	public function testAddCommand ()
	{
		$commands = $this->routine->command ( 'cd foo/bar' )
								  ->all ();
		
		$this->assertInternalType ( 'array', $commands );
		$this->assertCommandExists ( 'cd foo/bar' );
		
		$commands = $this->routine->command ( [ 'git status', 'pwd' ] )
								  ->all ();
		
		$this->assertInternalType ( 'array', $commands );
		$this->assertEquals ( 3, count ( $commands ) );
		$this->assertCommandExists ( 'pwd' );
	}
	
	
	public function testSudo ()
	{
		$this->routine->sudo ()
					  ->command ( 'foo' )
					  ->all ();
		$this->assertCommandExists ( 'sudo foo' );
	}
	
	
	public function testSetRootAndMaintenance ()
	{
		$this->routine->setRoot ( '/foo/bar' )
					  ->inMaintenance ( function ( AbstractDriver $routine )
					  {
						  $routine->command ( 'pwd' );
					  } );
		
		$commands = $this->routine->all ();
		
		$this->assertEquals ( 3, count ( $commands ) );
		$this->assertEquals ( 'php /foo/bar/artisan down', $commands[ 0 ] );
		$this->assertEquals ( 'pwd', $commands[ 1 ] );
		$this->assertEquals ( 'php /foo/bar/artisan up', $commands[ 2 ] );
	}
	
	
	public function testPull ()
	{
		$this->routine->setRoot ( '/foo/bar' )
					  ->pull ();
		
		$commands = $this->routine->all ();
		
		$this->assertEquals ( 2, count ( $commands ) );
		$this->assertEquals ( 'cd /foo/bar', $commands[ 0 ] );
		$this->assertEquals ( 'git pull origin master', $commands[ 1 ] );
	}
	
	
	public function testReset ()
	{
		$this->routine->command ( 'pwd' )
					  ->reset ();
		
		$this->assertEquals ( 0, count ( $this->routine->all () ) );
	}
	
	
	public function testCustomTask ()
	{
		$this->routine->extend ( 'foo:bar', function ( AbstractDriver $routine )
		{
			$routine->command ( 'foo bar' );
		} );
		
		$this->routine->task ( 'foo:bar' );
		
		$this->assertCommandExists ( 'foo bar' );
	}
	
}