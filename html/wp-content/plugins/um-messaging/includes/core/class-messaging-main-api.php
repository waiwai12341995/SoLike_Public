<?php
namespace um_ext\um_messaging\core;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Messaging_Main_API
 * @package um_ext\um_messaging\core
 */
class Messaging_Main_API {

	var $perms;

	/**
	 * Messaging_Main_API constructor.
	 */
	function __construct() {
		$this->emoji[':)'] = 'https://s.w.org/images/core/emoji/72x72/1f604.png';
		$this->emoji[':smiley:'] = 'https://s.w.org/images/core/emoji/72x72/1f603.png';
		$this->emoji[':D'] = 'https://s.w.org/images/core/emoji/72x72/1f600.png';
		$this->emoji[':$'] = 'https://s.w.org/images/core/emoji/72x72/1f60a.png';
		$this->emoji[':relaxed:'] = 'https://s.w.org/images/core/emoji/72x72/263a.png';
		$this->emoji[';)'] = 'https://s.w.org/images/core/emoji/72x72/1f609.png';
		$this->emoji[':heart_eyes:'] = 'https://s.w.org/images/core/emoji/72x72/1f60d.png';
		$this->emoji[':kissing_heart:'] = 'https://s.w.org/images/core/emoji/72x72/1f618.png';
		$this->emoji[':kissing_closed_eyes:'] = 'https://s.w.org/images/core/emoji/72x72/1f61a.png';
		$this->emoji[':kissing:'] = 'https://s.w.org/images/core/emoji/72x72/1f617.png';
		$this->emoji[':kissing_smiling_eyes:'] = 'https://s.w.org/images/core/emoji/72x72/1f619.png';
		$this->emoji[';P'] = 'https://s.w.org/images/core/emoji/72x72/1f61c.png';
		$this->emoji[':P'] = 'https://s.w.org/images/core/emoji/72x72/1f61b.png';
		$this->emoji[':stuck_out_tongue_closed_eyes:'] = 'https://s.w.org/images/core/emoji/72x72/1f61d.png';
		$this->emoji[':flushed:'] = 'https://s.w.org/images/core/emoji/72x72/1f633.png';
		$this->emoji[':grin:'] = 'https://s.w.org/images/core/emoji/72x72/1f601.png';
		$this->emoji[':apensive:'] = 'https://s.w.org/images/core/emoji/72x72/1f614.png';
		$this->emoji[':relieved:'] = 'https://s.w.org/images/core/emoji/72x72/1f60c.png';
		$this->emoji[':unamused'] = 'https://s.w.org/images/core/emoji/72x72/1f612.png';
		$this->emoji[':('] = 'https://s.w.org/images/core/emoji/72x72/1f61e.png';
		$this->emoji[':persevere:'] = 'https://s.w.org/images/core/emoji/72x72/1f623.png';
		$this->emoji[":'("] = 'https://s.w.org/images/core/emoji/72x72/1f622.png';
		$this->emoji[':joy:'] = 'https://s.w.org/images/core/emoji/72x72/1f602.png';
		$this->emoji[':sob:'] = 'https://s.w.org/images/core/emoji/72x72/1f62d.png';
		$this->emoji[':sleepy:'] = 'https://s.w.org/images/core/emoji/72x72/1f62a.png';
		$this->emoji[':disappointed_relieved:'] = 'https://s.w.org/images/core/emoji/72x72/1f625.png';
		$this->emoji[':cold_sweat:'] = 'https://s.w.org/images/core/emoji/72x72/1f630.png';
		$this->emoji[':sweat_smile:'] = 'https://s.w.org/images/core/emoji/72x72/1f605.png';
		$this->emoji[':sweat:'] = 'https://s.w.org/images/core/emoji/72x72/1f613.png';
		$this->emoji[':weary:'] = 'https://s.w.org/images/core/emoji/72x72/1f629.png';
		$this->emoji[':tired_face:'] = 'https://s.w.org/images/core/emoji/72x72/1f62b.png';
		$this->emoji[':fearful:'] = 'https://s.w.org/images/core/emoji/72x72/1f628.png';
		$this->emoji[':scream:'] = 'https://s.w.org/images/core/emoji/72x72/1f631.png';
		$this->emoji[':angry:'] = 'https://s.w.org/images/core/emoji/72x72/1f620.png';
		$this->emoji[':rage:'] = 'https://s.w.org/images/core/emoji/72x72/1f621.png';
		$this->emoji[':triumph'] = 'https://s.w.org/images/core/emoji/72x72/1f624.png';
		$this->emoji[':confounded:'] = 'https://s.w.org/images/core/emoji/72x72/1f616.png';
		$this->emoji[':laughing:'] = 'https://s.w.org/images/core/emoji/72x72/1f606.png';
		$this->emoji[':yum:'] = 'https://s.w.org/images/core/emoji/72x72/1f60b.png';
		$this->emoji[':mask:'] = 'https://s.w.org/images/core/emoji/72x72/1f637.png';
		$this->emoji[':cool:'] = 'https://s.w.org/images/core/emoji/72x72/1f60e.png';
		$this->emoji[':sleeping:'] = 'https://s.w.org/images/core/emoji/72x72/1f634.png';
		$this->emoji[':dizzy_face:'] = 'https://s.w.org/images/core/emoji/72x72/1f635.png';
		$this->emoji[':astonished:'] = 'https://s.w.org/images/core/emoji/72x72/1f632.png';
		$this->emoji[':worried:'] = 'https://s.w.org/images/core/emoji/72x72/1f61f.png';
		$this->emoji[':frowning:'] = 'https://s.w.org/images/core/emoji/72x72/1f626.png';
		$this->emoji[':anguished:'] = 'https://s.w.org/images/core/emoji/72x72/1f627.png';
		$this->emoji[':smiling_imp:'] = 'https://s.w.org/images/core/emoji/72x72/1f608.png';
		$this->emoji[':imp:'] = 'https://s.w.org/images/core/emoji/72x72/1f47f.png';
		$this->emoji[':open_mouth:'] = 'https://s.w.org/images/core/emoji/72x72/1f62e.png';
		$this->emoji[':grimacing:'] = 'https://s.w.org/images/core/emoji/72x72/1f62c.png';
		$this->emoji[':neutral_face:'] = 'https://s.w.org/images/core/emoji/72x72/1f610.png';
		$this->emoji[':confused:'] = 'https://s.w.org/images/core/emoji/72x72/1f615.png';
		$this->emoji[':hushed:'] = 'https://s.w.org/images/core/emoji/72x72/1f62f.png';
		$this->emoji[':no_mouth:'] = 'https://s.w.org/images/core/emoji/72x72/1f636.png';
		$this->emoji[':innocent:'] = 'https://s.w.org/images/core/emoji/72x72/1f607.png';
		$this->emoji[':smirk:'] = 'https://s.w.org/images/core/emoji/72x72/1f60f.png';
		$this->emoji[':expressionless:'] = 'https://s.w.org/images/core/emoji/72x72/1f611.png';

		$this->emoji = apply_filters( 'um_messaging_emoji', $this->emoji );
	}


