<?php
/**
 * User interface for managing the 'ads.txt' file.
 */
class Advanced_Ads_Ads_Txt_Utils {
	const TRANSIENT = 'advanced_ads_ads_txt_ctp';

	/**
	 * Check if a third-party 'ads.txt' file exists.
	 *
	 * @return bool
	 */
	public static function third_party_exists( $url = null, $force = false ) {
		$url = $url ? $url : home_url( '/' );

		if ( false === ( $r = get_transient( self::TRANSIENT ) ) || $force ) {
			$response     = wp_remote_get( trailingslashit( $url ) . 'ads.txt', array( 'timeout' => 3 ) );
			$code         = wp_remote_retrieve_response_code( $response );
			$content      = wp_remote_retrieve_body( $response );
			$content_type = wp_remote_retrieve_header( $response, 'content-type' );

			$r = ( ! is_wp_error( $response )
				&& 404 !== $code
				&& 'text/plain' === $content_type
				&& false === strpos( $content, Advanced_Ads_Ads_Txt_Public::TOP ) ) ? 'yes' : 'no';
			set_transient( self::TRANSIENT, $r, WEEK_IN_SECONDS );
		}

		return 'yes' === $r;
	}


	/**
	 * Check if the another 'ads.txt' file should be hosted on the root domain.
	 *
	 * @return bool
	 */
	public static function need_file_on_root_domain( $url = null ) {
		$url = $url ? $url : home_url( '/' );

		$parsed_url = wp_parse_url( $url );
		if ( ! isset( $parsed_url['host'] ) ) {
			return false;
		}

		$host = $parsed_url['host'];

		if ( WP_Http::is_ip_address( $host ) ) {
			return false;
		}

		$host_parts = explode( '.', $host );
		$count      = count( $host_parts );
		if ( $count < 3 ) {
			return false;
		}

		// Example: `http://one.{net/org/gov/edu/co}.two`.
		$suffixes = array( 'net', 'org', 'gov', 'edu', 'co'  );
		if ( 3 === $count && in_array( $host_parts[ $count - 2 ], $suffixes, true ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if the site is in a subdirectory, for example 'http://one.two/three'.
	 *
	 * @return bool
	 */
	public static function is_subdir( $url = null ) {
		$url = $url ? $url : home_url( '/' );

		$parsed_url = wp_parse_url( $url );
		if ( ! empty( $parsed_url['path'] ) && '/' !== $parsed_url['path'] ) {
			return true;
		}
		return false;
	}
}
