<?php
namespace um_ext\um_profile_completeness\core;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Profile_Completeness_Restrict
 * @package um_ext\um_profile_completeness\core
 */
class Profile_Completeness_Restrict {


	/**
	 * Profile_Completeness_Restrict constructor.
	 */
	function __construct() {
		add_action( 'template_redirect', array( &$this, 'template_redirect' ), 999999999 );

		add_action( 'wp_insert_comment', array( &$this, 'wp_insert_comment' ), 9999999 );

		add_filter( 'bbp_new_topic_pre_extras', array( &$this, 'forum_restrict' ), 9999999 );
		add_filter( 'bbp_new_reply_pre_extras', array( &$this, 'forum_restrict' ), 9999999 );
	}


	/**
	 * ACCESS / PROFILES
	 */
	function template_redirect() {
		if ( ! is_user_logged_in() || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$result = UM()->Profile_Completeness_API()->shortcode()->profile_progress( get_current_user_id() );
		$exclude_page = apply_filters( "um_profile_completeness_restrict_page", um_is_core_page('account') );

		if ( ! $exclude_page ) {

			if ( $result['req_progress'] > $result['progress'] ) {
				// Global
				if ( $result['prevent_browse'] && ! isset( $_REQUEST['um_action'] ) ) {
					$edit_profile_url = add_query_arg( 'notice', 'incomplete_access', um_get_core_page( 'user' ) );
					$edit_profile_url = add_query_arg( 'um_action', 'edit', $edit_profile_url );
					$edit_profile_url = add_query_arg( 'profiletab', 'main', $edit_profile_url );

					exit( wp_redirect( $edit_profile_url ) );
				}

				// Profile view
				if ( $result['prevent_profileview'] ) {
					if ( um_get_requested_user() && um_get_requested_user() != get_current_user_id() ) {
						$edit_profile_url = add_query_arg( 'notice', 'incomplete_view', um_get_core_page( 'user' ) );
						$edit_profile_url = add_query_arg( 'um_action', 'edit', $edit_profile_url );
						$edit_profile_url = add_query_arg( 'profiletab', 'main', $edit_profile_url );
						exit( wp_redirect( $edit_profile_url ) );
					}
				}

			}
		}

	}


	/**
	 * COMMENTS
	 *
	 * @param $cid
	 */
	function wp_insert_comment( $cid ) {
		if ( ! is_user_logged_in() || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$comment = get_comment( $cid );
		if ( get_post_type( $comment->comment_post_ID ) == "um_groups_discussion" ) {
			return;
		}

		//shouldn't affect group discussion comment
		$result = UM()->Profile_Completeness_API()->shortcode()->profile_progress( get_current_user_id() );
		if ( $result['progress'] < $result['req_progress'] ) {

			if ( $result['prevent_comment'] ) {
				wp_delete_comment( $cid, true );
				exit( wp_redirect( add_query_arg( 'notice', 'incomplete_comment', um_edit_profile_url() ) ) );
			}
		}
	}


	/**
	 * bbPress
	 *
	 * @param $forum_id
	 */
	function forum_restrict( $forum_id ) {
		if ( ! is_user_logged_in() || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$result = UM()->Profile_Completeness_API()->shortcode()->profile_progress( get_current_user_id() );
		if ( $result['progress'] < $result['req_progress'] ) {
			if ( $result['prevent_bb'] ) {
				exit( wp_redirect( add_query_arg( 'notice', 'incomplete_forum', um_edit_profile_url() ) ) );
			}
		}
	}
}