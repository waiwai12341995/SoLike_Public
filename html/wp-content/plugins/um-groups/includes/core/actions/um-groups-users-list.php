<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Invite Front - After details 
 */
add_action("um_groups_users_list_after_details__invite_front","um_groups_users_list_after_details__invite_front", 10, 5);
function um_groups_users_list_after_details__invite_front( $user_id, $group_id, $member, $menus, $has_joined = '' ){
	if( 'approved' == $has_joined  ){
		echo "<li>";
		_e("Already a member", "um-groups");
		echo "</li>";
	}


	if( 'blocked' == $has_joined  ){
		echo "<li>";
		_e("This user has been blocked", "um-groups");
		echo "</li>";
	}
}

add_action("um_groups_users_list_after_details__approved","um_groups_users_list_after_details__approved", 10, 5);
function um_groups_users_list_after_details__approved( $user_id, $group_id, $member, $menus, $has_joined = '' ){
	
}