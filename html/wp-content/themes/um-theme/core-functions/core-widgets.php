<?php
/**
 * Ultimate Member Plugin : Member Widget
 *
 * @since 0.50
 */

class Um_Theme_Widget_New_Members extends WP_Widget {

	protected $defaults;

	public function __construct() {

		// widget defaults
		$this->defaults = array(
			'title' 					=> esc_html__( '', 'um-theme' ),
			'um-member-order' 			=> 1,
			'member-no' 				=> 6,
			'um-member-layout' 			=> 1,
			'role' 						=> 'editor',
		);

		$widget_slug = 'um_theme_widget_new_members';

		$widget_options   = array(
			'classname' 					=> $widget_slug,
			'description' 					=> esc_html__( 'UM - Members', 'um-theme' ),
			'customize_selective_refresh' 	=> true,
		);

		$widget_name = esc_html__( 'UM Theme: New Members', 'um-theme' );

		parent::__construct( $widget_slug, $widget_name, $widget_options );
		$this->alt_option_name = 'um_theme_widget_new_members';

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
	}

	public function enqueue_scripts( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
	}


	public function print_scripts() { ?>
		<script>
		( function( $ ){
			function initColorPicker( widget ) {
						widget.find( '.color-picker' ).wpColorPicker( {
							change: _.throttle( function() { // For Customizer
								$(this).trigger( 'change' );
							}, 3000 )
						});
			}

			function onFormUpdate( event, widget ) {
				initColorPicker( widget );
			}

			$( document ).on( 'widget-added widget-updated', onFormUpdate );

			$( document ).ready( function() {
				$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
					initColorPicker( $( this ) );
				} );
			} );
		}( jQuery ) );
		</script>
			<?php
	}

	public function widget( $args, $instance ) {

		extract( $args );
		$instance 				= wp_parse_args( (array) $instance, $this->defaults );

		$title 					= apply_filters( 'widget_title', sanitize_text_field( $instance['title'] ) );
		$um_member_order 		= isset( $instance['um-member-order'] ) ? $instance['um-member-order'] : '1';
		$role 					= isset( $instance['role'] ) ? $instance['role'] : 'editor';
		$member_no 				= isset( $instance['member-no'] ) ? $instance['member-no'] : '6';
		$member_layout 			= isset( $instance['um-member-layout'] ) ? $instance['um-member-layout'] : '1';

        echo $before_widget;?>

        <div class="boot-text-center">
        <div class="website-canvas">
		<?php
			if ( $title ) {
				echo $before_title.$title.$after_title;
			}
		?>

		<?php

		if ( class_exists( 'UM' ) ) :

		global $ultimatemember;
		global $um_prefix;

		if ( $um_member_order == 1 ) {
			$query_args = array(
				'role'      	=> $role,
    			'fields'      	=> 'id',
			    'number'      	=> $member_no,
			    'orderby'     	=> 'registered',
			    'order'       	=> 'DESC',
			);
		} elseif ( $um_member_order == 2 ) {
			$query_args = array(
				'role'      	=> $role,
    			'fields'      	=> 'id',
			    'number'      	=> $member_no,
			    'orderby'     	=> 'user_name',
			    'order'       	=> 'DESC',
			);
		} elseif ( $um_member_order == 3 ) {
			$query_args = array(
				'role'      	=> $role,
    			'fields'      	=> 'id',
			    'number'      	=> $member_no,
			    'orderby'     	=> 'display_name',
			    'order'       	=> 'DESC',
			);
		} else {
			$query_args = array(
				'role'      	=> $role,
    			'fields'      	=> 'id',
			    'number'      	=> $member_no,
			    'orderby'     	=> 'post_count',
			    'order'       	=> 'DESC',
			);
		}


		$wp_user_query = new WP_User_Query( $query_args );

		// Get the results
		$users = $wp_user_query->get_results();

		if ( ! empty( $users ) ) :

		do_action( 'um_theme_member_widget_before' ); // action hook um_theme_member_widget_before

		if ( $member_layout == 1 ) {

			echo '<div class="boot-row">';
		  foreach( $users as $user_id ) :

		    um_fetch_user( $user_id );
		    $user = get_user_by( "id", $user_id );
		    $date_format = get_option( 'date_format' );
		    ?>

		    <div class="boot-col-12 um-widget-member">
		    	<div class="boot-row boot-align-items-center">
		    		<div class="boot-col-4 um-widget-member-image um-w-av-round">
		    			<a title="<?php echo um_user( 'display_name' )?>" href="<?php echo um_user_profile_url()?>">
		    				<?php echo um_get_avatar( '', $user_id, 65 )?>
		    			</a>
		    		</div>
		    		<div class="boot-col-8 um-widget-member-name">
		    			<a title="<?php echo um_user( 'display_name' )?>" href="<?php echo um_user_profile_url()?>">
							<?php echo um_user( "display_name" );?>
						</a>
		    		</div>
		    	</div>
		    </div>

		    <?php
		  endforeach;
		  echo '</div>';

		  do_action( 'um_theme_member_widget_after' );

		} else {
			echo '<div class="boot-row">';
		  foreach( $users as $user_id ) :

		    um_fetch_user( $user_id );
		    $user = get_user_by( "id", $user_id );
		    $date_format = get_option( 'date_format' );
		    ?>
		    <div class="boot-col-12 um-widget-member">
		    	<div class="boot-align-items-center">

		    		<div class="um-widget-member-image um-widget-member-image-two">
		    			<a title="<?php echo um_user( 'display_name' )?>" href="<?php echo um_user_profile_url()?>">
		    				<?php echo um_get_avatar( '', $user_id, 150 )?>
		    			</a>
		    		</div>

		    		<div class="um-widget-member-name boot-text-center">
		    			<a title="<?php echo um_user( 'display_name' )?>" href="<?php echo um_user_profile_url()?>">
							<?php echo um_user( "display_name" );?>
						</a>
		    		</div>
		    	</div>
		    </div>

		    <?php
		  endforeach;
		  echo '</div>';
		  do_action( 'um_theme_member_widget_after' );
		}
		um_reset_user();
	else :
		esc_html_e( 'Not Found', 'um-theme' );
	endif;
	endif;
		?>
       <?php  echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] 					= esc_html( $new_instance['title'] );
		$instance['role'] 					= $new_instance['role'] ;
		$instance['um-member-order'] 		= strip_tags( $new_instance['um-member-order'] );
		$instance['member-no'] 				= absint( $new_instance['member-no'] );
		$instance['um-member-layout'] 		= absint( $new_instance['um-member-layout'] );

		return $instance;
	}

    public function form( $instance ) {
		// Defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title 					= sanitize_text_field( $instance['title'] );
		$member_no 				= sanitize_text_field( $instance['member-no'] );
		$member_layout 			= sanitize_text_field( $instance['um-member-layout'] );
		?>

	    <!-- Heading -->
	    <p>
		    <label for="<?php echo $this->get_field_id( 'title' );?>"><?php _e( 'Title of Product Category Section ','um-theme' );?></label>
		    <input class="widefat" id="<?php echo $this->get_field_id( 'title' );?>" name="<?php echo $this->get_field_name( 'title' );?>" value="<?php if ( isset( $title ) ) echo esc_attr( $title );?>"/>
		</p>

	    <!-- Member No -->
	    <p>
		    <label for="<?php echo $this->get_field_id( 'member-no' );?>"><?php _e( 'Number of Members','um-theme' );?></label>
		    <input class="widefat" id="<?php echo $this->get_field_id( 'member-no' );?>" name="<?php echo $this->get_field_name( 'member-no' );?>" value="<?php if ( isset( $member_no ) ) echo esc_attr( $member_no );?>"/>
		</p>

		<!-- Members Order -->
        <p>
			<label for="<?php echo $this->get_field_id( 'um-member-order' ); ?>"><?php _e( 'Order Members by', 'um-theme' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'um-member-order' ); ?>" name="<?php echo $this->get_field_name( 'um-member-order' ); ?>" class="widefat">
				<option value="1"
					<?php if ( '1' == $instance['um-member-order'] ) echo 'selected="selected"'; ?>><?php _e( 'Newly Registered', 'um-theme' ) ?>
				</option>
				<option value="2"
					<?php if ( '2' == $instance['um-member-order'] ) echo 'selected="selected"'; ?>><?php _e( 'User Name (A - Z)', 'um-theme' ) ?>
				</option>
				<option value="3"
					<?php if ( '3' == $instance['um-member-order'] ) echo 'selected="selected"'; ?>><?php _e( 'Display Name (A - Z)', 'um-theme' ) ?>
				</option>
				<option value="4"
					<?php if ( '4' == $instance['um-member-order'] ) echo 'selected="selected"'; ?>><?php _e( 'Members with Highest Post', 'um-theme' ) ?>
				</option>
			</select>
		</p>

		<!-- Members Role -->
		<p>
			<label for="<?php echo $this->get_field_id( 'role' ); ?>"><?php _e( 'Role:', 'um-theme' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'role' ); ?>" name="<?php echo $this->get_field_name( 'role' ); ?>">
					<?php wp_dropdown_roles( $instance['role'] ); // Dropdown list of roles. ?>
				</select>
		</p>

		<!-- Members Order -->
        <p>
			<label for="<?php echo $this->get_field_id( 'um-member-layout' ); ?>"><?php _e( 'Layout', 'um-theme' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'um-member-layout' ); ?>" name="<?php echo $this->get_field_name( 'um-member-layout' ); ?>" class="widefat">
				<option value="1"
					<?php if ( '1' == $instance['um-member-layout'] ) echo 'selected="selected"'; ?>><?php _e( 'Layout 1', 'um-theme' ) ?>
				</option>
				<option value="2"
					<?php if ( '2' == $instance['um-member-layout'] ) echo 'selected="selected"'; ?>><?php _e( 'Layout 2', 'um-theme' ) ?>
				</option>

			</select>
		</p>

		<?php
	}
}