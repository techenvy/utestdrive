<?php

namespace Utestdrive;
/**
 * Fired during plugin deactivation
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 * @author     Rao <rao@booskills.com>
 */
class Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Un-Schedule Cron
		$timestamp = wp_next_scheduled( 'utestdrive_auto_delete_test_drive_blog' );
		wp_unschedule_event( $timestamp, 'utestdrive_auto_delete_test_drive_blog' );


	}

}
