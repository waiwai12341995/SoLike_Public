<?php
namespace um_ext\um_notifications\core;

if ( ! defined( 'ABSPATH' ) ) exit;


class Notifications_Enqueue {

	function __construct() {
		add_action( 'wp_enqueue_scripts',  array( &$this, 'wp_enqueue_scripts' ), 9999 );
	}


	/**
	 *
	 */
	function wp_enqueue_scripts() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'UM_SCRIPT_DEBUG' ) ) ? '' : '.min';

		wp_register_script('um-moment', um_notifications_url . 'assets/js/moment-with-locales.min.js', array( 'jquery' ), um_notifications_version, true );
		wp_register_script('um-moment-timezone', um_notifications_url . 'assets/js/moment-timezone.min.js', array( 'jquery' ), um_notifications_version, true );
		wp_register_script('um_notifications', um_notifications_url . 'assets/js/um-notifications' . $suffix . '.js', array( 'jquery', 'wp-util', 'um-moment', 'um-moment-timezone', 'um_scripts' ), um_notifications_version, true );

		$timer = false;
		if ( UM()->options()->get( 'realtime_notify' ) ) {
			$timer = UM()->options()->get( 'realtime_notify_timer' );
			$timer = ! empty( $timer ) ? $timer : 45;
			$timer = 1000 * $timer;
		}

		// Localize time
		$localize_data = array(
			'timer' => $timer
		);
		wp_localize_script( 'um_notifications', 'um_notifications', $localize_data );

		wp_register_style('um_notifications', um_notifications_url . 'assets/css/um-notifications' . $suffix . '.css', array(), um_notifications_version );

		/*wp_enqueue_script( 'um_notifications' );
		wp_enqueue_style( 'um_notifications' );*/
	}

}