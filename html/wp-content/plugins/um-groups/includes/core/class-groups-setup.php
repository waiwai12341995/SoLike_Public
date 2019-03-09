<?php
namespace um_ext\um_groups\core;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Groups_Setup
 * @package um_ext\um_groups\core
 */
class Groups_Setup {


	/**
	 * @var array
	 */
	var $settings_defaults;


	/**
	 * @var
	 */
	var $global_actions;


	/**
	 * Groups_Setup constructor.
	 */
	function __construct() {
		global $wpdb;

		$this->global_actions['status']                 = __('New wall post','um-activity');
		$this->global_actions['new-user']               = __('New user','um-activity');
		$this->global_actions['new-post']               = __('New blog post','um-activity');
		$this->global_actions['new-product']            = __('New product','um-activity');
		$this->global_actions['new-gform']              = __('New Gravity From','um-activity');
		$this->global_actions['new-gform-submission']   = __('New Gravity From Answer','um-activity');
		$this->global_actions['new-follow']             = __('New follow','um-activity');
		$this->global_actions['new-topic']              = __('New forum topic','um-activity');

		$this->db_groups_table = $wpdb->prefix . 'um_groups_members';

		 //settings defaults
		$this->settings_defaults = array(

			// Join Request - Email template
			'groups_join_request_on'        => 1,
			'groups_join_request_sub'       => '{site_name} - Join Request',
			'groups_join_request'           => 'Hi {display_name},<br /><br />' .
						'{display_name} has requested to join {group_name}. You can view their profile here: {profile_link}<br /><br />' .
						'To approve/reject this request please click the following link: {groups_request_tab_url}<br /><br />',

			// Request Approved - Email Template
			'groups_approve_member_on'      => 1,
			'groups_approve_member_sub'     => '{site_name} - Your request to join {group_name} has been approved.',
			'groups_approve_member'         => 'Your request to join {group_name} has been approved.<br /><br />' .
						'{group_url}',

			// Invited - Email Template
			'groups_invite_member_on'       => 1,
			'groups_invite_member_sub'      => '{site_name} - You have been invited to join {group_name}',
			'groups_invite_member'          => 'Hi {group_invitation_host_name},<br /><br />'.
							'{group_invitation_guest_name} has invited you to join {group_name}.<br /><br />'.
							'To confirm/reject this invitation please click the following link: {group_url}',

			'groups_invite_people'          => 'everyone',
			'groups_show_avatars'           => 1,

			// Discussion settings
			'groups_posts_num'              => 10,
			'groups_posts_num_mob'          => 5,
			'groups_init_comments_count'    => 2,
			'groups_load_comments_count'    => 10,
			'groups_order_comment'          => 'asc',
			'groups_post_truncate'          => 25,
			'groups_enable_privacy'         => 1,
			'groups_trending_days'          => 7,
			'groups_require_login'          => 0,
			'groups_need_to_login'          => sprintf( __( 'Please <a href="%s" class="um-link">sign up</a> or <a href="%s" class="um-link">sign in</a> to like or comment on this post.','um-activity'),  add_query_arg( 'redirect_to', '{current_page}', um_get_core_page('register') ), add_query_arg( 'redirect_to', '{current_page}', um_get_core_page('login') ) ),

		);


		// Real-time notification integration's default logs
		foreach ( $this->get_log_types() as $k => $desc ) {
			$this->settings_defaults[ 'log_' . $k ] = 1;
			$this->settings_defaults[ 'log_' . $k . '_template' ] = $desc['template'];
		}

		foreach ( apply_filters( 'um_groups_discussion_global_actions', $this->global_actions ) as $k => $v ) {
			if ( $k == 'status' ) {
				continue;
			}

			$this->settings_defaults[ 'groups-discussion-' . $k ] = 1;
		}

	}


