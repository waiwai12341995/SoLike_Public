<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Change group single header title
 * @param  string $title 
 * @return string        
 */
add_filter( 'the_title', 'um_groups_single_change_page_title' );
function um_groups_single_change_page_title( $title ){

	if ( in_the_loop() && 'um_groups' == get_post_type() && is_single() ){
		UM()->Groups()->api()->single_group_title = $title;
		
		return '';
	}

	return $title;
}

/**
 * Add group profile form
 */
add_filter( 'the_content', 'um_groups_single_remove_content' );
function um_groups_single_remove_content( $content ){
	
	if( 'um_groups' == get_post_type() && is_single() ){
		$content = do_shortcode('[ultimatemember_group_single]');
	}

	return $content;
}

/**
 * Remove thumbnail in single query post
 */
add_filter('post_thumbnail_html','um_groups_single_remove_thumbnail');
function um_groups_single_remove_thumbnail( $html ){

	if( 'um_groups' == get_post_type() && is_single() ){
		$html = '';
	}
	
	return $html;
}

/**
 * Add query variables
 */
add_filter( 'query_vars', 'um_groups_query_vars_filter' );
function um_groups_query_vars_filter( $vars ){
	$vars[ ] = "tab";
	$vars[ ] = "sub";
	$vars[ ] = "updated";
	$vars[ ] = "show";
 	return $vars;
}

/**
 * Add Discussion tab
 */
add_filter('um_groups_tabs','um_groups_tab_discussion', 10, 3 );
function um_groups_tab_discussion( $default_tabs, $group_id, $param_tab ){

	$default_tabs['discussion'] = array(
		'slug' => 'discussion',
		'name' => __('Discussions','um-groups'),
		'default' => true,
	);

	return $default_tabs;
}

/**
 * Add Member tab
 */
add_filter('um_groups_tabs','um_groups_tab_member', 10, 3 );
function um_groups_tab_member( $default_tabs, $group_id, $param_tab ){
				
	$default_tabs['members'] = array(
		'slug' => 'members',
		'name' => __('Members','um-groups'),
	);

	return $default_tabs;
}

/**
 * Add Settings tab
 */
add_filter('um_groups_tabs','um_groups_tab_settings', 99, 3 );
function um_groups_tab_settings( $default_tabs, $group_id, $param_tab ){

	if( is_user_logged_in() ){
		$default_tabs['settings'] = array(
			'slug' => 'settings',
			//'name' => '<i class="um-faicon-gear"></i> '.__('Settings','um-groups'),
			'name' => '<i class="um-faicon-gear um-tip-s" original-title="'.__('Edit group settings','um-groups').'"></i> ',
			'default_sub' => 'details'
		);
	}

	return $default_tabs;
}	

/**
 * Add Requests tab
 */
add_filter('um_groups_tabs','um_groups_tab_requests', 10, 3 );
function um_groups_tab_requests( $default_tabs, $group_id, $param_tab ){

	$count = um_groups_get_join_request_count_by_admin( $group_id, true );
	$has_joined = UM()->Groups()->api()->has_joined_group( get_current_user_id(), $group_id );
 	
 	if( $count > 0 && 'approved' == $has_joined ){
		$privacy = UM()->Groups()->api()->get_privacy_slug( $group_id );
		if( UM()->Groups()->api()->can_approve_requests( $group_id ) ){
			
			$default_tabs['requests'] = array(
							'slug' => 'requests',
							'name' => ''
			);

			if( UM()->Groups()->api()->show_tab_count_notification( null, 'requests', $group_id, (int) $count, $param_tab ) ){
				$default_tabs['requests']['name'] = sprintf( _n( 'Join Requests <span class="count">%s</span>', 'Join Requests <span class="count">%s</span>', $count, 'um-groups' ), number_format_i18n( $count ) );
			}else{
				$default_tabs['requests']['name'] = __( 'Join Requests', 'um-groups' );
			}
		}
		
	}

	return $default_tabs;
}	

/**
 * Add Send Invites tab
 */
add_filter('um_groups_tabs','um_groups_tab_send_invites', 10, 3 );
function um_groups_tab_send_invites( $default_tabs, $group_id, $param_tab ){

	$can_invite_members = UM()->Groups()->api()->can_invite_members();
		
	if( $can_invite_members ){
		
		$privacy = UM()->Groups()->api()->get_privacy_slug( $group_id );

		$default_tabs['invites'] = array(
						'slug' => 'invites',
						'name' => ''
		);

		switch ( $privacy ) {
			case 'private':
			case 'hidden':
			case 'public':
					$default_tabs['invites']['name'] = __( 'Send Invites', 'um-groups' );
			break;

		}
	
	}

	return $default_tabs;
}	

/**
 * Add Banned Users tab
 */
