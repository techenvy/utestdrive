<?php

namespace Utestdrive;

// if class already defined, bail out
use function Uschema\var_dump_pretty;

if ( class_exists( 'Utestdrive\Site_Delete' ) ) {
	return;
}

/**
 * The To Delete the sub site created for test drive
 *
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/public
 * @author     Rao <rao@booskills.com>
 */
class Site_Delete {


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
	 * Schedule cron job to auto delete site
	 * @hooked init
	 */
	public function hook_schedule_cron() {

		if ( ! wp_next_scheduled( 'utestdrive_cron_auto_delete_test_drive_blog' ) ) {
			wp_schedule_event( time(), 'hourly', 'utestdrive_cron_auto_delete_test_drive_blog' );
		}

	}

	/**
	 * Method to run on cron schedule
	 */
	public function cron_action_auto_delete_test_drive_blog() {

		$user_ids_to_delete = $this->get_user_ids_to_delete();


		if ( empty( $user_ids_to_delete ) ) {
			return null;
		}

		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		require_once( ABSPATH . 'wp-admin/includes/user.php' );

		foreach ( $user_ids_to_delete as $user_id ) {

			$blogs = get_blogs_of_user( $user_id );
			if ( ! empty( $blogs ) ) {
				foreach ( $blogs as $blog ) {
					// Delete WooCommerce data left behind
					if ( function_exists( 'woo_uninstall' ) ) {
						switch_to_blog( $blog->userblog_id );
						woo_uninstall();
						restore_current_blog();
					}
					// Delete Blog
					wpmu_delete_blog( $blog->userblog_id, true );
				}
			}
			wpmu_delete_user( $user_id );
		}

		return null;

	}

	/**
	 *
	 */
	public function get_user_ids_to_delete() {

		$user_ids_to_delete = array();

		$users_to_check = get_users( array(
			'blog_id'    => 0,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'utestdrive_schedule_delete_time',
					'value'   => time(),
					'compare' => '<='
				),
				array(
					'key'     => 'utestdrive_test_drive_user',
					'value'   => 1,
					'compare' => '=='
				)
			),
			'fields'     => array( 'ID' ),
		) );

		foreach ( $users_to_check as $user ) {
			// Make sure we are not including super_admin
			$user_ids_to_delete[] = ( ! is_super_admin( $user->ID ) ) ? $user->ID : '';
		}

		unset( $users_to_check );

		return array_filter( $user_ids_to_delete );

	}


}
