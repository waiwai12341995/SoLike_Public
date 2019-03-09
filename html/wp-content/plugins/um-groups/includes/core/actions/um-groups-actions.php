<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Groups join/leave button
 */
add_action('um_groups_join_button','um_groups_join_button');
function um_groups_join_button( $group_id = 0 ){
	wp_enqueue_script( 'um_groups' );
	wp_enqueue_style( 'um_groups' );

	$has_joined 		= UM()->Groups()->api()->has_joined_group( um_user('ID'), $group_id );
	$privacy 			= UM()->Groups()->api()->get_privacy_slug( $group_id );
	$button_labels 		= UM()->Groups()->api()->privacy_groups_button_labels[ $privacy ];
	$is_own_group 		= UM()->Groups()->api()->is_own_group( $group_id );
	
	echo '<div class="um-groups-single-button">';
	
		if( ! um_groups_admin_all_access() ){
		
			switch ( $privacy ) {
				case 'hidden':
					if( is_user_logged_in() && in_array( $has_joined, array('approved') ) ){
						echo '<a href="javascript:;" class="um-button um-groups-btn-leave um-groups-btn-hidden" data-groups-button-hover="'.$button_labels['hover'] .'"  data-groups-button-default="'.$button_labels['leave'] .'"  data-group_id="'.esc_attr($group_id).'">'.$button_labels['leave'].'</a>';
					}
					break;

				case 'private':
					if( is_user_logged_in() ){
							if( in_array( $has_joined, array('approved') ) ){
								echo '<a href="javascript:;" class="um-button um-groups-btn-leave"  data-groups-button-default="'.$button_labels['_leave'] .'"   data-group_id="'.esc_attr($group_id).'">'.$button_labels['_leave'].'</a>';
							}else if( in_array( $has_joined, array('pending_admin_review' ) ) ){
								echo '<a href="javascript:;" class="um-button um-groups-btn-leave"  data-groups-button-hover="'.$button_labels['hover'] .'" data-groups-button-default="'.$button_labels['leave'] .'" data-group_id="'.esc_attr($group_id).'">'.$button_labels['leave'].'</a>';
							}elseif( in_array( $has_joined, array('rejected','') ) ){
								echo '<a href="javascript:;" class="um-button um-groups-btn-join" data-groups-button-default="'.$button_labels['leave'] .'"    data-groups-button-hover="'.$button_labels['hover'] .'"  data-group_id="'.esc_attr($group_id).'">'.$button_labels['join'].'</a>';
							}
					}else{
							echo '<a href="'.um_get_core_page('login').'?redirect_to='.get_the_permalink( $group_id ).'" class="um-button um-groups-btn-guest" >'.__('Join Group', 'um-groups' ).'</a>';
					}
					break;

				
				default:
					
					if( is_user_logged_in() ){
							if( $has_joined == 'approved' ){
								echo '<a href="javascript:;" class="um-button um-groups-btn-leave" data-groups-button-hover="'.$button_labels['hover'] .'"  data-groups-button-default="'.$button_labels['leave'] .'"  data-group_id="'.esc_attr($group_id).'">'.$button_labels['leave'].'</a>';
							}else if( in_array( $has_joined, array('') ) ) {
								echo '<a href="javascript:;" class="um-button um-groups-btn-join" data-groups-button-default="'.$button_labels['join'] .'"    data-groups-button-hover="'.$button_labels['hover'] .'" data-group_id="'.esc_attr($group_id).'">'.$button_labels['join'].'</a>';
							}
					}else{

						if( isset( $button_labels['join'] ) ){
							echo '<a href="'.um_get_core_page('login').'?redirect_to='.get_the_permalink( $group_id ).'" class="um-button um-groups-btn-guest" >'.$button_labels['join'].'</a>';
						}
					}

					break;
			}
			
		}

	echo '</div>';
	
}

/**
 * Groups form error handler
 */
