<?php
namespace Automattic\LegacyRedirector\Tests\Unit;

use Automattic\LegacyRedirector\Utils;
use Brain\Monkey;
use Yoast\WPTestUtils\BrainMonkey\TestCase;
use Automattic\LegacyRedirector\Tests\Unit\MonkeyStubs;

/**
 * Capability Class Unit Test
 */
final class UtilsTest extends TestCase {

	/**
	 * Setup any mocks before tests.
	 *
	 * @before
	 */
	public static function initialSetup() {
		new MonkeyStubs();
	}

	/**
	 * Tests Utils::mb_parse_url().
	 *
	 * @dataProvider get_protected_redirect_data
	 * @covers \Automattic\LegacyRedirector\Utils::mb_parse_url
	 *
	 * @param [type] $url             Full URL or path to redirect from.
	 * @param [type] $expected_schema Expected return schema.
	 * @param [type] $expected_domain Expected return domain.
	 * @param [type] $expected_path   Expected return path.
	 * @param [type] $expected_query  Expected return query.
	 * @return void
	 */
	public function test_mb_parse_url( $url, $expected_schema, $expected_domain, $expected_path, $expected_query ) {

		$this->do_assertion_mb_parse_url( $url, $expected_schema, $expected_domain, $expected_path, $expected_query );

	}

	/**
	 * Data provider for tests methods
	 *
	 * @return array
	 */
	public function get_protected_redirect_data() {
		return array(
			'redirect_simple_url_no_end_slash'   => array(
				'https://www.example.org',
				'https',
				'www.example.org',
				'',
				'',
			),
			'redirect_simple_url_with_end_slash' => array(
				'http://www.example.org/',
				'http',
				'www.example.org',
				'/',
				'',
			),
			'redirect_url_with_path'             => array(
				'https://www.example.com/test',
				'https',
				'www.example.com',
				'/test',
				'',
			),
			'redirect_unicode_url_with_query'    => array(
				'http://www.example.com//فوتوغرافيا/?test=فوتوغرافيا',
				'http',
				'www.example.com',
				'//فوتوغرافيا/',
				'test=فوتوغرافيا',
			),
			'redirect_unicode_path_with_query'   => array(
				'/فوتوغرافيا/?test=فوتوغرافيا',
				'',
				'',
				'/فوتوغرافيا/',
				'test=فوتوغرافيا',
			),
			'redirect_unicode_path_with_multiple_parameters' => array(
				'/فوتوغرافيا/?test2=فوتوغرافيا&test=فوتوغرافيا',
				'',
				'',
				'/فوتوغرافيا/',
				'test2=فوتوغرافيا&test=فوتوغرافيا',
			),

		);
	}

	/**
	 * Do assertion method for testing mb_parse_url().
	 *
	 * @param string $url             URL to test redirection against, can be a full blown URL with schema.
	 * @param string $expected_scheme Expected URL schema return.
	 * @param string $expected_host   Expected URL hostname return.
	 * @param string $expected_path   Expected URL path return.
	 * @param string $expected_query  Expected URL query return.
	 * @return void
	 */
	private function do_assertion_mb_parse_url( $url, $expected_scheme, $expected_host, $expected_path, $expected_query ) {
		$path_info = Utils::mb_parse_url( $url );

		if ( ! isset( $path_info['scheme'] ) ) {
			$path_info['scheme'] = '';
		}
		if ( ! isset( $path_info['host'] ) ) {
			$path_info['host'] = '';
		}
		if ( ! isset( $path_info['path'] ) ) {
			$path_info['path'] = '';
		}
		if ( ! isset( $path_info['query'] ) ) {
			$path_info['query'] = '';
		}

		$this->assertTrue( is_array( $path_info ) );
		$this->assertTrue( count( $path_info ) > 1 ? true : false );
		$this->assertSame( $expected_host, $path_info['host'] );
		$this->assertSame( $expected_path, $path_info['path'] );
		$this->assertSame( $expected_query, $path_info['query'] );

	}
}
