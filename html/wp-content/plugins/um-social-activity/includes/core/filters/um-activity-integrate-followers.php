<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@Works on inserting/updating wall posts
	***/
	add_filter('um_activity_insert_post_content_filter', 'um_activity_mention_followers', 99, 4 );
	add_filter('um_activity_update_post_content_filter', 'um_activity_mention_followers', 99, 4 );
	function um_activity_mention_followers( $content, $user_id, $post_id, $status ){

        if ( ! UM()->options()->get( 'activity_followers_mention' ) )
            return $content;

        $content = apply_filters( 'um_activity_mention_integration', $content, $user_id, $post_id, $status );

		return $content;
	}