add_action('um_groups_publisher_errors_hook','um_groups_publisher_errors_hook');
function um_groups_publisher_errors_hook( $arr_posts ){

	UM()->form()->post_form = $_POST;

	$nonce = $_REQUEST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'um-groups-nonce_'.get_current_user_id()  ) ) {

	    wp_die('Invalid Nonce.');

	} else {

		if( isset( $arr_posts['group_name'] ) && ! empty( $arr_posts['group_name'] ) ){
			UM()->Groups()->api()->single_group_title = $arr_posts['group_name'];
		}
			
		if( isset( $arr_posts['group_name'] ) && empty( $arr_posts['group_name']  ) ){
			UM()->form()->add_error('group_name', __('You must enter a group name','um-groups') );
		}

		if( isset( $arr_posts['group_name'] ) && ! empty( $arr_posts['group_name']  ) && strlen( $arr_posts['group_name'] ) < 3 ){
			UM()->form()->add_error('group_name', __('Minimum of 3 characters are allowed.','um-groups') );
		}

		if( isset( $arr_posts['group_description'] ) && empty( $arr_posts['group_description']  ) ){
			UM()->form()->add_error('group_description', __('You must enter a description','um-groups') );
		}

		if( isset( $arr_posts['categories'] ) && empty( $arr_posts['categories']  ) ){
			UM()->form()->add_error('categories', __('You must select a category','um-groups') );
		}

		if( ! isset( $arr_posts['group_tags'] ) || isset( $arr_posts['group_tags'] ) && empty( $arr_posts['group_tags']  ) ){
			UM()->form()->add_error('group_tags', __('You must select a tag','um-groups') );
		}
	}
}

/**
 * Groups form upload error handler
 */
add_action('um_groups_upload_file_errors_hook','um_groups_upload_file_errors_hook');
function um_groups_upload_file_errors_hook( $post ){

	UM()->form()->post_form = $_POST;
	$arr_file = $_FILES;
	
	$nonce = $_REQUEST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'um-groups-nonce_upload_'.get_current_user_id()  ) ) {

	    wp_die( __('Invalid Nonce.','um-groups') );

	} else {

		if( ! UM()->Groups()->api()->can_manage_group( get_the_ID() ) && ! um_groups_admin_all_access() ){
			wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
		}

		if( isset( $arr_file['um_groups_avatar']['error'] ) && $arr_file['um_groups_avatar']['error'] > 0 ){
			UM()->form()->add_error('um_groups_avatar', __('You must select an image file','um-groups') );
		}

	}
	
}

/**
 * Groups form delete file error handler
 */
add_action('um_groups_delete_file_errors_hook','um_groups_delete_file_errors_hook');
function um_groups_delete_file_errors_hook( $post ){

	UM()->form()->post_form = $_POST;
	$arr_file = $_FILES;
	
	$nonce = $_REQUEST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'um-groups-nonce_upload_'.get_current_user_id()  ) ) {

	    wp_die( __('Invalid Nonce.','um-groups') );

	} else {

		if( ! UM()->Groups()->api()->can_manage_group( get_the_ID() ) && ! um_groups_admin_all_access() ){
			wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
		}
		
	}
	
}

/**
 * Groups form delete group error handler
 */
add_action('um_groups_delete_group_errors_hook','um_groups_delete_group_errors_hook');
function um_groups_delete_group_errors_hook( $post ){

	UM()->form()->post_form = $_POST;
	$arr_file = $_FILES;
	
	$nonce = $_REQUEST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'um-groups-nonce_delete_group_'.get_current_user_id()  ) ) {

	    wp_die( __('Invalid Nonce.','um-groups') );

	} else {

		if( ! UM()->Groups()->api()->can_manage_group( get_the_ID() ) && ! um_groups_admin_all_access() ){
			wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
		}
		
	}
	
}

/**
 * Groups delete group process
 */
add_action('um_groups_delete_group_process_form','um_groups_delete_group_process_form');
function um_groups_delete_group_process_form( $arr_posts ){
	$group_id = get_the_ID();

	if( ! UM()->Groups()->api()->can_manage_group( $group_id ) && ! um_groups_admin_all_access() ){
		wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
	}

	if (  isset( UM()->form()->errors ) ) {
		UM()->Groups()->form_process()->form_process_successful = false;
		return;
 	}

		
 	UM()->Groups()->form_process()->form_process_successful = true;

 	$has_deleted = UM()->Groups()->api()->delete_group_members( $group_id );

 	if( $has_deleted ){
 		$redirect_url = add_query_arg( 'um_group_deleted', true, get_the_permalink( $group_id ) );
		
		$attachment_id = get_post_thumbnail_id( $group_id );

		wp_delete_attachment( $attachment_id, true );
		wp_delete_post( $group_id );
	 					
		wp_safe_redirect( $redirect_url ); exit;
	}else{
		UM()->form()->add_error('um_groups_avatar', __('Something went wrong.','um-groups') );
	}
	
	
}

/**
 * Groups delete file process
 */
