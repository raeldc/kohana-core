<?php

/**
 * Tests Kohana Form helper
 *
 * @group kohana
 * @group kohana.form
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     Jeremy Bush <contractfrombelow@gmail.com>
 * @copyright  (c) 2008-2010 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_FormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provides test data for testOpen()
	 * 
	 * @return array
	 */
	function providerOpen()
	{
		return array(
			// $value, $result
			#array(NULL, NULL, '<form action="/" method="post" accept-charset="utf-8">'), // Fails because of Request::$current
			array('foo', NULL, '<form action="/foo" method="post" accept-charset="utf-8">'),
			array('', NULL, '<form action="/" method="post" accept-charset="utf-8">'),
			array('foo', array('method' => 'get'), '<form action="/foo" method="get" accept-charset="utf-8">'),
		);
	}

	/**
	 * Tests Form::open()
	 *
	 * @test
	 * @dataProvider providerOpen
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testOpen($action, $attributes, $expected)
	{
		$this->assertSame($expected, Form::open($action, $attributes));
	}

	/**
	 * Tests Form::close()
	 *
	 * @test
	 */
	function testClose()
	{
		$this->assertSame('</form>', Form::close());
	}

	/**
	 * Provides test data for testInput()
	 * 
	 * @return array
	 */
	function providerInput()
	{
		return array(
			// $value, $result
			array('foo', 'bar', NULL, 'input', '<input type="text" name="foo" value="bar" />'),
			array('foo', NULL, NULL, 'input', '<input type="text" name="foo" />'),
			array('foo', 'bar', NULL, 'hidden', '<input type="hidden" name="foo" value="bar" />'),
			array('foo', 'bar', NULL, 'password', '<input type="password" name="foo" value="bar" />'),
		);
	}

	/**
	 * Tests Form::open()
	 *
	 * @test
	 * @dataProvider providerInput
	 * @param boolean $input  Input for File::mime
	 * @param boolean $expected Output for File::mime
	 */
	function testInput($name, $value, $attributes, $type, $expected)
	{
		$this->assertSame($expected, Form::$type($name, $value, $attributes));
	}
}