	/**
	 * @param $user_id
	 * @return bool|array
	 */
	function get_perms( $user_id ) {
		if ( ! method_exists( UM()->roles(), 'role_data' ) )
			return false;

		$role = UM()->roles()->get_priority_user_role( $user_id );
		$role_data = apply_filters( 'um_user_permissions_filter', UM()->roles()->role_data( $role ), $user_id );

		return $role_data;
	}


	/**
	 * Blocked a user?
	 *
	 * @param $user_id
	 * @param bool $who_blocked
	 *
	 * @return bool
	 */
	function blocked_user( $user_id, $who_blocked = false ) {
		if ( ! $who_blocked ) {
			$who_blocked = get_current_user_id();
		}

		$blocked = get_user_meta( $who_blocked, '_pm_blocked', true );
		if ( is_array( $blocked ) && in_array( $user_id, $blocked ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Is it a hidden conversation?
	 *
	 * @param $conversation_id
	 *
	 * @return bool
	 */
	function hidden_conversation( $conversation_id ) {
		$hidden = (array) get_user_meta( get_current_user_id(), '_hidden_conversations', true );
		if ( in_array( $conversation_id, $hidden ) ) {
			return true;
		}
		return false;
	}


	/**
	 * Hides a conversation
	 *
	 * @param int $user_id
	 * @param int $conversation_id
	 */
	function hide_conversation( $user_id, $conversation_id ) {
		$hidden = (array) get_user_meta( $user_id, '_hidden_conversations', true );
		if ( ! in_array( $conversation_id, $hidden ) ) {
			$hidden[] = $conversation_id;
			update_user_meta( $user_id, '_hidden_conversations', $hidden );
		}
	}


	/**
	 * Can start messages?
	 *
	 * @param $recipient
	 * @return bool
	 */
	function can_message( $recipient ) {
		$can = true;

		if ( UM()->options()->get( 'pm_block_users' ) ) {
			$users = str_replace(' ', '', UM()->options()->get( 'pm_block_users' ) );
			$array = explode( ',', $users );
			if ( in_array( $recipient, $array ) || ( is_user_logged_in() && in_array( get_current_user_id(), $array ) ) ) {
				$can = false;
			}
		}

		$check_recipient = apply_filters( 'um_messaging_check_recipient_caps', true, $recipient );

		if ( $check_recipient ) {
			$role = UM()->roles()->get_priority_user_role( $recipient );
			$role_data = UM()->roles()->role_data( $role );
			$role_data = apply_filters( 'um_user_permissions_filter', $role_data, $recipient );
			if ( ! $role_data['enable_messaging'] || ! $role_data['can_read_pm'] ) {
				$can = false;
			}
		}

		if ( is_user_logged_in() ) {
			$role = UM()->roles()->get_priority_user_role( get_current_user_id() );
			$role_data = UM()->roles()->role_data( $role );
			$role_data = apply_filters( 'um_user_permissions_filter', $role_data, get_current_user_id() );
			if ( ! $role_data['enable_messaging'] ) {
				$can = false;
			}
		}

		if ( is_user_logged_in() &&
		     ( $this->blocked_user( $recipient ) || $this->blocked_user( get_current_user_id(), $recipient ) ) ) {
			$can = false;
		}

		$who_can_pm = get_user_meta( $recipient, '_pm_who_can', true );
		if ( $who_can_pm == 'nobody' ) {
			$can = false;
		}

		$custom_restrict = apply_filters( 'um_messaging_can_message_restrict', false, $who_can_pm, $recipient );
		if ( $custom_restrict ) {
			$can = false;
		}

		return $can;
	}


	/**
	 * Check if conversation has unread messages
	 *
	 * @param int $conversation_id
	 * @param int $user_id
	 * @return bool
	 */
	function unread_conversation( $conversation_id, $user_id ) {
		global $wpdb;

		$count = wp_cache_get( "um_unread_messages:$conversation_id:$user_id", 'um_messaging' );
		if ( false === $count ) {
			$count = $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT( message_id )
				FROM {$wpdb->prefix}um_messages
				WHERE conversation_id = %d AND
					  recipient = %d AND
					  status = 0",
				$conversation_id,
				$user_id
			) );
			wp_cache_set( "um_unread_messages:$conversation_id:$user_id", $count, 'um_messaging' );
		}

		return ( $count ) ? true : false;
	}


	/**
	 * Get conversations with unread messages
	 * @global wpdb $wpdb
	 * @param array $args
	 *	int 'reminded',
	 *	string 'reminded_rel',
	 *	int|string 'time_from',
	 *	int|string 'time_to'
	 * @return array
	 */
	function get_unread_conversations( $args = array() ){
		global $wpdb;

		$um_m_where = "`um_m`.`status` = 0";
		if( isset( $args['reminded'] ) && is_numeric( $args['reminded'] ) ){
			$rel = empty( $args['reminded_rel'] ) ? '=' : $args['reminded_rel'];
			$um_m_where .= " AND `um_m`.`reminded` $rel {$args['reminded']}";
		}
		if( !empty($args['time_from']) && !empty($args['time_to']) ){
			$from = is_numeric( $args['time_from'] ) ? date('Y-m-d H:i:s', $args['time_from']) : $args['time_from'];
			$to = is_numeric( $args['time_to'] ) ? date('Y-m-d H:i:s', $args['time_to']) : $args['time_to'];
			$um_m_where .= " AND (`um_m`.`time` BETWEEN '$from' AND '$to')";
		}else
		if( !empty($args['time_from']) ){
			$from = is_numeric( $args['time_from'] ) ? date('Y-m-d H:i:s', $args['time_from']) : $args['time_from'];
			$um_m_where .= " AND `um_m`.`time` > '$from'";
		}else
		if( !empty($args['time_to']) ){
			$to = is_numeric( $args['time_to'] ) ? date('Y-m-d H:i:s', $args['time_to']) : $args['time_to'];
			$um_m_where .= " AND `um_m`.`time` < '$to'";
		}

		$conversations = $wpdb->get_results( "
			SELECT *
			FROM `{$wpdb->prefix}um_messages` AS `um_m`
			WHERE $um_m_where
			GROUP BY `um_m`.`conversation_id`;" );

		return $conversations;
	}


	/**
	 * Get unread messages count
	 *
	 * @param int $user_id
	 * @return int
	 */
	function get_unread_count( $user_id ) {
		global $wpdb;

		$count = wp_cache_get( "um_unread_messages:{$user_id}", 'um_messaging' );
		if ( false === $count ) {

			$blocked = get_user_meta( $user_id, '_pm_blocked', true );
			$blocked = is_array( $blocked ) ? array_filter( $blocked, 'intval' ) : array();

			if ( count( $blocked ) ) {
				$count = $wpdb->get_var( $wpdb->prepare(
					"SELECT COUNT( message_id )
					FROM {$wpdb->prefix}um_messages
					WHERE recipient = %d AND
						  author NOT IN('" . implode( "','", $blocked ) . "') AND
						  status = 0
					LIMIT 11",
					$user_id
				) );
			} else {
				$count = $wpdb->get_var( $wpdb->prepare(
					"SELECT COUNT( message_id )
					FROM {$wpdb->prefix}um_messages
					WHERE recipient = %d AND
						  status = 0
					LIMIT 11",
					$user_id
				) );
			}

			wp_cache_set( "um_unread_messages:{$user_id}", $count, 'um_messaging' );
		}

		return intval( $count );
	}


	/**
	 * Remove a message
	 *
	 * @param $message_id
	 * @param $conversation_id
	 */
	function remove_message( $message_id, $conversation_id ) {
		global $wpdb;

		$user_id = get_current_user_id();

		$recipient_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT recipient
			FROM {$wpdb->prefix}um_messages
			WHERE conversation_id = %d AND
				  message_id = %d AND
				  author = %d",
			$conversation_id,
			$message_id,
			$user_id
		) );

		$wpdb->delete(
			"{$wpdb->prefix}um_messages",
			array(
				'conversation_id'   => $conversation_id,
				'message_id'        => $message_id,
				'author'            => $user_id
			)
		);

		if ( ! empty( $recipient_id ) ) {
			wp_cache_delete( "um_unread_messages:{$conversation_id}:{$user_id}", 'um_messaging' );
			wp_cache_delete( "um_unread_messages:$user_id", 'um_messaging' );
			wp_cache_delete( "um_messages:$user_id", 'um_messaging' );
		}
		wp_cache_delete( "um_new_messages:{$conversation_id}", 'um_messaging' );
		wp_cache_delete( "um_conversation_messages_limit:{$conversation_id}", 'um_messaging' );
		wp_cache_delete( "um_conversation_messages:{$conversation_id}", 'um_messaging' );
		wp_cache_delete( "um_messages:all", 'um_messaging' );
	}


