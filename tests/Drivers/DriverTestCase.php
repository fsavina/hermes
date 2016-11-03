<?php

use Hermes\Drivers\AbstractDriver;
use Hermes\Drivers\RoutineManager;


class DriverTestCase extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @var RoutineManager
	 */
	protected $manager;
	
	/**
	 * @var AbstractDriver
	 */
	protected $routine;
	
	
	public function setUp ()
	{
		$this->manager = new RoutineManager( null );
		$this->routine = $this->manager->driver ();
	}
	
	
	protected function assertCommandExists ( $command )
	{
		$this->assertTrue ( in_array ( $command, $this->routine->all () ) );
	}
	
}