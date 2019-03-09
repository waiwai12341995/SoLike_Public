<?php
namespace um_ext\um_messaging\core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Messaging_Shortcode
 * @package um_ext\um_messaging\core
 */
class Messaging_Shortcode {


	/**
	 * Messaging_Shortcode constructor.
	 */
	function __construct() {
		add_shortcode( 'ultimatemember_messages', array( &$this, 'ultimatemember_messages' ) );
		add_shortcode( 'ultimatemember_message_button', array( &$this, 'ultimatemember_message_button' ) );
		add_shortcode( 'ultimatemember_message_count', array( &$this, 'ultimatemember_message_count' ) );
	}


	/**
	 * Conversations list shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	function ultimatemember_messages( $args = array() ) {
		wp_enqueue_script( 'um-messaging' );
		wp_enqueue_style( 'um-messaging' );

		$defaults = array(
			'user_id' => get_current_user_id()
		);
		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var $user_id
		 */
		extract( $args );

		ob_start();

		$conversations = UM()->Messaging_API()->api()->get_conversations( $user_id );

		$show_conversations = array();
		if ( ! empty( $conversations ) ) {
			foreach ( $conversations as $conversation ) {

				if ( $conversation->user_a == um_profile_id() ) {
					$user = $conversation->user_b;
				} else {
					$user = $conversation->user_a;
				}

				if ( UM()->Messaging_API()->api()->blocked_user( $user ) ) {
					continue;
				}

				if ( UM()->Messaging_API()->api()->hidden_conversation( $conversation->conversation_id ) ) {
					continue;
				}

				$show_conversations[] = $conversation;
			}

			$conversations = $show_conversations;
		} else {
			$conversations = array();
		}


		if ( isset( $_GET['conversation_id'] ) ) {
			if ( esc_attr( absint( $_GET['conversation_id'] ) ) ) {
				foreach ( $conversations as $conversation ) {
					if ( $conversation->conversation_id == $_GET['conversation_id'] ) {
						$current_conversation = esc_attr( absint( $_GET['conversation_id'] ) );
						continue;
					}
				}
			}
		}

		if ( file_exists( get_stylesheet_directory() . '/ultimate-member/templates/conversations.php' ) ) {
			include_once get_stylesheet_directory() . '/ultimate-member/templates/conversations.php';
		} else {
			include_once um_messaging_path . 'templates/conversations.php';
		}

		$output = ob_get_clean();
		return $output;
	}


	/**
	 * Start conversation button shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	function ultimatemember_message_button( $args = array() ) {
		wp_enqueue_script( 'um-messaging' );
		wp_enqueue_style( 'um-messaging' );

		$defaults = array(
			'user_id' => 0
		);
		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var $user_id
		 */
		extract( $args );

		if ( ! UM()->Messaging_API()->api()->can_message( $user_id ) ) {
			return '';
		}

		$current_url = UM()->permalinks()->get_current_url();
		if ( um_get_core_page( 'user' ) ) {
			do_action( "um_messaging_button_in_profile", $current_url, $user_id );
		}

		ob_start();

		if ( ! is_user_logged_in() ) {
			$redirect = um_get_core_page( 'login' );

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
					$redirect = add_query_arg( 'redirect_to', urlencode( $_SERVER['HTTP_REFERER'] ), $redirect );
				}
			} else {
				$redirect = add_query_arg( 'redirect_to', $current_url, $redirect );
			} ?>

			<a href="<?php echo esc_attr( $redirect ) ?>" class="um-login-to-msg-btn um-message-btn um-button" data-message_to="<?php echo esc_attr( $user_id ) ?>">
				<?php _e('Message','um-messaging') ?>
			</a>

		<?php } elseif ( $user_id != get_current_user_id() ) { ?>

			<a href="javascript:void(0);" class="um-message-btn um-button" data-message_to="<?php echo esc_attr( $user_id ) ?>">
				<span><?php _e( 'Message','um-messaging' ) ?></span>
			</a>

		<?php }

		$btn = ob_get_clean();
		return $btn;
	}


	/**
	 * Unread messages shortcode
	 *
	 * @param array $args
	 *
	 * @return int|string
	 */
	function ultimatemember_message_count( $args = array() ) {
		if ( ! is_user_logged_in() ) {
			return '';
		}

		wp_enqueue_script( 'um-messaging' );
		wp_enqueue_style( 'um-messaging' );

		$defaults = array(
			'user_id' => get_current_user_id()
		);
		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var $user_id
		 */
		extract( $args );

		$count = UM()->Messaging_API()->api()->get_unread_count( $user_id );
		$count = ( $count > 10 ) ? 10 . '+' : $count;
		return $count;
	}

}