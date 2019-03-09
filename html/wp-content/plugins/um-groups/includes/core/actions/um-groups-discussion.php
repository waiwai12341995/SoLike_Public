<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Groups discussion confirmation
 */
add_action('wp_footer', 'um_groups_confirm_box');
function um_groups_confirm_box() {

	if( 'um_groups' !== get_post_type() ) return;
	?>
		
	<div class="um-groups-confirm-o"></div>
	<div class="um-groups-confirm">
		<div class="um-groups-confirm-m">

		</div>
		<div class="um-groups-confirm-b">
			<a href="#" class="um-groups-confirm-btn"><?php _e('Yes','um-groups'); ?></a>
			<a href="#" class="um-groups-confirm-close"><?php _e('No','um-groups'); ?></a>
		</div>
	</div>
		
	<style type="text/css">
		
		.um-groups-commentl.highlighted,
		.um-groups-comment-child .um-groups-commentl.highlighted
		{ border-color: <?php echo UM()->options()->get('groups_highlight_color'); ?>;  }
		
		.um-groups-widget.highlighted .um-groups-head {
			border-top-color: <?php echo UM()->options()->get('groups_highlight_color'); ?>;
			border-left-color: <?php echo UM()->options()->get('groups_highlight_color'); ?>;
			border-right-color: <?php echo UM()->options()->get('groups_highlight_color'); ?>;
			border-left-width: 2px;
			border-right-width: 2px;
			border-top-width: 2px;
		}
		
		.um-groups-widget.highlighted .um-groups-body,
		.um-groups-widget.highlighted .um-groups-foot,
		.um-groups-widget.highlighted .um-groups-comments {
			border-left-color: <?php echo UM()->options()->get('groups_highlight_color'); ?>;
			border-right-color: <?php echo UM()->options()->get('groups_highlight_color'); ?>;
			border-left-width: 2px;
			border-right-width: 2px;
		}
		
		.um-groups-widget.highlighted .um-groups-comments {
			border-bottom: 2px solid <?php echo UM()->options()->get('groups_highlight_color'); ?>;
		}
		
		.um-groups-dialog a:hover {background: <?php echo UM()->options()->get('groups_highlight_color'); ?>}
		ul.ui-autocomplete li.ui-menu-item:hover {background: <?php echo UM()->options()->get('groups_highlight_color'); ?> !important}
		
	</style>
		
	<?php

} // um_groups_confirm_box

/**
 * Allow custom wall image upload in discussions
 */
add_filter('um_custom_image_handle_wall_img_upload', 'um_groups_image_handle_wall_img_upload');
function um_groups_image_handle_wall_img_upload( $data ) {
		
	$data['role'] = 'wall-upload';

	return $data;
}

/**
 *  Create new group post type on group front-end creation
 */
add_action('um_groups_after_front_insert','um_groups_after_group_creation_frontend', 10, 2 );
function um_groups_after_group_creation_frontend( $args, $group_id ){

	$has_first_post = get_post_meta( $group_id, '_um_groups_first_post', true );

	if( ! $has_first_post ){

		$user_id = get_current_user_id();
		um_fetch_user( $user_id );
		$author_name = um_user('display_name');
		$author_profile = um_user_profile_url();
		
		UM()->Groups()->discussion()->save(
			array(
				'template' => 'new-group',
				'wall_id' => 0,
				'group_id' => $group_id,
				'author' => $user_id,
				'group_name' => ucwords( get_the_title( $group_id ) ),
				'group_permalink' => get_the_permalink( $group_id ),
				'group_author_name' => $author_name,
				'group_author_profile' => $author_profile,	
			)
		);

		update_post_meta( $group_id, '_um_groups_first_post', true );
	}
}

/**
 *  Create new group post type on group back-end creation
 */
add_action('um_groups_after_backend_insert','um_groups_after_group_creation_backend', 10, 3 );
function um_groups_after_group_creation_backend( $args, $group_id, $update ){

	$has_first_post = get_post_meta( $group_id, '_um_groups_first_post', true );

	if( ! $has_first_post ){
		$user_id = get_current_user_id();
		um_fetch_user( $user_id );
		$author_name = um_user('display_name');
		$author_profile = um_user_profile_url();
		
		UM()->Groups()->discussion()->save(
			array(
				'template' => 'new-group',
				'wall_id' => 0,
				'group_id' => $group_id,
				'author' => $user_id,
				'group_name' => ucwords( get_the_title( $group_id ) ),
				'group_permalink' => get_the_permalink( $group_id ),
				'group_author_name' => $author_name,
				'group_author_profile' => $author_profile,	
			)
		);
		
		update_post_meta( $group_id, '_um_groups_first_post', true );

	}

}