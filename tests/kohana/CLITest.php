<?php

/**
 * Unit tests for CLI
 *
 * Based on the Kohana-unittest test
 *
 * @group kohana
 * @group kohana.cli
 * @group testdox
 *
 * @see CLI
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_CLITest extends Kohana_Unittest_TestCase
{
	
	/**
	 * Tell PHPUnit to isolate globals during tests 
	 * @var boolean 
	 */
	protected $backupGlobals = TRUE;

	/**
	 * An array of arguments to put in $_SERVER['argv']
	 * @var array 
	 */
	protected $options = array(
							'--uri' => 'test/something',
							'--we_are_cool',
							'invalid option',
							'--version' => '2.23',
							'--important' => 'something=true',
						);

	/**
	 * Setup the enviroment for each test
	 *
	 * PHPUnit automatically backups up & restores global variables
	 */
	function setUp()
	{
		$_SERVER['argv'] = array('index.php');

		foreach($this->options as $option => $value)
		{
			if(is_string($option))
			{
				$_SERVER['argv'][] = $option.'='.$value;
			}
			else
			{
				$_SERVER['argv'][] = $value;
			}
		}

		$_SERVER['argc'] = count($_SERVER['argv']);
	}

	/**
	 * Options should only parse arguments requested
	 *
	 * @test
	 * @covers CLI::options
	 */
	function test_only_parses_wanted_arguments()
	{
		$options = CLI::options('uri');

		$this->assertSame(1, count($options));
		
		$this->assertArrayHasKey('uri', $options);
		$this->assertSame($options['uri'], $this->options['--uri']);
	}

	/**
	 * Options should not parse invalid arguments (i.e. not starting with --_
	 *
	 * @test
	 * @covers CLI::options
	 */
	function test_does_not_parse_invalid_arguments()
	{
		$options = CLI::options('uri', 'invalid');
		
		$this->assertSame(1, count($options));
		$this->assertArrayHasKey('uri', $options);
		$this->assertArrayNotHasKey('invalid', $options);
	}

	/**
	 * Options should parse multiple arguments & values correctly
	 *
	 * @test
	 * @covers CLI::options
	 */
	function test_parses_multiple_arguments()
	{
		$options = CLI::options('uri', 'version');

		$this->assertSame(2, count($options));
		$this->assertArrayHasKey('uri', $options);
		$this->assertArrayHasKey('version', $options);
		$this->assertSame($this->options['--uri'], $options['uri']);
		$this->assertSame($this->options['--version'], $options['version']);
	}

	/**
	 * Options should parse arguments without specified values as NULL
	 *
	 * @test
	 * @covers CLI::options
	 */
	function test_parses_arguments_without_value_as_null()
	{
		$options = CLI::options('uri', 'we_are_cool');

		$this->assertSame(2, count($options));
		$this->assertSame(NULL, $options['we_are_cool']);
	}

	/**
	 * 
	 * @test
	 * @covers CLI::options
	 * @ticket 2642
	 */
	function test_cli_only_splits_on_the_first_equals()
	{
		$options = CLI::options('important');

		$this->assertSame(1, count($options));
		$this->assertSame('something=true', reset($options));
	}
}
