<form action="" method="post"  enctype="multipart/form-data">
	<?php do_action('um_groups_upload_form_header'); ?>
	<?php echo UM()->Groups()->api()->get_group_image( get_the_ID(), 'default', 100, 100 ); ?>
	<input type="file" name="um_groups_avatar"  />
	<input type="submit" name="um_groups_upload_avatar" value="Upload" />

	<?php if( has_post_thumbnail() ): ?>
		<input type="submit" name="um_groups_delete_avatar" value="Delete" />
	<?php endif; ?>
	<?php 
	if ( UM()->form()->has_error('um_groups_avatar') ) {
         UM()->Groups()->form_process()->show_error( UM()->form()->errors['um_groups_avatar'] );
    }
    ?>
	<?php wp_nonce_field( 'um-groups-nonce_upload_'.get_current_user_id() ); ?>
</form>