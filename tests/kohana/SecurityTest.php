<?php

/**
 * Tests Kohana_Security
 *
 * @group kohana
 */

Class Kohana_SecurityTest extends Kohana_Unittest_TestCase
{
	/**
	 * Provides test data for testEncodePHPTags()
	 *
	 * @return array Test data sets
	 */
	function providerEncodePHPTags()
	{
		return array(
			array("&lt;?php echo 'helloo'; ?&gt;", "<?php echo 'helloo'; ?>"),
		);
	}

	/**
	 * Tests Security::encode_php_tags()
	 *
	 * @test
	 * @dataProvider providerEncodePHPTags
	 * @covers Security::encode_php_tags
	 */
	function testEncodePHPTags($expected, $input)
	{
		$this->assertSame($expected, Security::encode_php_tags($input));
	}

	/**
	 * Provides test data for testStripImageTags()
	 *
	 * @return array Test data sets
	 */
	function providerStripImageTags()
	{
		return array(
			array('foo', '<img src="foo" />'),
		);
	}

	/**
	 * Tests Security::strip_image_tags()
	 *
	 * @test
	 * @dataProvider providerStripImageTags
	 * @covers Security::strip_image_tags
	 */
	function testStripImageTags($expected, $input)
	{
		$this->assertSame($expected, Security::strip_image_tags($input));
	}

	/**
	 * Provides test data for Security::token()
	 *
	 * @return array Test data sets
	 */
	function providerCSRFToken()
	{
		$array = array();
		for ($i = 0; $i <= 4; $i++)
		{
			Security::$token_name = 'token_'.$i;
			$array[] = array(Security::token(TRUE), Security::check(Security::token(FALSE)), $i);
		}
		return $array;
	}

	/**
	 * Tests Security::token()
	 *
	 * @test
	 * @dataProvider providerCSRFToken
	 * @covers Security::token
	 */
	function testCSRFToken($expected, $input, $iteration)
	{
		Security::$token_name = 'token_'.$iteration;
		$this->assertSame(TRUE, $input);
		$this->assertSame($expected, Security::token(FALSE));
	}

	/**
	 * Tests that Security::xss_clean() removes null bytes
	 * 
	 *
	 * @test
	 * @covers Security::xss_clean
	 * @ticket 2676
	 * @see http://www.hakipedia.com/index.php/Poison_Null_Byte#Perl_PHP_Null_Byte_Injection
	 */
	function testXssCleanRemovesNullBytes()
	{
		$input = "<\0script>alert('XSS');<\0/script>";

		$this->assertSame("alert('XSS');", Security::xss_clean($input));
	}
}
