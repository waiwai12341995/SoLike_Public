<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class UM_Messaging_API
 */
class UM_Messaging_API {


	/**
	 * @var
	 */
	private static $instance;


	/**
	 * @return UM_Messaging_API
	 */
	static public function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_Messaging_API constructor.
	 */
	function __construct() {
		// Global for backwards compatibility.
		$GLOBALS['um_messaging'] = $this;
		add_filter( 'um_call_object_Messaging_API', array( &$this, 'get_this' ) );

		if ( UM()->is_request( 'admin' ) ) {
			$this->admin_upgrade();
			$this->admin();
		}

		$this->api();
		$this->enqueue();
		$this->shortcode();
		$this->gdpr();
		$this->account();
		$this->profile();

		add_action( 'plugins_loaded', array( &$this, 'init' ), 1 );

		add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );

		add_filter( 'um_rest_api_get_stats', array( &$this, 'rest_api_get_stats' ), 10, 1 );

		add_filter( 'um_email_templates_path_by_slug', array( &$this, 'email_templates_path_by_slug' ), 10, 1 );

		add_action( 'wp_ajax_um_messaging_update', array( $this->api(), 'ajax_messaging_update' ) );
		add_action( 'wp_ajax_um_messaging_unblock_user', array( $this->api(), 'ajax_messaging_unblock_user' ) );
		add_action( 'wp_ajax_um_messaging_block_user', array( $this->api(), 'ajax_messaging_block_user' ) );
		add_action( 'wp_ajax_um_messaging_delete_conversation', array( $this->api(), 'ajax_messaging_delete_conversation' ) );
		add_action( 'wp_ajax_um_messaging_remove', array( $this->api(), 'ajax_messaging_remove' ) );
		add_action( 'wp_ajax_um_messaging_send', array( $this->api(), 'ajax_messaging_send' ) );
		add_action( 'wp_ajax_um_messaging_login_modal', array( $this->api(), 'ajax_messaging_login_modal' ) );
		add_action( 'wp_ajax_nopriv_um_messaging_login_modal', array( $this->api(), 'ajax_messaging_login_modal' ) );
		add_action( 'wp_ajax_um_messaging_start', array( $this->api(), 'ajax_messaging_start' ) );
		add_action( 'wp_ajax_um_conversations_load', array( $this->api(), 'ajax_conversations_load' ) );
	}


	/**
	 * @param $slugs
	 *
	 * @return mixed
	 */
	function email_templates_path_by_slug( $slugs ) {
		$slugs['new_message'] = um_messaging_path . 'templates/email/';
		$slugs['new_message_reminder'] = um_messaging_path . 'templates/email/';
		return $slugs;
	}


	/**
	 * @param $response
	 *
	 * @return mixed
	 */
	function rest_api_get_stats( $response ) {
		global $wpdb;

		$total_conversations = wp_cache_get( 'um_conversations:all', 'um_messaging' );
		if ( false === $total_conversations ) {
			$total_conversations = absint( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}um_conversations" ) );
			wp_cache_set( 'um_conversations:all', $total_conversations, 'um_messaging' );
		}
		$response['stats']['total_conversations'] = $total_conversations;

		$total_messages = wp_cache_get( 'um_messages:all', 'um_messaging' );
		if ( false === $total_messages ) {
			$total_messages = absint( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}um_messages" ) );
			wp_cache_set( 'um_messages:all', $total_messages, 'um_messaging' );
		}
		$response['stats']['total_messages'] = $total_messages;

		return $response;
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
	 * @return $this
	 */
	function get_this() {
		return $this;
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_Setup()
	 */
	function setup() {
		if ( empty( UM()->classes['um_messaging_setup'] ) ) {
			UM()->classes['um_messaging_setup'] = new um_ext\um_messaging\core\Messaging_Setup();
		}
		return UM()->classes['um_messaging_setup'];
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_Main_API()
	 */
	function api() {
		if ( empty( UM()->classes['um_messaging_main_api'] ) ) {
			UM()->classes['um_messaging_main_api'] = new um_ext\um_messaging\core\Messaging_Main_API();
		}
		return UM()->classes['um_messaging_main_api'];
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_Enqueue()
	 */
	function enqueue() {
		if ( empty( UM()->classes['um_messaging_enqueue'] ) ) {
			UM()->classes['um_messaging_enqueue'] = new um_ext\um_messaging\core\Messaging_Enqueue();
		}
		return UM()->classes['um_messaging_enqueue'];
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_Shortcode()
	 */
	function shortcode() {
		if ( empty( UM()->classes['um_messaging_shortcode'] ) ) {
			UM()->classes['um_messaging_shortcode'] = new um_ext\um_messaging\core\Messaging_Shortcode();
		}
		return UM()->classes['um_messaging_shortcode'];
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_GDPR()
	 */
	function gdpr() {
		if ( empty( UM()->classes['um_messaging_gdpr'] ) ) {
			UM()->classes['um_messaging_gdpr'] = new um_ext\um_messaging\core\Messaging_GDPR();
		}
		return UM()->classes['um_messaging_gdpr'];
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_Account()
	 */
	function account() {
		if ( empty( UM()->classes['um_messaging_account'] ) ) {
			UM()->classes['um_messaging_account'] = new um_ext\um_messaging\core\Messaging_Account();
		}
		return UM()->classes['um_messaging_account'];
	}


	/**
	 * @return um_ext\um_messaging\core\Messaging_Profile()
	 */
	function profile() {
		if ( empty( UM()->classes['um_messaging_profile'] ) ) {
			UM()->classes['um_messaging_profile'] = new um_ext\um_messaging\core\Messaging_Profile();
		}
		return UM()->classes['um_messaging_profile'];
	}


	/**
	 * @return um_ext\um_messaging\admin\core\Admin_Upgrade()
	 */
	function admin_upgrade() {
		if ( empty( UM()->classes['um_messaging_admin_upgrade'] ) ) {
			UM()->classes['um_messaging_admin_upgrade'] = new um_ext\um_messaging\admin\core\Admin_Upgrade();
		}
		return UM()->classes['um_messaging_admin_upgrade'];
	}


	/**
	 * @return um_ext\um_messaging\admin\core\Messaging_Admin()
	 */
	function admin() {
		if ( empty( UM()->classes['um_messaging_admin'] ) ) {
			UM()->classes['um_messaging_admin'] = new um_ext\um_messaging\admin\core\Messaging_Admin();
		}
		return UM()->classes['um_messaging_admin'];
	}


	/**
	 * Init
	 */
	function init() {

		// Actions
		require_once um_messaging_path . 'includes/core/actions/um-messaging-notifications.php';
		require_once um_messaging_path . 'includes/core/actions/um-messaging-members.php';

		// Filters
		require_once um_messaging_path . 'includes/core/filters/um-messaging-permissions.php';
		require_once um_messaging_path . 'includes/core/filters/um-messaging-settings.php';
		require_once um_messaging_path . 'includes/core/filters/um-messaging-menu.php';
		require_once um_messaging_path . 'includes/core/filters/um-messaging-fields.php';
	}
}

//create class var
add_action( 'plugins_loaded', 'um_init_messaging', -10, 1 );
function um_init_messaging() {
	if ( function_exists( 'UM' ) ) {
		UM()->set_class( 'Messaging_API', true );
	}
}