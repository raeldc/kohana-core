<?php

/**
 * Tests Kohana_Security
 *
 * @group kohana
 */

Class Kohana_SecurityTest extends Kohana_Unittest_TestCase
{
	/**
	 * Provides test data for test_envode_php_tags()
	 *
	 * @return array Test data sets
	 */
	function provider_encode_php_tags()
	{
		return array
			(	
				array("&lt;?php echo 'helloo'; ?&gt;", "<?php echo 'helloo'; ?>"),
			);
	}

	/**
	 * Tests Security::encode_php_tags()
	 *
	 * @test
	 * @dataProvider provider_encode_php_tags
	 * @covers Security::encode_php_tags
	 */
	function test_encode_php_tags($expected, $input)
	{
		$this->assertSame($expected, Security::encode_php_tags($input));
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
	function test_xss_clean_removes_null_bytes()
	{
		$input = "<\0script>alert('XSS');<\0/script>";

		$this->assertSame("alert('XSS');", Security::xss_clean($input));
	}
}
