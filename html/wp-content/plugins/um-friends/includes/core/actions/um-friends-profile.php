<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add button in cover
 *
 * @param $args
 */
function um_friends_add_button( $args ) {
	if ( $args['cover_enabled'] == 1 ) {
		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		$user_id = um_profile_id();
		echo '<div class="um-friends-coverbtn">' . UM()->Friends_API()->api()->friend_button( $user_id, get_current_user_id() ) . '</div>';
	}
}
add_action( 'um_before_profile_main_meta', 'um_friends_add_button' );


/**
 * Add button in case that cover is disabled
 *
 * @param $args
 */
function um_friends_add_button_nocover( $args ) {
	wp_enqueue_script( 'um_friends' );
	wp_enqueue_style( 'um_friends' );

	$user_id = um_profile_id();
	if ( $args['cover_enabled'] != 1 ) {
		echo '<div class="um-friends-nocoverbtn" style="display: block">' . UM()->Friends_API()->api()->friend_button( $user_id, get_current_user_id() ) . '</div>';
	} else {
		echo '<div class="um-friends-nocoverbtn" style="display: none">' . UM()->Friends_API()->api()->friend_button( $user_id, get_current_user_id() ) . '</div>';
	}
}
add_action('um_after_profile_header_name_args', 'um_friends_add_button_nocover', 90, 1 );


/**
 * Add friendship state
 *
 * @param $args
 */
function um_friends_add_state( $args ) {
	wp_enqueue_script( 'um_friends' );
	wp_enqueue_style( 'um_friends' );

	if ( ! is_user_logged_in() || ! um_profile_id() ) {
		return;
	}

	if ( get_current_user_id() == um_profile_id() ) {
		return;
	}

	if ( UM()->Friends_API()->api()->is_friend( get_current_user_id(), um_profile_id() ) ) {
		echo '<span class="um-friend-you"></span>';
	}

}
add_action( 'um_after_profile_name_inline', 'um_friends_add_state', 200 );


/**
 * Friends List
 *
 * @param $args
 */
function um_profile_content_friends_default( $args ) {
	echo do_shortcode('[ultimatemember_friends user_id="'.um_profile_id().'"]');
}
add_action( 'um_profile_content_friends_default', 'um_profile_content_friends_default' );
add_action( 'um_profile_content_friends_myfriends', 'um_profile_content_friends_default' );


/**
 * Friend requests List
 *
 * @param $args
 */
function um_profile_content_friends_friendreqs( $args ) {
	echo do_shortcode('[ultimatemember_friend_reqs user_id="'.um_profile_id().'"]');
}
add_action( 'um_profile_content_friends_friendreqs', 'um_profile_content_friends_friendreqs' );


/**
 * Friend requests sent List
 *
 * @param $args
 */
function um_profile_content_friends_sentreqs( $args ) {
	echo do_shortcode('[ultimatemember_friend_reqs_sent user_id="'.um_profile_id().'"]');
}
add_action( 'um_profile_content_friends_sentreqs', 'um_profile_content_friends_sentreqs' );

/***
 ***	@customize the nav bar
 ***/
//add_action('um_profile_navbar', 'um_friends_add_profile_bar', 4 );
/*function um_friends_add_profile_bar( $args ) {
		echo do_shortcode('[ultimatemember_friends_bar user_id='.um_profile_id().']');
	}*/