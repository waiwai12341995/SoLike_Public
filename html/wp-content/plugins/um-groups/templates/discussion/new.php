<?php
$user_id = get_current_user_id();
$group_id = get_the_ID();
$show_pending_approval = get_query_var('show');
$group_post_id = isset( $_GET['group_post'] ) ? $_GET['group_post']: '';
$has_joined = UM()->Groups()->api()->has_joined_group( $user_id , $group_id  ) ;
$hide_wall_post = false;
 if( ! in_array( $has_joined, array('approved') ) || 'pending' == $show_pending_approval || ! empty( $group_post_id ) ){
	$hide_wall_post = true;
}

$total_pending_reviews = UM()->Groups()->discussion()->get_pending_reviews_count( $user_id, $group_id );

$group_moderation = get_post_meta( $group_id,  '_um_groups_posts_moderation', true );

if( $total_pending_reviews > 0 && is_user_logged_in() && 'require-moderation' == $group_moderation && ! $hide_wall_post  ){
	echo "<div class='um-groups-pending-approval'>";
	echo "<i class='um-groups-pending-icon um-faicon-exclamation-triangle'></i>";
	$group_pending_discussions_url = add_query_arg( 
		array( 
			'tab' => 'discussion',
			'show' => 'pending'
		), get_the_permalink( $group_id ) 
	);
	
	echo "<a href='". esc_url( $group_pending_discussions_url ) ."'>";
	if( UM()->Groups()->api()->can_manage_group( $group_id, $user_id, $privacy ) ){
		echo sprintf( _n("%s post requires approval","%s posts require approval",$total_pending_reviews,'um-groups'),$total_pending_reviews );
	}else{
		echo sprintf( _n("You have %s post requires admin approval","You have %s posts require admin approval",$total_pending_reviews,'um-groups'),$total_pending_reviews );
	}
	echo "</a>";
	echo "</div>";
}

?>
<div class="um-groups-widget um-groups-new-post" <?php if( $hide_wall_post ){ echo "style='display:none;'"; } ?> >

	<form action="" method="post" class="um-groups-publish">

	<div class="um-groups-head">
		<?php echo ( um_profile_id() == get_current_user_id() ) ? __('Write Post','um-groups') : sprintf(__('Post on %s\'s wall','um-groups'), um_user('display_name') ); ?>
	</div>
	
	<div class="um-groups-body">

		<div class="um-groups-textarea">
			<textarea data-photoph="<?php _e('Say something about this photo','um-groups'); ?>" data-ph="<?php _e('Write something...','um-groups'); ?>" placeholder="<?php _e('Write something...','um-groups'); ?>" class="um-groups-textarea-elem" name="_post_content" id="_post_content"></textarea>
		</div>
		
		<div class="um-groups-preview">
			<span class="um-groups-preview-spn">
				<img src="" alt="" title="" width="" height="" />
				<span class="um-groups-img-remove"><i class="um-icon-close"></i></span>
			</span>
			<input type="hidden" name="_post_img" id="_post_img" value="" />
			<input type="hidden" name="_post_img_url" id="_post_img_url" value="" />

		</div><div class="um-clear"></div>

	</div>
	
	<div class="um-groups-foot">
	
		<div class="um-groups-left um-groups-insert">
		
			<?php do_action('um_groups_pre_insert_tools'); ?>
			
			<?php if ( ! UM()->roles()->um_user_can('groups_photo_off') ) { ?>
			<?php $timestamp = current_time("timestamp"); ?>
			<?php $nonce = wp_create_nonce( 'um_upload_nonce-'.$timestamp ); ?>
			<a href="#" class="um-groups-insert-photo um-tip-s" data-timestamp="<?php echo $timestamp;?>" data-nonce="<?php echo $nonce;?>" title="<?php _e('Add photo','um-groups'); ?>" data-allowed="gif,png,jpeg,jpg" data-size-err="<?php _e('Image is too large','um-groups'); ?>" data-ext-err="<?php _e('Please upload a valid image','um-groups'); ?>"><i class="um-faicon-camera"></i></a>
			<?php } ?>
			
			<?php do_action('um_groups_post_insert_tools'); ?>
			
			<div class="um-clear"></div>
		</div>
		
		<div class="um-groups-right">

			<a href="#" class="um-button um-groups-post um-disabled"><?php _e('Post','um-groups'); ?></a>
		
		</div>
		<div class="um-clear"></div>
	
	</div>
	
	<input type="hidden" name="_wall_id" id="_wall_id" value="<?php echo $user_id; ?>" />
	<input type="hidden" name="_post_id" id="_post_id" value="0" />
	<input type="hidden" name="action" id="action" value="um_groups_publish" />
	<input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce( 'um-frontend-nonce' ) ?>" />
	<input type="hidden" name="_group_id" id="_group_id" value="<?php echo get_the_ID();?>" />
	

	</form>

</div>