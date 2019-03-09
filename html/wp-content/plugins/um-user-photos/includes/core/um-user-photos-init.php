<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class UM_Photos_API
 */
class UM_Photos_API {


	/**
	 * @var
	 */
	private static $instance;


	/**
	 * @return UM_Photos_API
	 */
	static public function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_Photos_API constructor.
	 */
	function __construct() {
		add_filter( 'um_call_object_Photos_API', array( &$this, 'get_this' ) );

		$this->admin();
		$this->shortcodes();
		$this->common();
		$this->profile();
		$this->account();
		$this->ajax();
		$this->activity();

		add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );
	}


	/**
	 * @param $defaults
	 *
	 * @return array
	 */
	function default_settings( $defaults ) {
		$defaults = array_merge( $defaults, $this->setup()->settings_defaults );
		return $defaults;
	}


	/**
	 * @return um_ext\um_user_photos\core\User_Photos_Setup()
	 */
	function setup() {
		if ( empty( UM()->classes['um_photos_setup'] ) ) {
			UM()->classes['um_photos_setup'] = new um_ext\um_user_photos\core\User_Photos_Setup();
		}
		return UM()->classes['um_photos_setup'];
	}


	/**
	 * @return um_ext\um_user_photos\admin\Admin()
	 */
	function admin() {
		if ( empty( UM()->classes['um_photos_admin'] ) ) {
			UM()->classes['um_photos_admin'] = new um_ext\um_user_photos\admin\Admin();
		}
		return UM()->classes['um_photos_admin'];
	}


	/**
	 * @return um_ext\um_user_photos\core\User_Photos_Shortcodes()
	 */
	function shortcodes() {
		if ( empty( UM()->classes['um_photos_shortcodes'] ) ) {
			UM()->classes['um_photos_shortcodes'] = new um_ext\um_user_photos\core\User_Photos_Shortcodes();
		}
		return UM()->classes['um_photos_shortcodes'];
	}


	/**
	 * @return um_ext\um_user_photos\core\User_Photos_Common()
	 */
	function common() {
		if ( empty( UM()->classes['um_photos_common'] ) ) {
			UM()->classes['um_photos_common'] = new um_ext\um_user_photos\core\User_Photos_Common();
		}
		return UM()->classes['um_photos_common'];
	}
	
	
	
	/**
	 * @return bool|um_ext\um_user_photos\core\User_Photos_Activity()
	 */
	function activity() {
		if ( ! class_exists('UM_Activity_API') ) {
			return false;
		}

		if ( empty( UM()->classes['um_photos_activity'] ) ) {
			UM()->classes['um_photos_activity'] = new um_ext\um_user_photos\core\User_Photos_Activity();
		}
		return UM()->classes['um_photos_activity'];
	}


	/**
	 * @return um_ext\um_user_photos\core\User_Photos_Profile()
	 */
	function profile() {
		if ( empty( UM()->classes['um_photos_profile'] ) ) {
			UM()->classes['um_photos_profile'] = new um_ext\um_user_photos\core\User_Photos_Profile();
		}
		return UM()->classes['um_photos_profile'];
	}


	/**
	 * @return um_ext\um_user_photos\core\User_Photos_Account()
	 */
	function account() {
		if ( empty( UM()->classes['um_photos_account'] ) ) {
			UM()->classes['um_photos_account'] = new um_ext\um_user_photos\core\User_Photos_Account();
		}
		return UM()->classes['um_photos_account'];
	}


	/**
	 * @return um_ext\um_user_photos\core\User_Photos_Ajax()
	 */
	function ajax() {
		if ( empty( UM()->classes['um_photos_ajax'] ) ) {
			UM()->classes['um_photos_ajax'] = new um_ext\um_user_photos\core\User_Photos_Ajax();
		}
		return UM()->classes['um_photos_ajax'];
	}


	/**
	 * @return $this
	 */
	function get_this() {
		return $this;
	}


	/**
	 * Helper function to load template
	 *
	 * @param $file
	 * @param array $args
	 */
	function get_view( $file, $args = array() ) {
		if ( ! empty( $args ) ) {
			extract( $args );
		}

		$theme_file = get_stylesheet_directory() . '/ultimate-member/um-user-photos/' . $file . '.php';
		$plugin_file = um_user_photos_path . 'views/' . $file . '.php';

		if ( file_exists( $theme_file ) ) {
			$plugin_file = $theme_file;
		}

		$plugin_file = str_replace( '/', DIRECTORY_SEPARATOR, $plugin_file );

		include $plugin_file;
	}
}

//create class var
add_action( 'plugins_loaded', 'um_init_photos', -10, 1 );
function um_init_photos() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'Photos_API', true );
	}
}
