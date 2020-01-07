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

		if ( ! isset( $_REQUEST['create_site'] ) ) {
			return null;
		}

		$nonce = $_REQUEST['_nonce_test_drive'] ?? '';
		if ( ! wp_verify_nonce( $nonce, 'create_test_drive' ) ) {
			wp_die( $this->get_error_message( 'invalid_nonce' ) );

			return null;
		};

		$this->set_user_details();
		$this->user_id = $this->create_user();

		if ( ! empty( $this->user_id ) ) {
			$this->create_site();
		}

		$this->send_response( 'site_created', true );

	}

	/**
	 *
	 */
	public function get_error_message( $code ) {

		$errors = array(
			'invalid_nonce' => esc_html__( 'Security token invalid', 'utestdrive' )
		);

		return $errors[ $code ] ?? esc_html__( 'Unknown Error occurred', 'utestdrive' );


	}

	/**
	 *
	 */
	public function set_user_details() {

		$this->user_email = isset( $_REQUEST['email'] ) && is_email( sanitize_email( $_REQUEST['email'] ) ) ? sanitize_email( $_REQUEST['email'] ) : '';
		$this->user_name  = isset( $_REQUEST['user'] ) ? sanitize_text_field( $_REQUEST['user'] ) : '';

	}

	/**
	 *
	 */
	public function create_user() {

		if ( empty( $this->user_name ) || empty( $this->user_email ) ) {
			return false;
		} else {
			$this->user_password = wp_generate_password();

			return wpmu_create_user( $this->user_name, $this->user_password, $this->user_email );
		}

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

		$blog_address = '';
		$address      = sanitize_key( $this->user_email );

		if ( ! preg_match( '/(--)/', $address ) && preg_match( '|^([a-zA-Z0-9-])+$|', $address ) ) {
			$blog_address = strtolower( $address );
		}

		$blog_title = $this->user_name;

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
			'title'  => $blog_title,
			'email'  => $this->user_email
		);
	}

	/**
	 *
	 */
	public function send_response( $code = '', $success = false ) {

		if ( ! $success ) {
			$msg = $this->get_error_message( $code );
			wp_safe_redirect( 'https://SUpertest.com/?' . 'site_create_error=' . urlencode( $msg ) );
		}


	}


}
