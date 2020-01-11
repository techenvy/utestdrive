<?php

namespace Utestdrive;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 * @author     Rao <rao@booskills.com>
 */
class Init {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;


	protected $prefix;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {


		if ( defined( 'UTESTDRIVE_VERSION' ) ) {
			$this->version = UTESTDRIVE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		if ( defined( 'UTESTDRIVE_PLUGIN_NAME' ) ) {
			$this->plugin_name = UTESTDRIVE_PLUGIN_NAME;
		} else {
			$this->plugin_name = 'utestdrive';
		}


		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_taxonomy_hooks();
		$this->define_shortcode_hooks();
		$this->define_site_create_hooks();
		$this->define_site_delete_hooks();


		do_action( 'utestdrive_init_construct' );

		$this->prefix = Globals::get_meta_prefix();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - i18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/* No Need to Load anything as autoloader is generated by Composer*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper-functions.php';

		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18N();

		/** @noinspection SpellCheckingInspection */
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		if ( ! is_admin() ) {
			return null;
		}

		$plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/*
		 * Added the plugin options menu and page
		 */
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu_simple', 99 );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		if ( is_admin() ) {
			return null;
		}

		$plugin_public = new Front( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to taxonomies
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_taxonomy_hooks() {

//		$plugin_taxonomies = new Taxonomy();


	}

	/**
	 * Register Shortcodes
	 */
	public function define_shortcode_hooks() {

		$plugin_shortcode = new Shortcode( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_shortcode(
			"utd_reg_form",
			$plugin_shortcode,
			"hook_display_form"
		);


	}

	/**
	 * Register all of the hooks related to site creation
	 *
	 * @since    1.0.0
	 */
	public function define_site_create_hooks() {

		$site_create = new Site_Create( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $site_create, 'init' );


	}

	/**
	 * Register all of the hooks related to delete site
	 *
	 * @since    1.0.0
	 */
	public function define_site_delete_hooks() {

		$site_delete = new Site_Delete( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp', $site_delete, 'hook_schedule_cron' );

		$this->loader->add_action(
			$this->prefix . 'cron_auto_delete_test_drive_blog',
			$site_delete,
			'cron_action_auto_delete_test_drive_blog'
		);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


}