	/**
	 * Check whether limit reached for sending msg
	 *
	 * @return bool
	 */
	function limit_reached() {
		$this->perms = $this->get_perms( get_current_user_id() );

		$user_id = get_current_user_id();
		$msgs_sent = get_user_meta( $user_id, '_um_pm_msgs_sent', true );

		$last_pm = get_user_meta( $user_id, '_um_pm_last_send', true );

		$limit = $this->perms['pm_max_messages'];
		$limit_tf = $this->perms['pm_max_messages_tf'];

		if ( !$limit ) return false;

		if ( $limit_tf ) {

		$numDays = number_format( abs( $last_pm - current_time('timestamp', true ) ) /60/60/24, 2 );
		if ( $numDays > $limit_tf ) { // more than x day since last msg open it again
			delete_user_meta( $user_id, '_um_pm_last_send' );
			delete_user_meta( $user_id, '_um_pm_msgs_sent' );
		} else {

			if ( $msgs_sent >= $limit ) {
				return true;
			} else {
				return false;
			}

		}

		} else {

			if ( $msgs_sent >= $limit ) {
				return true;
			}

		}

		return false;
	}


	/**
	 * Conversation template
	 *
	 * @param int $message_to
	 * @param int $user_id
	 */
	function conversation_template( $message_to, $user_id ) {
		if ( file_exists( get_stylesheet_directory() . '/ultimate-member/templates/conversation.php' ) ) {
			include get_stylesheet_directory() . '/ultimate-member/templates/conversation.php';
		} else {
			include um_messaging_path . 'templates/conversation.php';
		}
	}


	/**
	 * Get conversations
	 *
	 * @param int $user_id
	 * @return array|null|object|string
	 */
	function get_conversations( $user_id) {
		global $wpdb;
		$unread_first = UM()->options()->get('pm_unread_first');
		$results = wp_cache_get( "um_conversations:{$user_id}", 'um_messaging' );
		$limit = 50;
		if ( false === $results ) {
			if( $unread_first == 1 ){
				$results = $wpdb->get_results( $wpdb->prepare(
					"SELECT um_c.*
					FROM {$wpdb->prefix}um_conversations um_c
					LEFT JOIN {$wpdb->prefix}um_messages um_m ON um_c.conversation_id = um_m.conversation_id AND 
						um_m.recipient = %d AND
						um_m.status = 0
					WHERE um_c.user_b = %d OR
						  um_c.user_a = %d 
					GROUP BY um_c.conversation_id
					ORDER BY um_m.status DESC, 
					         um_c.last_updated DESC
					LIMIT $limit",
					$user_id,
					$user_id,
					$user_id
				) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare(
					"SELECT *
				FROM {$wpdb->prefix}um_conversations
				WHERE user_a = %d OR
					  user_b = %d
				ORDER BY last_updated DESC
				LIMIT $limit",
					$user_id,
					$user_id
				) );
			}

			wp_cache_set( "um_conversations:{$user_id}", $results, 'um_messaging' );
		}

		if ( $results ) {
			foreach ( $results as $key => $result ) {
				if ( get_userdata( $result->user_b ) === false || get_userdata( $result->user_a ) === false ) {
					unset( $results[ $key ] );
				}
			}
			return $results;
		}

		return '';
	}