add_action('um_groups_delete_file_process_form','um_groups_delete_file_process_form');
function um_groups_delete_file_process_form( $arr_posts ){
	$group_id = get_the_ID();

	if( ! UM()->Groups()->api()->can_manage_group( $group_id ) && ! um_groups_admin_all_access() ){
		wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
	}

	if (  isset( UM()->form()->errors ) ) {
		UM()->Groups()->form_process()->form_process_successful = false;
		return;
 	}

	if( has_post_thumbnail() ){
 		
 		UM()->Groups()->form_process()->form_process_successful = true;

 		$attachment_id = get_post_thumbnail_id( $group_id );

 		delete_post_thumbnail( $group_id, $attachment_id );
 		wp_delete_attachment( $attachment_id, true );

		$redirect_url = add_query_arg( 'updated', true, $arr_posts['_wp_http_referer'] );
					
		wp_safe_redirect( $redirect_url ); exit;
	
	}
}

/**
 * Groups upload file process
 */
add_action('um_groups_upload_file_process_form','um_groups_upload_file_process_form');
function um_groups_upload_file_process_form( $arr_posts ){
	$group_id = get_the_ID();

	if( ! UM()->Groups()->api()->can_manage_group( $group_id ) && ! um_groups_admin_all_access() ){
		wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
	}

	if (  isset( UM()->form()->errors ) ) {
		UM()->Groups()->form_process()->form_process_successful = false;
		return;
 	}

	if( ! empty( $_FILES ) ) {
	  foreach( $_FILES as $file ) {
	    
	    if( is_array( $file ) ) {

	    		// Delete existing group image
	    		$attachment_id = get_post_thumbnail_id( $group_id );

			 	delete_post_thumbnail( $group_id, $attachment_id );
			 	
			 	wp_delete_attachment( $attachment_id, true );

			 	// Upload new group image
	      		$attachment_id = um_groups_upload_user_file( $file );
				
				if( $attachment_id ){

 					UM()->Groups()->form_process()->form_process_successful = true;

					set_post_thumbnail( $group_id, $attachment_id );

					$redirect_url = add_query_arg( 'updated', true, $arr_posts['_wp_http_referer'] );
					
					wp_safe_redirect( $redirect_url ); exit;
				}
	    }

	  }
	}
}

/**
 * Groups updater process form
 */
add_action('um_groups_updater_process_form','um_groups_updater_process_form');
function um_groups_updater_process_form( $arr_posts ){

	if (  isset( UM()->form()->errors ) ) {
		UM()->Groups()->form_process()->form_process_successful = false;
		return;
 	}
 	
 	$group_id = get_the_ID();

	if( ! UM()->Groups()->api()->can_manage_group( $group_id ) && ! um_groups_admin_all_access() ){
		wp_die( __('You don\'t have a permission to change something in this group!','um-groups') );
	}

 	$arr_posts = apply_filters('um_groups_process_form_posts', $arr_posts );

 	do_action('um_groups_before_front_update', $arr_posts );

 	wp_update_post(array (
 	 	'ID'	=> $group_id,
	   	'post_type' 		=> 'um_groups',
	   	'post_title' 	=> $arr_posts['group_name'],
	   	'post_content' 	=> $arr_posts['group_description'],
	   	'post_status' 	=> 'publish',
	   	'comment_status' => 'closed',   
	  	 'ping_status' 	=> 'closed',      
	));

 	
	// insert post meta
	update_post_meta( $group_id, '_um_groups_privacy', 			$arr_posts['group_privacy'] );
	update_post_meta( $group_id, '_um_groups_can_invite', 		$arr_posts['can_invite_members'] );
	update_post_meta( $group_id, '_um_groups_posts_moderation', 	$arr_posts['post_moderations'] );
	   
	wp_set_object_terms( $group_id , $arr_posts['categories'], 	'um_group_categories', 	false );
	wp_set_object_terms( $group_id , $arr_posts['group_tags'], 	'um_group_tags', 		false );
	
	UM()->Groups()->form_process()->form_process_successful = true;
		
	do_action('um_groups_after_front_update', $arr_posts, $group_id );

	$redirect_url = add_query_arg( 'updated', true, $arr_posts['_wp_http_referer'] );
	wp_safe_redirect( $redirect_url ); exit;

}

/**
 * Form process handler
 */
