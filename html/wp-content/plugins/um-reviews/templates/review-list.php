<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( UM()->Reviews_API()->api()->already_reviewed( um_profile_id() ) ) {
	$my_id = get_current_user_id();
} else {
	$my_id = null;
}

foreach( $reviews as $post ) {
	setup_postdata( $post );

	$user_id = get_post_meta( $post->ID, '_reviewer_id', true );
	um_fetch_user( $user_id );

	$content = $post->post_content;
	$content = wp_strip_all_tags( $content ); ?>

	<div class="um-reviews-item" id="review-<?php echo $post->ID; ?>" data-review_id="<?php echo $post->ID; ?>" data-user_id="<?php echo um_profile_id(); ?>">

		<div class="um-reviews-img"><a href="<?php echo um_user_profile_url(); ?>"><?php echo um_user('profile_photo',40); ?></a></div>

		<div class="um-reviews-post review-list">

				<span class="um-reviews-avg" data-number="5" data-score="<?php echo get_post_meta( $post->ID, '_rating', true ); ?>"></span>

				<span class="um-reviews-title"><span><?php the_title(); ?></span></span>

				<span class="um-reviews-meta"><?php printf(__('by <a href="%s">%s</a>, %s','um-reviews'), um_user_profile_url(), um_user('display_name'), get_the_time('F d, Y') ); ?></span>

				<span class="um-reviews-content"><?php the_content(); ?></span>

				<?php if ( UM()->Reviews_API()->api()->is_flagged( $post->ID ) ) { ?>
					<div class="um-reviews-flagged"><?php _e('This is currently being reviewed by an admin','um-reviews'); ?></div>
				<?php } ?>

				<div class="um-reviews-note"></div>

				<div class="um-reviews-tools">
					<?php do_action('um_review_front_actions', um_profile_id(), $user_id, $my_id, $post->ID ); ?>
				</div>

		</div>

		<div class="um-reviews-post review-form">

			<form class="um-reviews-form" action="" method="post">

				<span class="um-reviews-rate" data-key="rating" data-number="5" data-score="<?php echo get_post_meta( $post->ID, '_rating', true ); ?>"></span>

				<span class="um-reviews-title"><input type="text" name="title" placeholder="<?php _e('Enter subject...','um-reviews'); ?>" value="<?php echo $post->post_title; ?>" /></span>

				<span class="um-reviews-meta"><?php printf(__('by <a href="%s">%s</a>, %s','um-reviews'), um_user_profile_url(), um_user('display_name'), current_time('F d, Y') ); ?></span>

				<span class="um-reviews-content"><textarea name="content" placeholder="<?php _e('Enter your review...','um-reviews'); ?>"><?php echo isset( $content ) ? $content:''; ?></textarea></span>

				<input type="hidden" name="user_id" id="user_id" value="<?php echo um_profile_id(); ?>" />
				<input type="hidden" name="reviewer_id" id="reviewer_id" value="<?php echo get_current_user_id(); ?>" />
				<input type="hidden" name="action" id="action" value="um_review_edit" />
				<input type="hidden" name="nonce" id="action" value="<?php echo wp_create_nonce( 'um-frontend-nonce' ) ?>" />

				<input type="hidden" name="review_id" id="review_id" value="<?php echo $post->ID; ?>" />
				<input type="hidden" name="rating_old" id="rating_old" value="<?php echo get_post_meta( $post->ID, '_rating', true ); ?>" />
				<input type="hidden" name="reviewer_publish" id="reviewer_publish" value="<?php echo UM()->roles()->um_user_can('can_publish_review'); ?>" />

				<div class="um-field-error" style="display:none"></div>

				<span class="um-reviews-send"><input type="submit" value="<?php _e('Save Review','um-reviews'); ?>" class="um-button" /></span>

			</form>

		</div>

		<div class="um-clear"></div>

	</div>

<?php }

um_reset_user();
wp_reset_postdata();
wp_reset_query();