	/**
	 * Get a conversation ID
	 *
	 * @param int $user1
	 * @param int $user2
	 * @return null
	 */
	function get_conversation_id( $user1, $user2 ) {
		global $wpdb;

		$response = null;
		$conversation = wp_cache_get( "um_conversation:{$user1}:{$user2}", 'um_messaging' );
		if ( false === $conversation ) {
			$conversation = wp_cache_get( "um_conversation:{$user2}:{$user1}", 'um_messaging' );
		}
		if ( false === $conversation ) {
			$conversation = $wpdb->get_row( $wpdb->prepare(
				"SELECT conversation_id,
					last_updated
				FROM {$wpdb->prefix}um_conversations
				WHERE ( user_a = %d AND user_b = %d ) OR
					  ( user_a = %d AND user_b = %d )
				LIMIT 1",
				$user1,
				$user2,
				$user2,
				$user1
			) );
			wp_cache_set( "um_conversation:{$user1}:{$user2}", $conversation, 'um_messaging', 30 * MINUTE_IN_SECONDS );
		}

		if ( isset( $conversation->conversation_id ) ) {
			$response['conversation_id'] = $conversation->conversation_id;
			$response['last_updated'] = $conversation->last_updated;
		}

		return $response;
	}


	/**
	 * Get a conversation
	 *
	 * @param int $user1
	 * @param int $user2
	 * @param int $conversation_id
	 * @return null|string
	 */
	function get_conversation( $user1, $user2, $conversation_id ) {
		global $wpdb;

		$hidden_conversations = get_user_meta( $user2, '_hidden_conversations', true );
		$hidden_conversations = ! empty( $hidden_conversations ) ? $hidden_conversations : array();

		$loop_user = um_user( 'ID' );
		um_fetch_user( $user2 );

		ob_start();

		if ( in_array( $conversation_id, $hidden_conversations ) ) { ?>
			<span class="um-message-notice">
				<?php _e( 'This conversation is hidden.', 'um-messaging' ) ?>
			</span>
		<?php } else {

			if ( ! um_user( 'can_read_pm' ) ) {

				if ( um_user( 'can_start_pm' ) ) {
					// Get conversation ordered by time and show only 1000 messages
					$first_answer_id = $wpdb->get_var( $wpdb->prepare(
						"SELECT message_id
						FROM {$wpdb->prefix}um_messages
						WHERE conversation_id = %d AND
							  author = %d
						ORDER BY time ASC
						LIMIT 1",
						$conversation_id,
						$user1
					) );

					if ( ! empty( $first_answer_id ) ) {
						$messages = $wpdb->get_results( $wpdb->prepare(
							"SELECT *
							FROM {$wpdb->prefix}um_messages
							WHERE conversation_id = %d AND
								  author = %d AND
								  message_id < %d
							ORDER BY time ASC
							LIMIT 1000",
							$conversation_id,
							$user2,
							$first_answer_id
						) );

					} else {
						$messages = $wpdb->get_results( $wpdb->prepare(
							"SELECT *
							FROM {$wpdb->prefix}um_messages
							WHERE conversation_id = %d AND
								  author = %d
							ORDER BY time ASC
							LIMIT 1000",
							$conversation_id,
							$user2
						) );

					}

					foreach ( $messages as $message ) { ?>

						<div class="um-message-item read right_m" data-message_id="<?php echo esc_attr( $message->message_id ) ?>" data-conversation_id="<?php echo esc_attr( $message->conversation_id ) ?>">
							<div class="um-message-item-content"><?php echo $this->chatize( $message->content ) ?></div>
							<div class="um-clear"></div>
							<div class="um-message-item-metadata"><?php echo $this->beautiful_time( $message->time, 'right_m' ) ?></div>
							<div class="um-clear"></div>
							<a href="javascript:void(0);" class="um-message-item-remove um-message-item-show-on-hover um-tip-s" title="<?php esc_attr_e('Remove','um-messaging' ) ?>"></a>
						</div>
						<div class="um-clear"></div>

					<?php }
				} ?>
				<span class="um-message-notice">
					<?php _e( 'Your membership level does not allow you to view conversations.', 'um-messaging' ) ?>
				</span>
			<?php } else {
				$messages = wp_cache_get( "um_conversation_messages_limit:$conversation_id", 'um_messaging' );
				if ( false === $messages ) {
					// Get conversation ordered by time and show only 1000 messages
					$messages = $wpdb->get_results( $wpdb->prepare(
						"SELECT *
						FROM {$wpdb->prefix}um_messages
						WHERE conversation_id = %d
						ORDER BY time ASC LIMIT 1000",
						$conversation_id
					) );
					wp_cache_set( "um_conversation_messages_limit:$conversation_id", $messages, 'um_messaging' );
				}

				$update_query = false;
				foreach ( $messages as $message ) {

					$status = 'read';
					if ( $message->status == 0 && $user2 == get_current_user_id() ) {
						$update_query = true;
						$status = 'unread';
					}

					$class = 'left_m';
					$remove_msg = '';
					if ( $message->author == get_current_user_id() ) {
						$class = 'right_m';
						$remove_msg = '<a href="javascript:void(0);" class="um-message-item-remove um-message-item-show-on-hover um-tip-s" title="'. esc_attr__('Remove','um-messaging').'"></a>';
					} ?>

					<div class="um-message-item <?php echo esc_attr( $class . ' ' . $status ) ?>" data-message_id="<?php echo esc_attr( $message->message_id ) ?>" data-conversation_id="<?php echo esc_attr( $message->conversation_id ) ?>">
						<div class="um-message-item-content"><?php echo $this->chatize( $message->content ) ?></div>
						<div class="um-clear"></div>
						<div class="um-message-item-metadata"><?php echo $this->beautiful_time( $message->time, $class ) ?></div>
						<div class="um-clear"></div>
						<?php echo $remove_msg ?>
					</div>
					<div class="um-clear"></div>

				<?php }

				if ( $update_query ) {
					$wpdb->query( $wpdb->prepare(
						"UPDATE {$wpdb->prefix}um_messages
						SET status = 1
						WHERE conversation_id = %d AND
							  author = %d",
						$conversation_id,
						$user1
					) );

					//user2 because user1 is author not recipient
					wp_cache_delete( "um_conversation_messages_limit:{$conversation_id}", 'um_messaging' );
					wp_cache_delete( "um_conversation_messages:{$conversation_id}", 'um_messaging' );
					wp_cache_delete( "um_new_messages:{$conversation_id}", 'um_messaging' );
					wp_cache_delete( "um_unread_messages:{$conversation_id}:{$user2}", 'um_messaging' );
					wp_cache_delete( "um_unread_messages:$user2", 'um_messaging' );
					wp_cache_delete( "um_messages:$user2", 'um_messaging' );
					wp_cache_delete( "um_messages:all", 'um_messaging' );
				}
			}
		}

		um_fetch_user( $loop_user );

		$response = ob_get_clean();
		return $response;
	}


