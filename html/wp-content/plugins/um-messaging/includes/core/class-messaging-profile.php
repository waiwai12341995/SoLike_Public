<?php
namespace um_ext\um_messaging\core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Messaging_Profile
 * @package um_ext\um_messaging\core
 */
class Messaging_Profile {


	/**
	 * Messaging_Profile constructor.
	 */
	function __construct() {
		add_action( 'um_profile_navbar', array( &$this, 'add_profile_bar' ), 5 );
		add_filter( 'um_profile_navbar_classes', array( &$this, 'profile_navbar_classes' ), 10, 1 );

		add_filter( 'um_profile_tabs', array( &$this, 'add_tab' ), 200 );

		add_action( 'wp_footer', array( &$this, 'dynamic_styles' ), 99999999999999 );
		add_action( 'um_profile_content_messages_default', array( &$this, 'content_messages_default' ) );

		add_filter( 'um_profile_tag_hook__new_messages', array( &$this, 'unread_count_messages' ), 10, 2 );

		add_action( 'um_profile_footer', array( &$this, 'profile_footer_login_form' ), 99, 1 );
	}


	/**
	 * Customize the nav bar
	 *
	 * @param $args
	 */
	function add_profile_bar( $args ) {
		$user_id = um_profile_id();

		if ( is_user_logged_in() ) {
			if ( get_current_user_id() == $user_id ) {
				return;
			}

			if ( ! UM()->Messaging_API()->api()->can_message( $user_id ) ) {
				return;
			}
		}

		wp_enqueue_script( 'um-messaging' );
		wp_enqueue_style( 'um-messaging' ); ?>

		<div class="um-messaging-btn">
			<?php echo do_shortcode( '[ultimatemember_message_button user_id="' . $user_id . '"]' ) ?>
		</div>
		<?php
	}


	/**
	 * @param string $classes
	 * @return string
	 */
	function profile_navbar_classes( $classes ) {
		$classes .= ' um-messaging-bar';
		return $classes;
	}


	/**
	 * Messaging profile tab
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	function add_tab( $tabs ) {
		if ( ! is_admin() && ! um_user('enable_messaging' ) ) {
			return $tabs;
		}

		if ( ! is_user_logged_in() ) {
			return $tabs;
		}

		$tabs['messages'] = array(
			'name' => __( 'Messages', 'um-messaging' ),
			'icon' => 'um-faicon-envelope-o',
		);

		if ( ! is_admin() && um_user('can_read_pm' ) ) {
			$count = UM()->Messaging_API()->api()->get_unread_count( um_profile_id() );
			$tabs['messages']['notifier'] = ( $count > 10 ) ? 10 . '+' : $count;
		}

		return $tabs;
	}


	/**
	 * Footer css
	 */
	function dynamic_styles() {
		?>
		<style type="text/css">

			<?php

			$color_hex = UM()->options()->get('pm_active_color');
			$color_rgb = UM()->Messaging_API()->api()->hex_to_rgb( $color_hex );

			?>

			.um-message-item-content a {color: <?php echo $color_hex; ?>; text-decoration: underline !important}
			.um-message-item-content a:hover {color: rgba(<?php echo $color_rgb; ?>, 0.9)}

			.um-message-item.left_m .um-message-item-content a {color: #fff}

			.um-message-send, .um-message-send.disabled:hover { background-color: <?php echo $color_hex; ?> }
			.um-message-send:hover { background-color: rgba(<?php echo $color_rgb; ?>, 0.9) }

			.um-message-item.left_m .um-message-item-content { background-color: rgba(<?php echo $color_rgb; ?>, 0.8);}

			.um-message-footer {
				background: rgba(<?php echo $color_rgb; ?>, 0.03);
				border-top: 1px solid rgba(<?php echo $color_rgb; ?>, 0.2);
			}

			.um-message-textarea textarea, div.um div.um-form .um-message-textarea textarea {border: 2px solid rgba(<?php echo $color_rgb; ?>, 0.3) !important}
			.um-message-textarea textarea:focus,  div.um div.um-form .um-message-textarea textarea:focus {border: 2px solid rgba(<?php echo $color_rgb; ?>, 0.6) !important}

			.um-message-emolist {
				border: 1px solid rgba(<?php echo $color_rgb; ?>, 0.25);
			}

			.um-message-conv-item.active {
				color: <?php echo $color_hex; ?>;
			}

			.um-message-conv-view {
				border-left: 1px solid rgba(<?php echo $color_rgb; ?>, 0.2);
			}

		</style>

		<?php
	}


	/**
	 * Default tab
	 *
	 * @param $args
	 */
	function content_messages_default( $args ) {
		echo do_shortcode( '[ultimatemember_messages]' );
	}


	/**
	 * Display unread messages count in menu
	 *
	 * @param $value
	 * @param $user_id
	 *
	 * @return string
	 */
	function unread_count_messages( $value, $user_id ) {
		wp_enqueue_script( 'um-messaging' );
		wp_enqueue_style( 'um-messaging' );

		$count = UM()->Messaging_API()->api()->get_unread_count( $user_id );
		return '<span class="um-message-unreaditems count-'. $count . '">' . ( ( $count > 10 ) ? 10 . '+' : $count ) . '</span>';
	}



	/**
	 * Insert Login form to hidden block
	 *
	 * @param array $args
	 */
	function profile_footer_login_form( $args ) {
		if ( is_user_logged_in() ) {
			return;
		}

		wp_enqueue_script( 'um-messaging' );
		wp_enqueue_style( 'um-messaging' );

		if ( ! empty( $_COOKIE['um_messaging_invite_login'] ) ) {
			$_POST = array_merge( json_decode( wp_unslash( $_COOKIE['um_messaging_invite_login'] ), true ), $_POST );
			UM()->form()->form_init();
		}

		add_filter( 'um_browser_url_redirect_to__filter', array( UM()->Messaging_API()->api(), 'set_redirect_to' ), 10, 1 ); ?>

		<div class="um_messaging_hidden_login" style="display: none;">
			<?php echo do_shortcode( '[ultimatemember form_id="' . UM()->shortcodes()->core_login_form() . '"]' ); ?>
		</div>

		<?php
	}
}