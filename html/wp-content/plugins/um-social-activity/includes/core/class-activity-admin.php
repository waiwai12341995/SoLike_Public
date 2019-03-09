<?php
namespace um_ext\um_social_activity\core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Activity_Admin {

	function __construct() {
	
		$this->slug = 'ultimatemember';
		$this->pagehook = 'toplevel_page_ultimatemember';
		
		add_action('um_extend_admin_menu',  array(&$this, 'um_extend_admin_menu'), 5);
		
		add_action('admin_enqueue_scripts',  array(&$this, 'admin_enqueue_scripts'), 10);
		
		add_filter('views_edit-um_activity', array(&$this, 'views_um_activity') );
		
		add_action( 'load-post-new.php', array(&$this, 'prevent_backend_new'), 9 );

		add_filter('parse_query', array(&$this, 'parse_query') );

        add_filter('manage_edit-um_activity_columns', array(&$this, 'manage_edit_um_activity_columns') );
        add_action('manage_um_activity_posts_custom_column', array(&$this, 'manage_um_activity_posts_custom_column'), 10, 3);

		$this->count_flagged();
		
	}

	function count_flagged(){

		global $pagenow;

		if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type']=='um_activity' ) {

			$flags = new \WP_Query(
				array(
					'post_type' => 'um_activity',
					'meta_query' => array(
						array(
							'key' => '_reported',
							'value' => 0,
							'compare' => '>',
						)
					),
					'posts_per_page' => '-1',
				)
			);

			if( isset( $flags->found_posts ) ){
				update_option('um_activity_flagged', $flags->found_posts );
			}
			
		}

	}

    /**
     * @param $q \WP_Query
     * @return mixed
     */
	function parse_query($q) {
		global $pagenow;

		if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type']=='um_activity' ) {

			if ( isset( $_REQUEST['status'] ) && !empty( $_REQUEST['status'] ) ) {
				
				if ( $_REQUEST['status'] == 'flagged' ) {
					$q->set( 'meta_key', '_reported' );
					$q->set( 'meta_value', 0 );
					$q->set( 'meta_compare', '>' );
				}

			}
			
		}
		
		return $q;
		
	}
	
	/***
	***	@
	***/
	function prevent_backend_new() {
		global $current_screen;
		if( $current_screen->id == 'um_activity'){
			wp_die( __('This can be done from the frontend only.','um-activity') );
		}
	}
	
	/***
	***	@
	***/
	function views_um_activity( $views ) {
		$array['flagged'] = __('Flagged','um-reviews');
		
		foreach( $array as $view => $name ) {
			if ( isset( $_REQUEST['status'] ) && $_REQUEST['status'] == $view || ( !isset( $_REQUEST['status'] ) && $view == 'all' ) ) {
				$class = 'current';
			} else {
				$class = '';
			}
			$count = (int)get_option("um_activity_{$view}");
			$views[ $view ] = '<a href="?post_type=um_activity&status='.$view.'" class="'.$class.'">' . $name . ' <span class="count">('.$count.')</span></a>';
		}
		
		return $views;
	}

	/***
	***	@
	***/
	function um_extend_admin_menu() {

		$t_count = (int) get_option('um_activity_flagged');
		
		$count = '<span class="awaiting-mod update-plugins count-' . $t_count . '"><span class="processing-count">' . number_format_i18n( $t_count ) . '</span></span>';
		
		add_submenu_page( $this->slug, __('Social Activity','um-activity'), sprintf(__('Social Activity %s','um-activity'), $count ), 'manage_options', 'edit.php?post_type=um_activity', '', '' );
		
		add_submenu_page( $this->slug, __('Hashtags','um-activity'), __('Hashtags','um-activity'), 'manage_options', 'edit-tags.php?taxonomy=um_hashtag', '', '' );
		
	}


	/**
	 *
	 */
	function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( ! isset( $screen->id ) ) {
			return;
		}
		if ( ! strstr( $screen->id, 'um_activity' ) ) {
			return;
		}

		wp_register_style('um_admin_activity', um_activity_url . 'includes/admin/assets/css/um-admin-activity.css', array(), um_activity_version );
		wp_enqueue_style('um_admin_activity');
		
		wp_register_script('um_admin_activity', um_activity_url . 'includes/admin/assets/js/um-admin-activity.js', array( 'jquery' ), um_activity_version, true );
		wp_enqueue_script('um_admin_activity');
		
	}


    /***
     ***	@Custom columns
     ***/
    function manage_edit_um_activity_columns($columns) {

        unset( $columns['title'] );
        unset( $columns['tags'] );
        unset( $columns['date'] );

        $columns['a_author'] = __('Author','um-activity');
        $columns['a_content'] = __('Content','um-activity');
        $columns['a_hashtags'] = __('Hashtags','um-activity');
        $columns['a_likes'] = __('Likes','um-activity');
        $columns['a_comments'] = __('Comments','um-activity');
        $columns['a_action'] = __('Action','um-activity');

        return $columns;

    }

    /***
     ***	@Display cusom columns
     ***/
    function manage_um_activity_posts_custom_column($column_name, $id) {
        switch ($column_name) {

            case 'a_hashtags':
                $hashtags = wp_get_post_terms( $id, 'um_hashtag', $args = array('orderby' => 'count', 'order' => 'desc', 'fields' => 'all') );
                $res = '';
                if ( $hashtags ) {
                    foreach( $hashtags as $hashtag ) {
                        $res .= '<a target="_blank" href="' .add_query_arg( 'hashtag', $hashtag->slug, um_get_core_page('activity') ) . '">#' . $hashtag->name . '</a> ('.$hashtag->count.')&nbsp;&nbsp;';
                    }
                }
                echo $res;
                break;

            case 'a_author':
                um_fetch_user( UM()->Activity_API()->api()->get_author( $id ) );
                echo '<a href="'. um_user_profile_url() . '" target="_blank" title="'. um_user('display_name') .'" class="authorimg">' . get_avatar( um_user('ID'), 80 ) . '</a>';
                break;

            case 'a_content':

                $link = get_post_meta( get_the_ID(), '_shared_link', true );
                $author_id = UM()->Activity_API()->api()->get_author( $id );

                echo '<a href="' . get_edit_post_link( $id ) . '" class="um-admin-tipsy-s" title="'.__('Edit','um-activity').'"><strong>'. UM()->Activity_API()->api()->get_post_time( $id ) . '</strong></a> - <a href="' . UM()->Activity_API()->api()->get_permalink( $id ) . '" target="_blank">' .__('Permalink','um-activity').'</a>';
                echo '<div class="um-admin-activity-c">' . UM()->Activity_API()->api()->get_content() . '</div>';
                echo '<div class="um-admin-activity-ph">' .  UM()->Activity_API()->api()->get_photo( get_the_ID(), 'backend', $author_id ) .'</div>';
                echo '<div class="um-admin-activity-if">' .  UM()->Activity_API()->api()->get_video( get_the_ID(), array( 'width' => 300 ) ) .'</div>';
                if ( $link ) {
                    echo '<div class="um-activity-bodyinner-txt"> ' . $link . '</div>';
                }

                if ( UM()->Activity_API()->api()->reported( $id ) ) {
                    $clear_report = add_query_arg( 'um_adm_action', 'wall_report' );
                    $clear_report = add_query_arg( 'post_id', $id, $clear_report );
                    echo '<div class="um-admin-activity-reported">' . sprintf(__('This post is flagged by community. <a href="%s">Clear report</a>','um-activity'), $clear_report ) . '</div>';
                }

                break;

            case 'a_likes':
                echo UM()->Activity_API()->api()->get_likes_number( $id );
                break;

            case 'a_comments':
                echo UM()->Activity_API()->api()->get_comments_number( $id );
                break;

            case 'a_action':
                echo UM()->Activity_API()->api()->get_action( $id );
                if ( UM()->Activity_API()->api()->get_wall( $id ) && UM()->Activity_API()->api()->get_author( $id ) != UM()->Activity_API()->api()->get_wall( $id ) ) {
                    um_fetch_user( UM()->Activity_API()->api()->get_wall( $id ) );
                    echo '<div class="um-admin-activity-resp"><i class="um-icon-forward"></i><a href="'. um_user_profile_url(). '" target="_blank">' . get_avatar(  um_user('ID'), 80 )  . um_user('display_name') . '</a></div>';
                }
                break;

        }

    }

}