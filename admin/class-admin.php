<?php

namespace Utestdrive;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/admin
 * @author     Rao <rao@booskills.com>
 */
class Admin {

	protected $prefix;
	protected $settings_api;
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
	 * @param string $plugin_name The name of this plugin.
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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Utestdrive_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Utestdrive_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Utestdrive_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Utestdrive_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 *
	 */
	public function admin_menu_simple() {

		$config_array = array(
			'options_id' => $this->plugin_name . '-general',
			'tabs'       => true,
			'menu'       => $this->get_settings_menu(),
			'links'      => $this->get_settings_links(),
			'sections'   => $this->get_settings_sections(),
			'fields'     => $this->get_settings_fields()
		);


		$this->settings_api = new \Boo_Settings_Helper( $config_array );

		//set menu settings
//			$this->settings_api->set_menu( $this->get_settings_menu() );

		//set the plugin action links
		$this->settings_api->set_links( $this->get_settings_links() );

		//set the settings
//			$this->settings_api->set_sections( $this->get_settings_sections_new() );

		// set fields
//			$this->settings_api->set_fields( $this->get_settings_fields_new() );

		//initialize settings
		$this->settings_api->admin_init();

//			add_options_page( 'WeDevs Settings API', 'WeDevs Settings API', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
	}

	function get_settings_menu() {
		$config_menu = array(
			//The name of this page
			'page_title'      => __( 'uTestDrive', 'utestdrive' ),
			// //The Menu Title in Wp Admin
			'menu_title'      => __( 'uTestDrive', 'utestdrive' ),
			// The capability needed to view the page
			'capability'      => 'manage_network',
			// Slug for the Menu page
			'slug'            => 'utestdrive-settings',
			// Required for submenu
			'submenu'         => true,
			// position
//			'position'   => 10,
			// For sub menu, we can define parent menu slug (Defaults to Options Page)
			'parent'          => 'options-general.php',
			// plugin_basename required to add plugin action links
			'plugin_basename' => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
		);

		return $config_menu;
	}

	function get_settings_links() {
		$links = array(

			'plugin_basename' => plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_name . '.php' ),

			// array of Settings
			'action_links'    => array(
				array(
					'text' => __( 'Configure', 'utestdrive' ),
					'type' => 'default',
				),
				array(
					'text' => __( 'Github Repo', 'utestdrive' ),
					'url'  => 'https://github.com/boospot/utestdrive',
					'type' => 'external',
				),
			),


		);

		return $links;
	}

	function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'settings_test_site',
				'title' => __( 'Test Site Settings', 'utestdrive' ),
			),

//			array(
//				'id'    => 'delete_site',
//				'title' => __( 'Delete Site', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'settings_api',
//				'title' => __( 'API', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'settings_woocommerce',
//				'title' => __( 'WooCommerce', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'settings_envato',
//				'title' => __( 'Envato', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'recipe_search_form',
//				'title' => __( 'Search Form', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'recipe_widgets',
//				'title' => __( 'Widget Settings', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'recipe_options_backup_restore',
//				'title' => __( 'Settings Backup', 'utestdrive' ),
//			),
//			'recipe_plugin_activation' => array(
//				'id'    => 'recipe_plugin_activation',
//				'title' => __( 'Premium Plugin', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'special_section',
//				'title' => __( 'Special', 'utestdrive' ),
//			),
//			array(
//				'id'    => 'uninstall_section',
//				'title' => __( 'Uninstall', 'utestdrive' ),
//			)
		);

		return apply_filters( 'utestdrive_filter_options_sections_array', $sections );
	}

	public function get_settings_fields() {
		$options_fields = array();
		/*
		* License Settings
		*/
		$options_fields['settings_test_site'] = apply_filters( 'utestdrive_filter_fields_settings_test_site', array(

			array(
				'id'      => $this->prefix . 'test_site_user_role',
				'type'    => 'user_roles',
				'label'   => __( 'User role to assign for test site creator', 'utestdrive' ),
				'desc'    => esc_html__( 'if a role other than "Administrator" is selected, new site will have administrator assigned from first available "Super Admin" users.', 'utestdrive' ),
				'default' => Globals::get_default_options( 'test_site_user_role' ),
			),

			array(
				'id'      => $this->prefix . 'test_site_expiry_in_hours',
				'type'    => 'number',
				'label'   => __( 'Expiry of Test Site', 'utestdrive' ),
				'desc'    => esc_html__( '(in hours). After the lapse of this period, site shall auto deleted if option enabled.', 'utestdrive' ),
				'default' => Globals::get_default_options( 'test_site_expiry_in_hours' ),
				'options' => array(
					'step' => 0.1
				)
			),

			array(
				'id'      => $this->prefix . 'auto_delete_test_site',
				'type'    => 'select',
				'label'   => esc_html__( 'Auto delete test site?', 'utestdrive' ),
				'desc'    => __( 'If enabled, test site shall auto delete after the expiry time specified.', 'utestdrive' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'utestdrive' ),
					'no'  => esc_html__( 'No', 'utestdrive' )
				),
				'default' => Globals::get_options_value( 'auto_delete_test_site' )
			),


			array(
				'id'      => $this->prefix . 'is_delete_orphan_users',
				'type'    => 'select',
				'label'   => esc_html__( 'Delete orphan users when auto deleting test site?', 'utestdrive' ),
				'desc'    => __( 'Delete all users who has no sites or content? These are typically created when test drive sites have created some users.', 'utestdrive' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'utestdrive' ),
					'no'  => esc_html__( 'No', 'utestdrive' )
				),
				'default' => Globals::get_options_value( 'is_delete_orphan_users' )
			),

		) );

		return apply_filters( 'utestdrive_filter_options_fields_array', $options_fields );
	}


}