	/**
	 * Chatize a message content
	 *
	 * @param $content
	 *
	 * @return string
	 */
	function chatize( $content ) {
		$content = stripslashes( $content );

		// autolink
		$content = preg_replace('$(\s|^)(https?://[a-z0-9_./?=&-:]+)(?![^<>]*>)$i', '<a href="$2" target="_blank" rel="nofollow">$2</a> ', $content." ");
		$content = preg_replace('$(\s|^)(www\.[a-z0-9_./?=&-:]+)(?![^<>]*>)$i', '<a target="_blank" href="http://$2"  target="_blank" rel="nofollow">$2</a> ', $content." ");


		foreach( $this->emoji as $code => $val ) {
			if( strpos( $code, ')' ) !== false ){
				$code = str_replace(')','\)', $code );
			}

			if( strpos( $code, '(' ) !== false ){
				$code = str_replace('(','\(', $code );
			}

			if( strpos( $code, '$' ) !== false ){
				$code = str_replace('$','\$', $code );
			}

			if( strpos($content,':pensive:') !== false ){
				$content = str_replace(':pensive:', ':apensive:', $content );
			}

			$pattern = "~(?i)<a.*?</a>(*SKIP)(*F)|{$code}~";
			$content = preg_replace($pattern, '<img src="'.$val.'" alt="'.$code.'" title="'.$code.'" class="emoji" />', $content);

		}



		return nl2br( $content );
	}


