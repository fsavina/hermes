<?php


class LaravelDriverTest extends DriverTestCase
{

	public function testBuiltInTasks ()
	{
		foreach ( $this->routine->provides () as $task )
		{
			$this->routine->reset ()
						  ->task ( $task );

			$this->assertGreaterThan ( 0, count ( $this->routine->all () ) );
		}
	}
	
}