add_filter('um_groups_tabs','um_groups_tab_banned_users', 10, 3 );
function um_groups_tab_banned_users( $default_tabs, $group_id, $param_tab ){

	$count = um_groups_get_banned_member_count( $group_id, true );
	
	if( $count  > 0 ){			
		$default_tabs['blocked'] = array(
						'slug' => 'blocked',
						'name' => ''
		);


		$privacy = UM()->Groups()->api()->get_privacy_slug( $group_id );

		switch ( $privacy ) {
			case 'private':
			case 'hidden':
			case 'public':
				if( UM()->Groups()->api()->can_approve_requests( $group_id, null, $privacy ) ){
					
					if( UM()->Groups()->api()->show_tab_count_notification( null, 'blocked', $group_id, (int) $count, $param_tab ) ){
						$default_tabs['blocked']['name'] = sprintf( _n( 'Blocked <span class="count">%s</span>', 'Blocked <span class="count">%s</span>', $count, 'um-groups' ), number_format_i18n( $count ) );
					}else{
						$default_tabs['blocked']['name'] = __( 'Blocked', 'um-groups' );
					}


				}
			break;

		}
	}

	return $default_tabs;
}	

/**
 * Apply tab access permisssion by member roles and group privacy
 */
add_filter('um_groups_tabs','um_groups_tab_role_permission', 100, 3 );
function um_groups_tab_role_permission( $default_tabs = array() , $group_id, $param_tab ){

	if( um_groups_admin_all_access() ){
			return $default_tabs;
	}

	$privacy = UM()->Groups()->api()->get_privacy_slug( $group_id );
	
	switch ( $privacy ) {

		case 'public':
		
			if( ! UM()->Groups()->api()->can_manage_group( $group_id, null, $privacy ) ){
				unset( $default_tabs['settings'] );
			}
		
		break;

		case 'private':


			if( ! UM()->Groups()->api()->can_approve_requests( $group_id, get_current_user_id(), $privacy ) ){
				unset( $default_tabs['blocked'] );
			}

			if( ! UM()->Groups()->api()->can_manage_group( $group_id, null, $privacy ) ){
				unset( $default_tabs['settings'] );
			}

			$status = UM()->Groups()->api()->has_joined_group( get_current_user_id() , $group_id  );

			if( 'approved' !== $status ){
				$default_tabs = array();
			}

		break;

		case 'hidden':

			if( ! UM()->Groups()->api()->can_manage_group( $group_id, null, $privacy ) ){
				unset( $default_tabs['settings'] );
				unset( $default_tabs['blocked'] );
			}

			$status = UM()->Groups()->api()->has_joined_group( get_current_user_id() , $group_id  );

			if( 'approved' !== $status ){
				$default_tabs = array();
			}

		break;
		
	}

	return $default_tabs;
}

/**
 * Add Group settings sub tabs
 */
add_filter('um_groups_sub_tabs','um_groups_sub_tabs', 10 , 4);
function um_groups_sub_tabs( $sub_tabs, $group_id, $sub_tab, $param_tab ){
	
	if( 'settings' == $param_tab && is_user_logged_in() ){
		$sub_tabs['details'] = array(
				'parent_tab' => 'settings',
				'slug' => 'details',
				'name' => __('Details','um-groups')
		);

		$sub_tabs['avatar'] = array(
				'parent_tab' => 'settings',
				'slug' => 'avatar',
				'name' => __('Avatar','um-groups')
		);

		$sub_tabs['delete'] = array(
				'parent_tab' => 'settings',
				'slug' => 'delete',
				'name' => __('Delete','um-groups')
		);
	}
	

	return $sub_tabs;
}

/**
 * Add default fields of group form
 */
add_filter('um_field_default_value','um_groups_field_settings_value',10 ,3);
function um_groups_field_settings_value( $default, $data, $type ){
	if( is_single() && get_post_type() == 'um_groups'){
		switch ( $data['key'] ) {
			case 'group_name':
				return get_the_title();

				break;
			case 'group_description':
				return get_the_content();

				break;

			case 'group_privacy':
				return UM()->query()->get_meta_value( '_um_groups_privacy' );

				break;
			case 'can_invite_members':
				return UM()->query()->get_meta_value( '_um_groups_can_invite' );

				break;
			case 'post_moderations':
				return UM()->query()->get_meta_value( '_um_groups_posts_moderation' );

				break;

			case 'group_tags':
				$tags = wp_get_object_terms( get_the_ID(), 'um_group_tags' );
				$array_tags = array();
				foreach ( $tags as $tag ) {
					array_push( $array_tags , $tag->name );
				}

				return $array_tags;

				break;

			case 'categories':
				$category = wp_get_object_terms( get_the_ID(), 'um_group_categories' );
	   			
	   			if( isset( $category[0] ) ){
		   			return $category[0]->slug;
		   		}else{
		   			return '';
		   		}

				break;
			
			default:
				 return '';
				break;
		}
	}

	return '';
}

