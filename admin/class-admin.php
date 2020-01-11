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
				'id'    => 'create_site',
				'title' => __( 'Create Site', 'utestdrive' ),
//				'desc'  => 'this is sweet'
			),

			array(
				'id'    => 'delete_site',
				'title' => __( 'Delete Site', 'utestdrive' ),
			),
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
		$options_fields['create_site'] = apply_filters( 'utestdrive_filter_fields_settings_license', array(

			array(
				'id'      => $this->prefix . 'test_site_expiry_in_hours',
				'type'    => 'number',
				'label'   => __( 'Test Site Expiry', 'utestdrive' ),
				'desc'    => esc_html__( 'in hours', 'utestdrive' ),
				'default' => Globals::get_default_options( 'test_site_expiry_in_hours' ),
				'options' => array(
					'step' => 0.1
				)
			),

			array(
				'id'      => $this->prefix . 'test_site_user_role',
				'type'    => 'user_roles',
				'label'   => __( 'User role to assign', 'utestdrive' ),
				'desc'    => esc_html__( 'if a role other than "Administrator" is selected, new site will have administrator assigned from first available "Super Admin" users.', 'utestdrive' ),
				'default' => Globals::get_default_options( 'test_site_user_role' ),
			),

			array(
				'id'    => $this->prefix . 'key_length',
				'type'  => 'number',
				'label' => __( 'Length of Key', 'utestdrive' ),
				'desc'  => __( 'In normal cases, you dont want to change it. ', 'utestdrive' ) . " " . __( 'If filled, License key will be trimmed to length defined.', 'utestdrive' ) . " " . '0 => Unlimited',
			),

			array(
				'id'                => $this->prefix . 'key_prefix',
				'type'              => 'text',
				'label'             => __( 'Key Prefix', 'utestdrive' ),
				'radio'             => true,
				'desc'              => __( 'I dont know why you would like to use it. If filled, License keys will be prefixed with this.', 'utestdrive' ),
				'placeholder'       => __( 'In normal cases, you want to leave it empty.', 'utestdrive' ),
				'sanitize_callback' => 'sanitize_key'
			),


			array(
				'id'    => $this->prefix . 'defaults_heading',
				'type'  => 'html',
				'label' => '<h3>' . __( 'Set Defaults', 'utestdrive' ) . '</h3>',
			),

			array(
				'id'    => $this->prefix . 'max_domains',
				'type'  => 'number',
				'label' => esc_html__( 'Maximum allowed domains', 'utestdrive' ),
				'std'   => 1,
			),


			array(
				'id'      => $this->prefix . 'auto_expire_cron',
				'type'    => 'select',
				'label'   => esc_html__( 'Auto Expire Licenses', 'utestdrive' ),
				'default' => 'yes',
				'options' => array( 'yes' => 'Yes', 'no' => 'No' ),
				'desc'    => __( 'If active, licenses will auto expire once everyday', 'utestdrive' ),
			),

			array(
				'id'    => $this->prefix . 'is_debug_on',
				'type'  => 'checkbox',
				'label' => esc_html__( 'Log Debug information?', 'utestdrive' ),
				'desc'  => __( 'Check Error Logs for WordPress installation', 'utestdrive' )
			),

//			array(
//				'id'      => $this->prefix . 'enable_wysiwyg_editor',
//				'type'    => 'select',
//				'label'   => __( 'Enable WYSIWYG Editor?', 'utestdrive' ),
//				'options' => array( 'yes' => 'Yes', 'no' => 'No' ),
//				'radio'   => true,
//				'default' => 'no',
//				'desc'    => __( 'This will only be available for Short Description and Additional Notes', 'utestdrive' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_nutrition',
//				'type'              => 'select',
//				'label'             => __( 'Show Nutrition? (Global)', 'utestdrive' ),
//				'label_description' => __( 'Do you want to show Nutrition info in individual Recipe?', 'utestdrive' ),
//				'default'           => 'yes',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_icons',
//				'type'              => 'select',
//				'label'             => __( 'Show Icons?', 'utestdrive' ),
//				'label_description' => __( 'Do you want to show icons in individual Recipe?', 'utestdrive' ),
//				'default'           => 'yes',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_key_point_label',
//				'type'              => 'select',
//				'label'             => __( 'Show Labels for Key Points?', 'utestdrive' ),
//				'label_description' => __( 'Do you want to show labels for key points in individual Recipe?', 'utestdrive' ),
//				'default'           => 'yes',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'      => $this->prefix . 'ingredients_editor',
//				'type'    => 'select',
//				'label'   => __( 'Ingredients Editor', 'utestdrive' ),
//				'desc'    => __( 'More Styles in Premium Version', 'utestdrive' ),
//				'default' => 'textarea',
//				'options' => apply_filters( 'utestdrive_filter_options_field_ingredients_editor', array(
//					'textarea' => __( 'Simple Textarea', 'utestdrive' )
//				) )
//
//			),
//
//			array(
//				'id'                => $this->prefix . 'ingredient_side',
//				'type'              => 'select',
//				'label'             => __( 'Ingredients by the Side', 'utestdrive' ),
//				'label_description' => __( 'Do you Want to show ingredients by the side?', 'utestdrive' ),
//				'default'           => 'no',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'nutrition_side',
//				'type'              => 'select',
//				'label'             => __( 'Nutrition by the Side', 'utestdrive' ),
//				'label_description' => __( 'Do you Want to show nutrition by the side?', 'utestdrive' ),
//				'default'           => 'yes',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_featured_image',
//				'type'              => 'select',
//				'label'             => __( 'Show Featured Image?', 'utestdrive' ),
//				'label_description' => __( 'Some Themes add this to header, you may want to hide the one added by this plugin to avoid duplicated contents', 'utestdrive' ),
//				'default'           => $this->get_default_options( 'show_featured_image' ),
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_recipe_title',
//				'type'              => 'select',
//				'label'             => __( 'Show Recipe Title?', 'utestdrive' ),
//				'label_description' => __( 'Some Themes add this to header, you may want to hide the one added by this plugin to avoid duplicated contents', 'utestdrive' ),
//				'default'           => $this->get_default_options( 'show_recipe_title' ),
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_recipe_publish_info',
//				'type'              => 'select',
//				'label'             => __( 'Show Recipe Publish info?', 'utestdrive' ),
//				'label_description' => __( 'Some Themes add this to header, you may want to hide the one added by this plugin to avoid duplicated contents', 'utestdrive' ),
//				'default'           => $this->get_default_options( 'show_recipe_publish_info' ),
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_share_buttons',
//				'type'              => 'select',
//				'label'             => __( 'Show Share Buttons?', 'utestdrive' ),
//				'label_description' => __( 'Do you Want to show share buttons on recipe page?', 'utestdrive' ),
//				'default'           => $this->get_default_options( 'show_share_buttons' ),
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_author',
//				'type'              => 'select',
//				'label'             => __( 'Show Author', 'utestdrive' ),
//				'label_description' => __( 'Do you Want to show author name on recipe page?', 'utestdrive' ),
//				'default'           => 'yes',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'                => $this->prefix . 'show_published_date',
//				'type'              => 'select',
//				'label'             => __( 'Show Published Date', 'utestdrive' ),
//				'label_description' => __( 'Do you want to show published date on recipe page?', 'utestdrive' ),
//				'default'           => 'no',
//				'options'           => array( 'yes' => 'Yes', 'no' => 'No' ),
//			),
//
//			array(
//				'id'          => $this->prefix . 'featured_image_height',
//				'type'        => 'text',
//				'label'       => __( 'Featured image height', 'utestdrive' ),
////					'after'       => __("You will need to re-generate thumbnails after changing this value for existing recipes", "utestdrive"),
//				'description' => __( 'Maximum height of the recipe image', 'utestdrive' ),
//				'default'     => '576',
//				'sanitize'    => 'utestdrive_sanitize_absint',
//
//			),
//
//			array(
//				'id'          => $this->prefix . 'recipe_default_img_url',
//				'type'        => 'media',
//				'label'       => __( 'Recipe default image', 'utestdrive' ),
//				'description' => __( 'Paste the full url to the image you want to use', 'utestdrive' ),
//				'width'       => 768,
//				'height'      => 768,
//				'max_width'   => 768
//			),
//
//			array(
//				'id'          => $this->prefix . 'layout_max_width',
//				'type'        => 'number',
//				'label'       => __( 'Layout Max Width', 'utestdrive' ),
////					'after'       => __("You will need to re-generate thumbnails after changing this value for existing recipes", "utestdrive"),
//				'description' => __( 'in pixels', 'utestdrive' ),
//				'default'     => '1048',
//				'sanitize'    => 'utestdrive_sanitize_absint',
//
//			),
//
//			array(
//				'id'      => $this->prefix . 'recipe_layout',
//				'type'    => 'select',
//				'label'   => __( 'Recipe Layout', 'utestdrive' ),
//				'options' => array(
//					'full'  => __( 'Full', 'utestdrive' ),
//					'left'  => __( 'Left', 'utestdrive' ),
//					'right' => __( 'Right', 'utestdrive' ),
//				),
//				'radio'   => true,
//				'default' => 'full',
//			),
//
//			array(
//				'id'          => $this->prefix . 'recipe_slug',
//				'type'        => 'text',
//				'label'       => __( 'Recipe Slug', 'utestdrive' ),
//				'desc'        => sprintf( __( "You will need to re-save %spermalinks%s after changing this value", "utestdrive" ), '<a href=' . get_admin_url() . "options-permalink.php" . ' target="_blank">', '</a>' ),
//				'class'       => 'text-class',
//				'description' => __( 'the term that appears in url', 'utestdrive' ),
//				'default'     => 'recipe',
//				'attributes'  => array(
//					'rows' => 10,
//					'cols' => 5,
//				),
//				'help'        => 'only use small letters and underscores or dashes',
//				'sanitize'    => 'sanitize_key',
//
//			),


		) );

		$options_fields['delete_site'] = apply_filters( 'utestdrive_filter_fields_settings_delete_site', array(

			array(
				'id'      => $this->prefix . 'is_delete_orphan_users',
				'type'    => 'select',
				'label'   => esc_html__( 'Delete Orphan Users?', 'utestdrive' ),
				'desc'    => __( 'Delete all users who has no sites or content. These are typically created when test drive sites have created some users', 'utestdrive' ),
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
