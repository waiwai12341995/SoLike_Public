<?php if ( ! defined( 'ABSPATH' ) ) exit;
add_action("um_groups_users_list_after_details","um_groups_friends_user_list_details", 10, 4);
function um_groups_friends_user_list_details( $user_id, $group_id, $menus, $has_joined ){

	if( $user_id == get_current_user_id() ) return;
	if( ! class_exists("UM_Friends_API") ) return;
	
	$is_friend = UM()->Friends_API()->api()->is_friend( get_current_user_id(), $user_id );
	if( ! $is_friend ) return;
	
	echo "<li>".__("Friend","um-groups")."</li>";

}