	/**
	 * Get default notification log templates
	 * @return array
	 */
	function get_log_types() {
		$array = array();

		$array['groups_approve_member'] = array(
			'title'         => __( 'Groups - Approve Member', 'um-groups' ),
			'template'      => __( 'Your request to join {group_name} have been approved.', 'um-groups' ),
			'account_desc'  => __( 'When my group requests have been approved', 'um-groups' ),
		);


		$array['groups_join_request'] = array(
			'title'         => __( 'Groups - Join Request', 'um-groups' ),
			'template'      => __( '{member_name} has requested to join {group_name}.', 'um-groups' ),
			'account_desc'  => __( 'When a user requested to join their group', 'um-groups' ),
		);


		$array['groups_invite_member'] = array(
			'title'         => __( 'Groups - Invite Member', 'um-groups' ),
			'template'      => __( '{group_invitation_host_name} has invited you to join {group_name}.', 'um-groups' ),
			'account_desc'  => __( 'When a member has invited to join a group', 'um-groups' ),
		);

		$array['groups_change_role'] = array(
			'title'         => __( 'Groups - Change Group Role', 'um-groups' ),
			'template'      => __( 'Your group role {group_role_old} has been changed to {group_role_new} in {group_name}.', 'um-groups' ),
			'account_desc'  => __( 'When my group roles have been changed', 'um-groups' ),
		);

		return $array;
	}


	/**
	 * Set default settings
	 */
	function set_default_settings() {
		$options = get_option( 'um_options' );
		$options = empty( $options ) ? array() : $options;

		foreach ( $this->settings_defaults as $key => $value ) {
			//set new options to default
			if ( ! isset( $options[$key] ) )
				$options[$key] = $value;

		}

		update_option( 'um_options', $options );
	}


	/**
	 * Page Setup
	 */
	public function page_setup() {

		$version = get_option( 'um_groups_version' );

		if ( ! $version ) {
			$options = get_option( 'um_options' );
			$options = empty( $options ) ? array() : $options;

			 //only on first install
			$create_group_exists = UM()->query()->find_post_id( 'page', '_um_core', 'create_group' );
			$my_group_exists = UM()->query()->find_post_id( 'page', '_um_core', 'my_groups' );
			$groups_exists = UM()->query()->find_post_id( 'page', '_um_core', 'groups' );


			if ( ! $groups_exists ) {

				// All Groups
				$all_groups = array(
					'post_title'    => 'Groups',
					'post_content'  => '[ultimatemember_groups]',
					'post_status'   => 'publish',
					'post_author'   => get_current_user_id(),
					'post_type'     => 'page'
				);

				$post_id = wp_insert_post( $all_groups );

				if ( $post_id ){
					update_post_meta( $post_id, '_um_core', 'groups');
					$key = UM()->options()->get_core_page_id( 'groups' );
					$options[ $key ] = $post_id;
				}

			}


			if ( ! $create_group_exists ) {

				// Create New Group
				$new_groups  = array(
					'post_title'    => 'Create New Group',
					'post_content'  => '[ultimatemember_group_new]',
					'post_status'   => 'publish',
					'post_author'   => get_current_user_id(),
					'post_type'     => 'page'
				);

				$post_id = wp_insert_post( $new_groups  );

				if ( $post_id ){
					update_post_meta( $post_id, '_um_core', 'create_group');
					$key = UM()->options()->get_core_page_id( 'create_group' );
					$options[ $key ] = $post_id;
				}
			}


			if ( ! $groups_exists ) {

				// My Groups
				$my_groups  = array(
					'post_title'    => 'My Groups',
					'post_content'  => '[ultimatemember_my_groups]',
					'post_status'   => 'publish',
					'post_author'   => get_current_user_id(),
					'post_type'     => 'page'
				);

				$post_id = wp_insert_post( $my_groups );

				if ( $post_id ) {
					update_post_meta( $post_id, '_um_core', 'my_groups' );
					$key = UM()->options()->get_core_page_id( 'my_groups' );
					$options[ $key ] = $post_id;
				}

			}

			update_option( 'um_options', $options );

		}

	}


	/**
	 * SQL Setup
	 */
	public function sql_setup() {
		global $wpdb;

		if ( ! current_user_can('manage_options') ) {
			return;
		}

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $this->db_groups_table;

		$sql = "CREATE TABLE {$table_name} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			group_id mediumint(9) NOT NULL,
			user_id1 mediumint(9) NOT NULL,
			user_id2 mediumint(9) NOT NULL,
			status varchar(30) NOT NULL,
			role varchar(30) NOT NULL,
			invites mediumint(9) NOT NULL,
			time_stamp timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			date_joined datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$sql_queries = dbDelta( $sql );
	}


	/**
	 *
	 */
	function run_setup() {
		$this->sql_setup();
		$this->page_setup();
		$this->set_default_settings();
	}
}