	/**
	 * Nice time difference
	 *
	 * @param $from
	 * @param string $to
	 *
	 * @return mixed|void
	 */
	function human_time_diff( $from, $to = '' ) {
		if ( empty( $to ) ) {
			$to = time();
		}

		$diff = (int) abs( $to - $from );


		if ( $diff < 60 ) {
			$since = sprintf( __('%ss','um-messaging'), $diff );
		} elseif ( $diff < HOUR_IN_SECONDS ) {
			$mins = round( $diff / MINUTE_IN_SECONDS );
			if ( $mins <= 1 )
				$mins = 1;
			/* translators: min=minute */
			$since = sprintf( __('%sm','um-messaging'), $mins );
		} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
			$hours = round( $diff / HOUR_IN_SECONDS );
			if ( $hours <= 1 )
				$hours = 1;
			$since = sprintf( __('%sh','um-messaging'), $hours );
		} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
			$days = round( $diff / DAY_IN_SECONDS );
			if ( $days <= 1 )
				$days = 1;
			$since = sprintf( __('%sd','um-messaging'), $days );
		} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
			$weeks = round( $diff / WEEK_IN_SECONDS );
			if ( $weeks <= 1 )
				$weeks = 1;
			$since = sprintf( __('%sw','um-messaging'), $weeks );
		} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
			$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
			if ( $months <= 1 )
				$months = 1;
			$since = sprintf( __('%sm','um-messaging'), $months );
		} elseif ( $diff >= YEAR_IN_SECONDS ) {
			$years = round( $diff / YEAR_IN_SECONDS );
			if ( $years <= 1 )
				$years = 1;
			$since = sprintf( __('%sy','um-messaging'), $years );
		}

		return apply_filters( 'um_messaging_human_time_diff', $since, $diff, $from, $to );
	}


	/**
	 * Show time beautifully
	 *
	 * @param $time
	 * @param $pos
	 *
	 * @return string
	 */
	function beautiful_time( $time, $pos ) {
		$from_time_unix = strtotime( $time );
		$offset = get_option( 'gmt_offset' );
		$offset = apply_filters("um_messages_time_offset", $offset );

		$from_time = $from_time_unix - $offset * HOUR_IN_SECONDS;
		$from_time = apply_filters("um_messages_time_from", $from_time, $time );

		$current_time = current_time('timestamp') - $offset * HOUR_IN_SECONDS;
		$current_time = apply_filters("um_messages_current_time", $current_time );

		$nice_time = $this->human_time_diff( $from_time, $current_time  );
		$nice_time = apply_filters("um_messages_time_nice", $nice_time, $from_time, $current_time );

		$clean_date_time = date("F d, Y, h:i A", $from_time );
		$clean_date_time = apply_filters("um_messages_time_clean", $clean_date_time, $from_time );

        $pos = apply_filters("um_messages_time_position", $pos );

		if ( $pos == 'right_m' ) {
			return '<span class="um-message-item-time um-tip-e" title="" um-messsage-timestamp="'.$from_time.'" um-message-utc-time="'.$clean_date_time.'">' . $nice_time . '</span>';
		} else {
			return '<span class="um-message-item-time um-tip-w"  title="" um-messsage-timestamp="'.$from_time.'" um-message-utc-time="'.$clean_date_time.'">' . $nice_time . '</span>';
		}
	}


	/**
	 * Checks if user enabled email notification
	 *
	 * @param $user_id
	 *
	 * @return bool|int
	 */
	function enabled_email( $user_id ) {
		$_enable_new_pm = true;
		if ( get_user_meta( $user_id, '_enable_new_pm', true ) == 'yes' ) {
			$_enable_new_pm = 1;
		} elseif ( get_user_meta( $user_id, '_enable_new_pm', true ) == 'no' ) {
			$_enable_new_pm = 0;
		}
		return $_enable_new_pm;
	}


	/**
	 * Create a conversation between both parties
	 *
	 * @param int $user1
	 * @param int $user2
	 * @return bool|int|null|string
	 */
	function create_conversation( $user1, $user2 ) {
		global $wpdb;

		// Test for previous conversation
		$conversation_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT conversation_id
			FROM {$wpdb->prefix}um_conversations
			WHERE ( user_a = %d AND user_b = %d ) OR
				  ( user_a = %d AND user_b = %d )
			LIMIT 1",
			$user1,
			$user2,
			$user2,
			$user1
		) );

		um_fetch_user( $user2 );

		// Build new conversation
		if ( ! $conversation_id ) {

			if ( ! um_user( 'can_start_pm' ) ) {
				return false;
			}

			$wpdb->insert(
				"{$wpdb->prefix}um_conversations",
				array(
					'user_a'        => $user1,
					'user_b'        => $user2,
					'last_updated'  => current_time( 'mysql', true )
				)
			);

			$conversation_id = $wpdb->insert_id;

			do_action('um_after_new_conversation', $user1, $user2, $conversation_id );

		} else {

			$other_message = $wpdb->get_var( $wpdb->prepare(
				"SELECT message_id
				FROM {$wpdb->prefix}um_messages
				WHERE conversation_id = %d AND
					  author = %d
				ORDER BY time ASC
				LIMIT 1",
				$conversation_id,
				$user1
			) );

			if ( ! ( um_user( 'can_reply_pm' ) || ( um_user( 'can_start_pm' ) && empty( $other_message ) ) ) ) {
				return false;
			}

			$wpdb->update(
				"{$wpdb->prefix}um_conversations",
				array(
					'last_updated'  => current_time( 'mysql', true ),
				),
				array(
					'conversation_id'   => $conversation_id,
				)
			);

			do_action('um_after_existing_conversation', $user1, $user2, $conversation_id );
		}

		// Insert message
		$wpdb->insert(
			"{$wpdb->prefix}um_messages",
			array(
				'conversation_id'   => $conversation_id,
				'time'              => current_time( 'mysql' ),
				'content'           => strip_tags( $_POST['content'] ),
				'status'            => 0,
				'author'            => $user2,
				'recipient'         => $user1
			)
		);

		wp_cache_delete( 'um_conversations:all', 'um_messaging' );
		wp_cache_delete( "um_conversations:{$user1}", 'um_messaging' );
		wp_cache_delete( "um_conversations:{$user2}", 'um_messaging' );
		wp_cache_delete( "um_all_conversations:{$user1}", 'um_messaging' );
		wp_cache_delete( "um_all_conversations:{$user2}", 'um_messaging' );
		wp_cache_delete( "um_conversation:{$user1}:{$user2}", 'um_messaging' );
		wp_cache_delete( "um_conversation:{$user2}:{$user1}", 'um_messaging' );
		wp_cache_delete( "um_new_messages:{$conversation_id}", 'um_messaging' );
		wp_cache_delete( "um_conversation_messages_limit:{$conversation_id}", 'um_messaging' );
		wp_cache_delete( "um_conversation_messages:{$conversation_id}", 'um_messaging' );
		wp_cache_delete( "um_unread_messages:{$conversation_id}:{$user1}", 'um_messaging' );
		wp_cache_delete( "um_unread_messages:{$conversation_id}:{$user2}", 'um_messaging' );
		wp_cache_delete( 'um_messages:all', 'um_messaging' );
		wp_cache_delete( "um_unread_messages:$user1", 'um_messaging' );
		wp_cache_delete( "um_unread_messages:$user2", 'um_messaging' );
		wp_cache_delete( "um_messages:$user1", 'um_messaging' );
		wp_cache_delete( "um_messages:$user2", 'um_messaging' );

		$this->update_user( $user2 );

		$hidden = (array) get_user_meta( $user1, '_hidden_conversations', true );
		if ( in_array( $conversation_id, $hidden ) ) {
			$hidden = array_diff( $hidden, array( $conversation_id ) );
			update_user_meta( $user1, '_hidden_conversations', $hidden );
		}

		$hidden = (array) get_user_meta( $user2, '_hidden_conversations', true );
		if ( in_array( $conversation_id, $hidden ) ) {
			$hidden = array_diff( $hidden, array( $conversation_id ) );
			update_user_meta( $user2, '_hidden_conversations', $hidden );
		}

		do_action('um_after_new_message', $user1, $user2, $conversation_id );

		return $conversation_id;
	}


	/**
	 * Update user
	 *
	 * @param $user_id
	 */
	function update_user( $user_id ) {
		update_user_meta( $user_id, '_um_pm_last_send', current_time( 'timestamp' ) );
		$msgs_sent = get_user_meta( $user_id, '_um_pm_msgs_sent', true );
		update_user_meta( $user_id, '_um_pm_msgs_sent', (int) $msgs_sent + 1 );
	}


	/**
	 * Show available emoji
	 */
	function emoji() {
		if ( file_exists( get_stylesheet_directory() . '/ultimate-member/templates/emoji.php' ) ) {
			include get_stylesheet_directory() . '/ultimate-member/templates/emoji.php';
		} else {
			include um_messaging_path . 'templates/emoji.php';
		}
	}


	/**
	 * Hex to RGB
	 *
	 * @param $hex
	 *
	 * @return string
	 */
	function hex_to_rgb( $hex ) {
		list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
		return "$r, $g, $b";
	}


	/**
	 * @param $url
	 *
	 * @return bool|string
	 */
	public function set_redirect_to( $url ) {
		return ! empty( $_SESSION['um_social_login_redirect'] ) ? $_SESSION['um_social_login_redirect'] : ' ';
	}


	/**
	 * Unblock a user via AJAX
	 */
	function ajax_messaging_unblock_user() {
		UM()->check_ajax_nonce();

		if ( !isset( $_POST['user_id'] ) || !is_numeric( $_POST['user_id'] ) || !is_user_logged_in() ) {
			wp_send_json_error();
		}

		$blocked = (array) get_user_meta( get_current_user_id(), '_pm_blocked', true );
		if ( ! in_array( $_POST['user_id'] , $blocked ) ) {
			wp_send_json_error();
		}

		$blocked = array_diff($blocked, array( $_POST['user_id'] ) );
		update_user_meta( get_current_user_id(), '_pm_blocked', $blocked );

		wp_send_json_success();
	}


	/**
	 * block a user via AJAX
	 */
	function ajax_messaging_block_user() {
		UM()->check_ajax_nonce();

		if ( ! isset( $_POST['other_user'] ) || ! is_numeric( $_POST['other_user'] ) || ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		$blocked = (array)get_user_meta( get_current_user_id(), '_pm_blocked', true );
		$blocked[] = $_POST['other_user'];
		update_user_meta( get_current_user_id(), '_pm_blocked', $blocked );

		wp_send_json_success();
	}


	/**
	 * Delete a conversation via AJAX
	 */
	function ajax_messaging_delete_conversation() {
		global $wpdb;

		UM()->check_ajax_nonce();

		if ( ! isset( $_POST['conversation_id'] ) || ! is_numeric( $_POST['conversation_id'] ) || ! is_user_logged_in() ) {
			wp_send_json_error();
		}
		if ( ! isset( $_POST['other_user'] ) || ! is_numeric( $_POST['other_user'] ) || ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		$current_user = get_current_user_id();
		$other_user = sanitize_text_field( $_POST['other_user'] );

		$conversation = wp_cache_get( "um_conversation:$current_user:$other_user", 'um_messaging' );
		if ( false === $conversation ) {
			$conversation = wp_cache_get( "um_conversation:$other_user:$current_user", 'um_messaging' );
		}
		if ( false === $conversation ) {
			$conversation = $wpdb->get_row( $wpdb->prepare(
				"SELECT conversation_id,
						last_updated
					FROM {$wpdb->prefix}um_conversations
					WHERE ( user_a = %d AND user_b = %d ) OR
						  ( user_a = %d AND user_b = %d )
					LIMIT 1",
				$current_user,
				$other_user,
				$other_user,
				$current_user
			) );
			wp_cache_set( "um_conversation:$current_user:$other_user", $conversation, 'um_messaging', 30 * MINUTE_IN_SECONDS );
		}

		if ( empty( $conversation->conversation_id ) ) {
			wp_send_json_error();
		}

		$this->hide_conversation( get_current_user_id(), $conversation->conversation_id );
		wp_send_json_success();
	}


	/**
	 * Remove a message via AJAX
	 */
	function ajax_messaging_remove() {
		UM()->check_ajax_nonce();

		if ( !isset( $_POST['message_id'] ) || !is_numeric( $_POST['message_id'] ) || !is_user_logged_in() ) {
			wp_send_json_error();
		}
		if ( !isset( $_POST['conversation_id'] ) || !is_numeric( $_POST['conversation_id'] ) || !is_user_logged_in() ) {
			wp_send_json_error();
		}

		$this->remove_message( $_POST['message_id'], $_POST['conversation_id'] );

		wp_send_json_success();
	}


	/**
	 * Send a message via AJAX
	 */
	function ajax_messaging_send() {
		UM()->check_ajax_nonce();

		if ( ! isset( $_POST['message_to'] ) || ! is_numeric( $_POST['message_to'] ) || ! is_user_logged_in() ) {
			wp_send_json_error();
		}
		if ( ! isset( $_POST['content'] ) || trim( $_POST['content'] ) == '' ) {
			wp_send_json_error();
		}

		if ( ! UM()->Messaging_API()->api()->can_message( $_POST['message_to'] ) ) {
			wp_send_json_error();
		}

		// Create/Update conversation and add message
		$conversation_id = $this->create_conversation( $_POST['message_to'], get_current_user_id() );
		if ( empty( $conversation_id ) ) {
			wp_send_json_error();
		}

		$response = $this->get_conversation_id( $_POST['message_to'], get_current_user_id() );
		$output['conversation_id'] = $response['conversation_id'];
		$output['last_updated'] = $response['last_updated'];
		$output['messages'] = $this->get_conversation( $_POST['message_to'], get_current_user_id(), $conversation_id );
		$output['limit_hit'] = $this->limit_reached() ? 1 : 0;
		$output['chat_history_download'] = UM()->Messaging_API()->gdpr()->get_download_url( $response['conversation_id'] );

		wp_send_json_success( $output );
	}


	/**
	 * Login Modal
	 */
	function ajax_messaging_login_modal() {
		UM()->check_ajax_nonce();

		if ( is_user_logged_in() ) {
			wp_send_json_error();
		}

		$message_to = absint( $_POST['message_to'] );
		um_fetch_user( $message_to );

		$url = um_user_profile_url();

		$_SESSION['um_messaging_message_to'] = $message_to;
		$_SESSION['um_social_login_redirect'] = $url;

		ob_start(); ?>

		<div class="um-message-modal">

			<div class="um-message-header um-popup-header">
				<div class="um-message-header-left"><?php printf(__('%s Please login to message <strong>%s</strong>','um-messaging'), get_avatar( $message_to, 40 ), um_user('display_name') ); ?></div>
				<div class="um-message-header-right">
					<a href="#" class="um-message-hide"><i class="um-icon-android-close"></i></a>
				</div>
			</div>

			<div class="um-message-body um-popup-autogrow2 um-message-autoheight"></div>

		</div>

		<?php $output = ob_get_clean();
		wp_send_json_success( array( 'content' => $output, 'redirect_url' => $url ) );
	}


	/**
	 * Coming from send message button
	 */
	function ajax_messaging_start() {
		UM()->check_ajax_nonce();

		if ( ! isset( $_POST['message_to'] ) || ! is_numeric( $_POST['message_to'] ) || ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		ob_start(); ?>

		<div class="um-message-modal">
			<?php $this->conversation_template( $_POST['message_to'], get_current_user_id() ); ?>
		</div>

		<?php $output = ob_get_clean();
		wp_send_json_success( $output );
	}


	/**
	 * Auto refresh of chat messages
	 */
	function ajax_messaging_update() {
		global $wpdb;

		UM()->check_ajax_nonce();

		$output["errors"] = array();
		if ( ! isset( $_POST['message_to'] ) || ! is_numeric( $_POST['message_to'] ) || ! is_user_logged_in() ) {
			wp_send_json_error( esc_js( __( "Invalid target user_ID or user is not logged in", 'um-messaging' ) ) );
		}

		um_fetch_user( get_current_user_id() );
		if ( ! um_user( 'can_read_pm' ) ) {

			$output['response'] = 'nothing_new';

		} else {
			$conversation_id = absint( $_POST['conversation_id'] );
			$last_update_query = $wpdb->prepare(
				"SELECT last_updated
				FROM {$wpdb->prefix}um_conversations
				WHERE conversation_id = %d
				LIMIT 1",
				$conversation_id
			);
			$results = $wpdb->get_results( $last_update_query );

			if ( $wpdb->num_rows <= 0 ) {

				wp_send_json_error( esc_js( sprintf( __( "UM Messaging - invalid query: %s", 'um-messaging' ), $last_update_query ) ) );

			} else {
				if ( ! $results[0]->last_updated ) {
					wp_send_json_error( esc_js( __( "UM Messaging - No result found", 'um-messaging' ) ) );
				}

				$output['debug']['last_updated_from_query'] = $results[0]->last_updated;
				$output['debug']['last_updated_from_post'] = $_POST['last_updated'];
				$output['debug']['last_updated'] = ( strtotime( $results[0]->last_updated ) > strtotime( $_POST['last_updated'] ) ? true : false );

				if ( strtotime( $results[0]->last_updated ) > strtotime( $_POST['last_updated'] ) ) {

					$last_updated = $_POST['last_updated'];

					// get new messages
					$messages_query = $wpdb->prepare(
						"SELECT *
						FROM {$wpdb->prefix}um_messages as tn2
						WHERE tn2.conversation_id = %d AND
							  tn2.time > %s
						ORDER BY tn2.time ASC",
						$conversation_id,
						$last_updated
					);

					$messages = wp_cache_get( "um_new_messages:{$conversation_id}", 'um_messaging' );
					if ( false === $messages ) {
						$messages = $wpdb->get_results( $messages_query );
						wp_cache_set( "um_new_messages:{$conversation_id}", $messages, 'um_messaging' );
					}

					$output['debug']['messages_query'] = $messages_query;
					$output['debug']['messages_query_results'] = $messages;
					$output['debug']['messages_query_num_rows'] = $wpdb->num_rows;

					$response = null;
					foreach ( $messages as $message ) {

						if ( $message->status == 0 ) {
							$status = 'unread';
						} else {
							$status = 'read';
						}

						if ( $message->author == get_current_user_id() ) {
							$class = 'right_m';
							$remove_msg = '<a href="#" class="um-message-item-remove um-message-item-show-on-hover um-tip-s" title="'. esc_attr__( 'Remove', 'um-messaging' ).'"></a>';
						} else {
							$class = 'left_m';
							$remove_msg = '';
						}

						$response .= '<div class="um-message-item ' . $class . ' ' . $status . '" data-message_id="'.$message->message_id.'" data-conversation_id="'.$message->conversation_id.'">';

						$response .= '<div class="um-message-item-content">' . $this->chatize( $message->content ) . '</div><div class="um-clear"></div>';

						$response .= '<div class="um-message-item-metadata">' . $this->beautiful_time( $message->time, $class ) . '</div><div class="um-clear"></div>';

						$response .= $remove_msg;

						$response .= '</div><div class="um-clear"></div>';

						$output['message_id'] = $message->message_id;
						$output['last_updated'] = $message->time;
					}

					$output['response'] = $response;
				} else {

					$output['response'] = 'nothing_new';

				}
			}
		}

		wp_send_json_success( $output );
	}


	/**
	 * AJAX Pagination
	 */
	function ajax_conversations_load() {
		UM()->check_ajax_nonce();

		global $wpdb;
		$user_id = $_POST['user_id'];
		$offset = $_POST['offset'];
		$unread_first = UM()->options()->get( 'pm_unread_first' );

		if ( $unread_first == 1 ) {
			$conversations = $wpdb->get_results( $wpdb->prepare(
				"SELECT um_c.*
				FROM {$wpdb->prefix}um_conversations um_c
				LEFT JOIN {$wpdb->prefix}um_messages um_m ON um_c.conversation_id = um_m.conversation_id AND 
					um_m.recipient = %d AND
					um_m.status = 0
				WHERE um_c.user_b = %d OR
					  um_c.user_a = %d
				GROUP BY um_c.conversation_id
				ORDER BY um_m.status DESC, 
				         um_c.last_updated DESC
				LIMIT 20 OFFSET $offset",
				$user_id,
				$user_id,
				$user_id
			) );
		} else {
			$conversations = $wpdb->get_results( $wpdb->prepare(
				"SELECT *
				FROM {$wpdb->prefix}um_conversations
				WHERE user_a = %d OR
					  user_b = %d
				ORDER BY last_updated DESC
				LIMIT 20 OFFSET $offset",
				$user_id,
				$user_id
			) );
		}

		$output = '';
		$profile_can_read = um_user( 'can_read_pm' );
		if ( ! empty( $conversations ) ) {
			foreach ( $conversations as $conversation ) {
				if ( $conversation->user_a == um_profile_id() ) {
					$user = $conversation->user_b;
				} else {
					$user = $conversation->user_a;
				}
				um_fetch_user( $user );

				$user_name = ( um_user( 'display_name' ) ) ? um_user( 'display_name' ) : __( 'Deleted User', 'um-messaging' );

				$is_unread = UM()->Messaging_API()->api()->unread_conversation( $conversation->conversation_id, um_profile_id() );

				$output .= '<a href="' . add_query_arg('conversation_id', $conversation->conversation_id) . '" class="um-message-conv-item" data-message_to="' . $user . '" data-trigger_modal="conversation" data-conversation_id="' . $conversation->conversation_id . '">';
				$output .= '<span class="um-message-conv-name">' . $user_name . '</span>';
				$output .= '<span class="um-message-conv-pic">' . get_avatar($user, 40) . '</span>';

				if ( $is_unread && $profile_can_read ) {
					$output .= '<span class="um-message-conv-new"><i class="um-faicon-circle"></i></span>';
				}
				if ( class_exists('UM_Online_API') ) {
					if ( UM('UM_Online_API')->is_online( um_user('ID') ) ) {
						$output .= '<span class="um-online-status online"><i class="um-faicon-circle"></i></span>';
					} else {
						$output .= '<span class="um-online-status offline"><i class="um-faicon-circle"></i></span>';
					}
				}
				$output .= '</a>';
			}
		} else {
			$output = '';
		}

		wp_send_json_success( $output );
	}
}