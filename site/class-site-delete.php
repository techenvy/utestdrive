<?php

namespace Utestdrive;

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
	 * The prefix for this plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $prefix The prefix for this plugin.
	 */
	private $prefix;

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
	 * Method to run on cron schedule
	 *
	 * @hooked utestdrive_cron_auto_delete_test_drive_blog
	 */
	public function cron_action_auto_delete_test_drive_blog() {

		$this->delete_users_with_schedule_expiry();
		$this->delete_sites_with_schedule_expiry();
		$this->delete_orphan_users();

	}

	/**
	 *
	 */
	public function delete_users_with_schedule_expiry() {

		if ( 'yes' !== Globals::get_options_value( 'auto_delete_test_user' ) ) {
			return null;
		}

		$user_ids_to_delete = $this->get_expired_test_drive_users();

		if ( empty( $user_ids_to_delete ) ) {
			return null;
		}

		if ( ! function_exists( 'wpmu_delete_user' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/ms.php' );
		}

		foreach ( $user_ids_to_delete as $user_id ) {
			wpmu_delete_user( $user_id );
		}

	}

	/**
	 *
	 */
	public function get_expired_test_drive_users() {

		$user_ids_to_delete = array();

		$users_to_check = get_users( array(
			'blog_id'    => 0,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => $this->prefix . 'schedule_delete_time',
					'value'   => time(),
					'compare' => '<='
				),
				array(
					'key'     => $this->prefix . 'test_drive_user',
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

	/**
	 *
	 */
	public function delete_sites_with_schedule_expiry() {


		if ( 'yes' !== Globals::get_options_value( 'auto_delete_test_site' ) ) {
			return null;
		}

		$sites = $this->get_expired_test_drive_sites();

		$this->delete_sites( $sites );

	}

	/**
	 * Get Expired Test Drive Sites
	 */
	public function get_expired_test_drive_sites() {

		return $this->get_test_drive_sites( true );

	}

	/**
	 * get test drive sites
	 *
	 * @param bool $only_expired
	 *
	 * @return array
	 */
	public function get_test_drive_sites( $only_expired = false ) {

		$expired_sites = array();
		$all_sites     = get_sites();

		foreach ( $all_sites as $index => $site ) {

			if ( ! get_blog_option( $site->blog_id, $this->prefix . 'test_drive_site' ) ) {
				continue;
			}

			if ( ! $only_expired ) {
				$expired_sites[] = $site;
			} else {
				$schedule_delete_time = get_blog_option( $site->blog_id, $this->prefix . 'schedule_delete_time' );

				if ( $schedule_delete_time && $schedule_delete_time <= time() ) {
					$expired_sites[] = $site;
				}
			}


		}

		unset( $all_sites );

		return $expired_sites;

	}

	/**
	 * @param array $sites
	 */
	public function delete_sites( $sites ) {

		if ( ! function_exists( 'wpmu_delete_blog' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/ms.php' );
		}

		foreach ( $sites as $site ) {

			/* Get site_id in both cases:
			 * 1. when $sites are provided using get_blogs_of_user()
			 * 2. when $sites are provided using get_sites()
			 */

			$site_id = ( isset( $site->userblog_id ) ) ? $site->userblog_id : $site->blog_id;
			// Delete WooCommerce data left behind
			if ( function_exists( 'woo_uninstall' ) ) {
				switch_to_blog( $site_id );
				woo_uninstall();
				restore_current_blog();
			}
			// Delete Blog
			wpmu_delete_blog( $site_id, true );
		}

	}

	/**
	 * It will delete all users who has no sites or content
	 */
	public function delete_orphan_users() {

		if (
			'yes' !== Globals::get_options_value( 'auto_delete_test_site' )
			|| 'yes' !== Globals::get_options_value( 'is_delete_orphan_users' )
		) {
			return null;
		}

		require_once( ABSPATH . 'wp-admin/includes/user.php' );
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );

		$users_to_check = get_users( array(
			'blog_id'    => 0,
			'meta_query' => array(
				array(
					'key'     => 'primary_blog',
					'value'   => false,
					'compare' => '=='
				)
			),
			'fields'     => array( 'ID' ),
		) );

		foreach ( $users_to_check as $user ) {
			if ( empty( $this->get_test_drive_sites_of_user( $user->ID ) ) ) {
				wpmu_delete_user( $user->ID );
			}
		}

		unset( $users_to_check );

	}

	/**
	 * Get a list of test drive sites for a  given $user_id
	 *
	 * @param $user_id
	 *
	 * @return array
	 */
	public function get_test_drive_sites_of_user( $user_id ) {

		if ( ! function_exists( 'get_blogs_of_user' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/user.php' );
		}

		$sites = get_blogs_of_user( $user_id );

		foreach ( $sites as $index => &$site ) {
			if ( ! get_blog_option( $site->userblog_id, $this->prefix . 'test_drive_site' ) ) {
				$site = false;
			}
		}

		return array_filter( $sites );

	}

}
