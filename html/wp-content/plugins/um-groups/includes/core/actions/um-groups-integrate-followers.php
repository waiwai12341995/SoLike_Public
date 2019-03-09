<?php if ( ! defined( 'ABSPATH' ) ) exit;
add_action("um_groups_users_list_after_details","um_groups_followers_user_list_details", 11, 4);
function um_groups_followers_user_list_details( $user_id, $group_id, $menus, $has_joined ){

		if( $user_id == get_current_user_id() ) return;
		if( ! class_exists("UM_Followers_API") ) return;

		$followed = UM()->Followers_API()->api()->followed( get_current_user_id(), $user_id );	
			
		if( ! $followed ) return;

	   echo "<li>".__("Follows you","um-groups")."</li>";

}