add_action('um_groups_publisher_process_form','um_groups_publisher_process_form');
function um_groups_publisher_process_form( $arr_posts ){

	if (  isset( UM()->form()->errors ) ) {
 	 	UM()->Groups()->form_process()->form_process_successful = false;
		return;
 	}

 	$arr_posts = apply_filters('um_groups_process_form_posts', $arr_posts );

 	do_action('um_groups_before_front_insert', $arr_posts );

 	$group_id = wp_insert_post(array (
	   'post_type' 		=> 'um_groups',
	   'post_title' 	=> $arr_posts['group_name'],
	   'post_content' 	=> $arr_posts['group_description'],
	   'post_status' 	=> 'publish',
	   'comment_status' => 'closed',   
	   'ping_status' 	=> 'closed',      
	));

 	if ( $group_id ) {
	   // insert post meta
	   add_post_meta( $group_id, '_um_groups_privacy', 				$arr_posts['group_privacy'] );
	   add_post_meta( $group_id, '_um_groups_can_invite', 			$arr_posts['can_invite_members'] );
	   add_post_meta( $group_id, '_um_groups_posts_moderation', 	$arr_posts['post_moderations'] );
	   
	   wp_set_object_terms( $group_id , $arr_posts['categories'], 	'um_group_categories', 	false );
	   wp_set_object_terms( $group_id , $arr_posts['group_tags'], 	'um_group_tags', 		false );

	   UM()->Groups()->form_process()->form_process_successful = true;
	
	}

	do_action('um_groups_after_front_insert', $arr_posts, $group_id );

 	wp_safe_redirect( get_permalink( $group_id ) ); exit;

}

/**
 * Add self/author to own group on front-end creation
 */
add_action('um_groups_after_front_insert','um_groups_add_self_to_own_group', 10, 2 );
function um_groups_add_self_to_own_group( $arr_posts, $group_id ){
	$user_id = get_current_user_id();
	$new_group = true;
 	UM()->Groups()->api()->join_group( $user_id, $user_id, $group_id, 'admin', $new_group);
}

/**
 * Add self/author to own group on back-end creation
 */
add_action('um_groups_after_backend_insert','um_groups_add_self_to_own_group_backend', 10, 3 );
function um_groups_add_self_to_own_group_backend( $arr_posts, $group_id , $update ){

	if( ! $update ){
		$user_id = get_current_user_id();
		$new_group = true;
	 	UM()->Groups()->api()->join_group( $user_id, $user_id, $group_id, 'admin', $new_group );
	}

}

/**
 * Groups form header notices
 */
add_action('um_groups_create_form_header','um_groups_form_notice');
add_action('um_groups_upload_form_header','um_groups_form_notice');
function um_groups_form_notice( $arr_settings ){

	$updated = get_query_var('updated');

	if( $updated == 1 ){
		echo '<p class="um-notice success"><i class="um-icon-ios-close-empty" onclick="jQuery(this).parent().fadeOut();"></i>'.__('Group was updated successfully.','um-groups').'</p>';
	}
}

/**
 * Get Members pre user query
 */
add_action( 'pre_user_query', 'um_groups_get_members_pre_user_query');
function um_groups_get_members_pre_user_query( $uqi ) {
	global $wpdb;
				 
	
	if ( isset( $uqi->query_vars['um_groups_get_members'] ) ){
		$group_id = $uqi->query_vars['um_group_id'];
		$groups_table_name = UM()->Groups()->setup()->db_groups_table;
					
		$group_meta = $wpdb->prepare("
				        {$wpdb->users}.ID NOT IN( 
				        	SELECT DISTINCT tbg.user_id1 FROM {$groups_table_name} as tbg
				       		WHERE tbg.user_id1 = {$wpdb->users}.ID AND tbg.group_id = %d AND tbg.role NOT IN('approved','blocked','rejected') GROUP BY tbg.user_id1
				        )", $group_id );
		
		$uqi->query_where = str_replace(
				            'WHERE 1=1 AND (',
				            "WHERE 1=1 AND (" . $group_meta . " AND ",
				            $uqi->query_where );
	}
				   
}

/**
 * Search users form
 */
add_action('um_groups_search_users','um_groups_search_users');
function um_groups_search_users( $args ) {
	wp_enqueue_script( 'um_groups' );
	wp_enqueue_style( 'um_groups' );

	echo "<div id='um-groups-users-search-form' class='um-groups-users-search-form' data-group-id='{$args['group_id']}' data-load-more='{$args['load_more']}' >";
	echo "<form method='GET' action='' name='um_groups_search_users'>";
	echo "<input type='hidden' name='tab' value='invites' /> ";
	echo "<input type='text' name='search-user' placeholder='".__("Search people","um-groups")."' />";
	echo "</form>";
	echo "</div>";
	echo "<div class='um-clear'></div>";
}

/**
 * Delete member on user delete
 */
add_action( 'delete_user', 'um_groups_delete_user' );
function um_groups_delete_user( $user_id ) {
	global $wpdb;
	$table_name = UM()->Groups()->setup()->db_groups_table;
	$wpdb->query(
	$wpdb->prepare("DELETE FROM {$table_name} WHERE user_id1 = %s ", $user_id)
		);
}