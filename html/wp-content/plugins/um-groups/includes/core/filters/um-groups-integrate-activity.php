<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Email template placeholders
 */
add_filter("um_activity_search_tpl","um_groups_activity_search_tpl", 10 );
function um_groups_activity_search_tpl( $search ){

	$search[ ] = "{group_author_profile}";
	$search[ ] = "{group_author_name}";
	$search[ ] = "{group_permalink}";
	$search[ ] = "{group_name}";
	

	return $search;
}

/**
 * Search and Replace Email template placeholders
 */
add_filter("um_activity_replace_tpl","um_groups_activity_replace_tpl", 10, 2 );
function um_groups_activity_replace_tpl( $replace, $array ){

	$replace[ ] = isset( $array['group_author_profile'] ) ? $array['group_author_profile'] : '';
	$replace[ ] = isset( $array['group_author_name'] ) ? $array['group_author_name'] : '';
	$replace[ ] = isset( $array['group_permalink'] ) ? $array['group_permalink'] : '';
	$replace[ ] = isset( $array['group_name'] ) ? $array['group_name'] : '';
			
	return $replace;
}

/**
 * Activity options
 */
add_filter("um_activity_global_actions","um_groups_ctivity_global_actions", 10, 1 );
function um_groups_ctivity_global_actions( $actions ){

	
	$actions['new-group'] = __("New group","um-groups");

	return $actions;
}