<?php
/**
 * WordPress specific related functions.
 *
 * @package Skela
 */

namespace Skela\Services;

/** Class */
class WordPressService {

	/**
	 * Force an abort of a HTTP request
	 *
	 * @param integer $status_code HTTP status code number.
	 *
	 * @return void
	 */
	public static function abort_request( $status_code = 0 ) {

		// Always reset the content type.
		add_filter(
			'wp_headers',
			function ( $headers ) {
				$headers['Content-Type'] = 'text/html';
				return $headers;
			},
			99,
			1
		);

		// ...reset in nocache_headers as well.
		add_filter(
			'nocache_headers',
			function ( $headers ) {
				$headers['Content-Type'] = 'text/html';
				return $headers;
			},
			99,
			1
		);

		switch ( $status_code ) {
			case 403:
				wp_die(
					'Error: Access forbidden.',
					'Access Forbidden',
					array( 'response' => 403 )
				);
				// no break.
			case 404:
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				nocache_headers();
				include get_query_template( '404' );
				exit;
		}
	}
}
