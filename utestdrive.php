<?php
/** @noinspection PhpUnused */

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://booskills.com/rao
 * @since             1.0.0
 * @package           Utestdrive
 *
 * @wordpress-plugin
 * Plugin Name:       uTestDrive
 * Plugin URI:        https://wordpress.org/plugins/utestdrive/
 * Description:       The Plugin to create Demo Test Drive setup for plugin and theme authors
 * Requires PHP:      5.6
 * Requires at least: 4.7
 * Tested up to:      5.3
 * Version:           1.0.1
 * Author:            BooSpot
 * Author URI:        https://boospot.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       utestdrive
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'UTESTDRIVE_VERSION', '1.0.1' );

define( 'UTESTDRIVE_PLUGIN_NAME', 'utestdrive' );

/**
 * Plugin base name.
 * used to locate plugin resources primarily code files
 * Start at version 1.0.0
 */
/** @noinspection PhpUnused */
define( 'UTESTDRIVE_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );


/**
 * Plugin base dir path.
 * used to locate plugin resources primarily code files
 * Start at version 1.0.0
 */
/** @noinspection PhpUnused */
define( 'UTESTDRIVE_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin url to access its resources through browser
 * used to access assets images/css/js files
 * Start at version 1.0.0
 */
/** @noinspection PhpUnused */
define( 'UTESTDRIVE_URL_PATH', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'UTESTDRIVE_DEBUG_DISPLAY' ) ) {
	define( 'UTESTDRIVE_DEBUG_DISPLAY', false );
}


/**
 * Composer Auto Loader
 */
require 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-utestdrive-activator.php
 */
function utestdrive_activate() {
	Utestdrive\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-utestdrive-deactivator.php
 */
function utestdrive_deactivate() {
	Utestdrive\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'utestdrive_activate' );
register_deactivation_hook( __FILE__, 'utestdrive_deactivate' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_utestdrive() {

	$plugin = new Utestdrive\Init();
	$plugin->run();

}

run_utestdrive();