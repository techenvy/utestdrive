<?php

namespace Utestdrive;

/**
 * The functionality of the plugin to create site.
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/public
 */
// if class already defined, bail out
if ( class_exists( 'Utestdrive\Site_Create' ) ) {
	return;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/public
 * @author     Rao <rao@booskills.com>
 */
class Site_Create {


	protected $user_name;
	protected $user_email;
	protected $user_id;
	protected $user_password;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->prefix      = Globals::get_meta_prefix();

	}

	/**
	 *
	 */
	public function init() {

		if ( ! isset( $_REQUEST['utd_register_submit'] ) ) {
			return null;
		}

		$nonce    = $_REQUEST['_nonce_test_drive'] ?? '';
		$redirect = add_query_arg( 'test_setup', 'failed', parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

		if ( ! wp_verify_nonce( $nonce, 'create_test_drive' ) ) {
			$redirect = add_query_arg( 'message', urlencode( $this->get_error_message( 'invalid_nonce' ) ), $redirect );
			wp_safe_redirect( $redirect );
			exit;
		};

		$this->set_user_details();

		if ( empty( $this->user_name ) || in_array( $this->user_name, $this->get_invalid_user_name_list(), true ) ) {
			$redirect = add_query_arg( 'message', urlencode( $this->get_error_message( 'invalid_user_name' ) ), $redirect );
			wp_safe_redirect( $redirect );
			exit;
		}

		if ( empty( $this->user_email ) ) {
			$redirect = add_query_arg( 'message', urlencode( $this->get_error_message( 'invalid_email' ) ), $redirect );
			wp_safe_redirect( $redirect );
			exit;
		}

		$this->user_id = $this->create_user();

		if ( ! $this->user_id ) {
			$redirect = add_query_arg( 'message', urlencode( $this->get_error_message( 'user_exist' ) . var_export( $this->user_id, true ) ), $redirect );
			wp_safe_redirect( $redirect );
			exit;
		}

		$blog_id = $this->create_site();

		if ( ! $blog_id ) {
			$redirect = add_query_arg( 'message', urlencode( $this->get_error_message( 'create_site' ) ), $redirect );
			wp_safe_redirect( $redirect );
			exit;
		}

		$redirect = add_query_arg( 'test_setup', 'success', $redirect );
		$redirect = add_query_arg( 'message', apply_filters( 'utestdrive_success_message', '' ), $redirect );

		wp_safe_redirect( $redirect );
		exit;

	}

	/**
	 *
	 */
	public function get_error_message( $code ) {

		$errors = apply_filters( 'utestdrive_error_messages_list', array(
			'invalid_nonce'     => esc_html__( 'Security token invalid', 'utestdrive' ),
			'user_exist'        => esc_html__( 'User already exist with provided email. Please try with another user details', 'utestdrive' ),
			'create_site'       => esc_html__( 'There was an error creating site. Please try with different Email.', 'utestdrive' ),
			'invalid_email'     => esc_html__( 'Please provide a valid email or use another email address.', 'utestdrive' ),
			'invalid_user_name' => esc_html__( 'Please provide a valid name', 'utestdrive' ),
		) );

		return $errors[ $code ] ?? esc_html__( 'Unknown Error occurred', 'utestdrive' );

	}

	/**
	 *
	 */
	public function set_user_details() {

		$this->user_email = isset( $_REQUEST['utd_email'] ) && ! empty( sanitize_email( $_REQUEST['utd_email'] ) )
			? sanitize_email( $_REQUEST['utd_email'] )
			: '';
		$this->user_name  = isset( $_REQUEST['utd_user'] ) ? sanitize_text_field( $_REQUEST['utd_user'] ) : '';

		$this->user_name = $this->generate_unique_username( $this->user_name );
	}

	/**
	 * @source https://gist.github.com/philipnewcomer/59a695415f5f9a2dd851deda42d0552f
	 *
	 * Recursive function to generate a unique username.
	 *
	 * If the username already exists, will add a numerical suffix which will increase until a unique username is found.
	 *
	 * @param string $username
	 *
	 * @return string The unique username.
	 */
	function generate_unique_username( $username ) {

		$username = sanitize_user( $username );

		static $i;
		if ( null === $i ) {
			$i = 1;
		} else {
			$i ++;
		}
		if ( ! username_exists( $username ) ) {
			return $username;
		}
		$new_username = sprintf( '%s-%s', $username, $i );
		if ( ! username_exists( $new_username ) ) {
			return $new_username;
		} else {
			return call_user_func( array( $this, 'generate_unique_username' ), $username );
		}
	}

	/**
	 *
	 */
	public function get_invalid_user_name_list() {

		return apply_filters( 'utestdrive_invalid_user_name_list', array(
			'admin',
			'administrator',
			'demo',
			'demo123',
			'guest',
			'guest123',
			'test',
			'test123
			',
		) );
	}

	/**
	 *
	 */
	public function create_user() {
		// Check if user exists
		if ( email_exists( $this->user_email ) ) {
			return false;
		}

		$this->user_password = wp_generate_password();

		$user_id = wpmu_create_user( $this->user_name, $this->user_password, $this->user_email );

		if ( $user_id ) {
			foreach ( $this->get_new_user_meta() as $meta_key => $meta_value ) {
				update_user_meta( $user_id, $meta_key, $meta_value );
			}
		}

		return $user_id;
	}

	/**
	 *
	 */
	public function get_new_user_meta() {

		return apply_filters( 'utestdrive_new_user_meta_list', array(
			'utestdrive_create_time'          => time(),
			'utestdrive_schedule_delete_time' => time() + ( $this->get_expiry_in_hours() * HOUR_IN_SECONDS ),
			'utestdrive_test_drive_user'      => 1,
		) );

	}

	/**
	 *
	 */
	public function get_expiry_in_hours() {
		return Globals::get_options_value( 'test_site_expiry_in_hours' );
	}

	/**
	 * Create Multi Site
	 */
	public function create_site() {

		$site_data = $this->get_site_data();
		if ( ! $site_data ) {
			return false;
		}

		global $current_site;

		$user_role = sanitize_key( Globals::get_options_value( 'test_site_user_role' ) );
		/*
		 * Make sure role exists, else assign 'administrator'
		 */
		$user_role = ( ! empty( get_role( $user_role ) ) ) ? $user_role : 'administrator';

		$is_admin_role = ( 'administrator' === $user_role );

		/*
		 * if the required role is not admin, then get a user form super admin users to assign as admin
		 */
		if ( ! $is_admin_role ) {
			require_once( ABSPATH . 'wp-includes/capabilities.php' );
			$blog_admin_user_id = get_user_by( 'login', array_pop( get_super_admins() ) )->ID;
		} else {
			$blog_admin_user_id = $this->user_id;
		}

		$blog_id = wpmu_create_blog(
			$site_data['domain'],
			$site_data['path'],
			$site_data['title'],
			$blog_admin_user_id,
			$this->get_new_site_options(),
			$current_site->id
		);

		if ( is_wp_error( $blog_id ) ) {
			return false;
		}

		if ( ! is_super_admin( $this->user_id ) ) {
			if ( get_user_option( 'primary_blog', $this->user_id ) == $current_site->id ) {
				update_user_option( $this->user_id, 'primary_blog', $blog_id, true );
			} else {
				add_user_to_blog( $blog_id, $this->user_id, $user_role );
				update_user_option( $this->user_id, 'primary_blog', $blog_id, true );
			}
		}

		wpmu_welcome_notification( $blog_id, $this->user_id, $this->user_password, $site_data['title'], array( 'public' => 1 ) );

		return $blog_id;

	}

	public function get_site_data() {
		global $current_site;

		$blog_address = sanitize_key( $this->user_email );

		if ( empty( $blog_address ) || empty( $this->user_email ) || ! is_email( $this->user_email ) ) {
			return array();
		}

		if ( is_subdomain_install() ) {
			$blog_domain = $blog_address . '.' . preg_replace( '|^www\.|', '', $current_site->domain );
			$path        = $current_site->path;
		} else {
			$blog_domain = $current_site->domain;
			$path        = trailingslashit( $current_site->path ) . $blog_address . '/';
		}

		return array(
			'domain' => $blog_domain,
			'path'   => $path,
			'title'  => $this->user_name,
			'email'  => $this->user_email
		);
	}

	/**
	 *
	 */
	public function get_new_site_options() {

		return apply_filters( 'utestdrive_new_site_options_list', array(
			'utestdrive_create_time'          => time(),
			'utestdrive_schedule_delete_time' => time() + ( $this->get_expiry_in_hours() * HOUR_IN_SECONDS ),
			'utestdrive_test_drive_site'      => 1,
		) );

	}


}
