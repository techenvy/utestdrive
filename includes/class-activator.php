<?php

namespace Utestdrive;
/**
 * Fired during plugin activation
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 * @author     Rao <rao@booskills.com>
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Activate only if its a multi site setup and main site of network
		if ( ! is_multisite() || ! is_main_site( get_current_blog_id() ) ) {
			deactivate_plugins( UTESTDRIVE_PLUGIN_BASE_NAME );
			wp_die(
				'<p>' .
				sprintf(
					'uTestDrive plugin requires %s, it can not do anything for a non-network installation',
					'<a href="https://wordpress.org/support/article/create-a-network/" target="_blank">' . esc_html__( 'WordPress Site Network', 'utestdrive' ) . '</a>'
				)
				. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . esc_html__( 'go back', '' ) . '</a>'
			);
		}

	}


}
