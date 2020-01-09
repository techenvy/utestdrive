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

		if ( empty( $this->user_name ) || empty( $this->user_email ) ) {
			$redirect = add_query_arg( 'message', urlencode( 'Email is not validated.' . $this->user_email ), $redirect );
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

		return wpmu_create_user( $this->user_name, $this->user_password, $this->user_email );
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

		$site_meta = array(
			$this->prefix . 'site_meta_1' => time()

		);

		$blog_id = wpmu_create_blog(
			$site_data['domain'],
			$site_data['path'],
			$site_data['title'],
			$this->user_id, $site_meta, $current_site->id
		);

		if ( is_wp_error( $blog_id ) ) {
			return false;
		}

		if ( ! is_super_admin( $this->user_id ) && get_user_option( 'primary_blog', $this->user_id ) == $current_site->blog_id ) {
			update_user_option( $this->user_id, 'primary_blog', $blog_id, true );
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


}
