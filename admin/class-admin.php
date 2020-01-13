<?php

namespace Utestdrive;
use Boo_Settings_Helper;

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
	 * @hooked wpmu_blogs_columns
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function wpmu_blogs_columns( $columns ) {

		$columns['site_expiry'] = esc_html__( 'Expiry', 'utestdrive' );

		return $columns;

	}

	/**
	 * @hooked wpmu_blogs_columns
	 * @param $columns
	 * @return mixed
*/
	public function wpmu_users_columns( $columns ) {

		$columns['user_expiry'] = esc_html__( 'Expiry', 'utestdrive' );

		return $columns;

	}

	/**
	 * Get Value of custom columns
	 *
	 * @param string $value Custom column output.
	 * @param string $column_name The current column name.
	 * @param int $user_id ID of the currently-listed user.
	 *
	 * @return int|string
	 * @hooked manage_users_custom_column
	 */
	public function wpmu_users_columns_values( $value, $column_name, $user_id ) {

		if ( 'user_expiry' === $column_name ) {
			$user_expiry = get_user_meta( $user_id, 'utestdrive_schedule_delete_time', true );
			if ( is_super_admin( $user_id ) ) {
				$user_expiry = '';
			}
			$value = $this->get_expiry_time_text( $user_expiry );
		}

		return $value;
	}

	/**
	 * @param $expiry_timestamp
	 * @return string
*/
	public function get_expiry_time_text( $expiry_timestamp ) {

		if ( empty( $expiry_timestamp ) ) {
			$value = esc_html__( 'Never Expire', 'utestdrive' );
		} else {
			if ( $expiry_timestamp > time() ) {
				$value = human_time_diff( $expiry_timestamp );
			} else {
				$value = esc_html__( 'EXPIRED', 'utestdrive' ) . ' ' . human_time_diff( $expiry_timestamp ) . ' ' . esc_html__( 'ago', 'utestdrive' );
			}
		}

		return $value;

	}

	/**
	 * @hooked manage_sites_custom_column
	 * @param $column_name
	 * @param $blog_id
	 * @return mixed
*/
	public function wpmu_blogs_columns_values( $column_name, $blog_id ) {

		if ( 'site_expiry' === $column_name ) {
			$site_expiry = get_blog_option( $blog_id, 'utestdrive_schedule_delete_time' );
			echo $this->get_expiry_time_text( $site_expiry );
		}

		return $column_name;
	}


	/**
	 *
	 */
	public function admin_menu_simple() {

		$config_array = apply_filters( 'utestdrive_filter_config_settings', array(
			'options_id' => $this->plugin_name . '-general',
			'tabs'       => true,
			'prefix'     => $this->prefix,
			'menu'       => $this->get_settings_menu(),
			'links'      => $this->get_settings_links(),
			'sections'   => $this->get_settings_sections(),
			'fields'     => $this->get_settings_fields()
		) );


		$this->settings_api = new Boo_Settings_Helper( $config_array );

		//set the plugin action links
		$this->settings_api->set_links( $this->get_settings_links() );

		//initialize settings
		$this->settings_api->admin_init();

	}

	function get_settings_menu() {

		return apply_filters( 'utestdrive_filter_config_settings_menu', array(
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
			// For sub menu, we can define parent menu slug (Defaults to Options Page)
			'parent'          => 'options-general.php',
			// plugin_basename required to add plugin action links
			'plugin_basename' => plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ),
		) );

	}

	function get_settings_links() {
		return apply_filters( 'utestdrive_filter_config_settings_links', array(
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
		) );

	}

	function get_settings_sections() {
		return apply_filters( 'utestdrive_filter_config_settings_sections', array(
			array(
				'id'    => 'test_site',
				'title' => __( 'Test Site Settings', 'utestdrive' ),
			),
		) );

	}

	public function get_settings_fields() {
		$options_fields = array();
		/*
		* License Settings
		*/
		$options_fields['test_site'] = apply_filters( 'utestdrive_filter_config_settings_fields_test_site', array(

			array(
				'id'      => 'test_site_user_role',
				'type'    => 'user_roles',
				'label'   => __( 'User role to assign for test site creator', 'utestdrive' ),
				'desc'    => esc_html__( 'if a role other than "Administrator" is selected, new site will have administrator assigned from first available "Super Admin" users.', 'utestdrive' ),
				'default' => Globals::get_default_options( 'test_site_user_role' ),
			),

			array(
				'id'      => 'test_drive_expiry_in_hours',
				'type'    => 'number',
				'label'   => __( 'Test Drive Expiry', 'utestdrive' ),
				'desc'    => esc_html__( '(in hours). After the lapse of this period, test drive user and test drive site shall auto deleted if option enabled.', 'utestdrive' ),
				'default' => Globals::get_default_options( 'test_drive_expiry_in_hours' ),
				'options' => array(
					'step' => 0.1
				)
			),

			array(
				'id'      => 'auto_delete_test_site',
				'type'    => 'select',
				'label'   => esc_html__( 'Auto delete test Site?', 'utestdrive' ),
				'desc'    => sprintf( esc_html__( 'If enabled, %s shall auto delete after the expiry time specified.', 'utestdrive' ), esc_html__( 'test drive site', 'utestdrive' ) ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'utestdrive' ),
					'no'  => esc_html__( 'No', 'utestdrive' )
				),
				'default' => Globals::get_options_value( 'auto_delete_test_site' )
			),

			array(
				'id'      => 'auto_delete_test_user',
				'type'    => 'select',
				'label'   => esc_html__( 'Auto delete test User?', 'utestdrive' ),
				'desc'    => sprintf( esc_html__( 'If enabled, %s shall auto delete after the expiry time specified.', 'utestdrive' ), esc_html__( 'test drive user', 'utestdrive' ) ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'utestdrive' ),
					'no'  => esc_html__( 'No', 'utestdrive' )
				),
				'default' => Globals::get_options_value( 'auto_delete_test_user' )
			),


			array(
				'id'      => 'is_delete_orphan_users',
				'type'    => 'select',
				'label'   => esc_html__( 'Delete orphan users after auto deleting test site?', 'utestdrive' ),
				'desc'    => __( 'Delete all users who has no sites or content? These are typically created when test drive sites have created some users.', 'utestdrive' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'utestdrive' ),
					'no'  => esc_html__( 'No', 'utestdrive' )
				),
				'default' => Globals::get_options_value( 'is_delete_orphan_users' )
			),

		) );

		return apply_filters( 'utestdrive_filter_config_settings_fields', $options_fields );
	}


}
