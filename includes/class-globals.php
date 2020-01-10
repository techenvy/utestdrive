<?php /** @noinspection PhpUnused */

/** @noinspection CheckEmptyScriptTag */

namespace Utestdrive;
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// if class already defined, bail out
if ( class_exists( 'Utestdrive\Globals' ) ) {
	return;
}

/**
 * Class Globals
 * Defining Global Utility Functions
 */
class Globals {

	/**
	 * The Plugin Options
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var
	 */
	protected static $options = array();
	protected static $prefix = 'utestdrive_';
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private static $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private static $version;

	/**
	 * Globals constructor.
	 *
	 * @param $plugin_name
	 * @param $version
	 */
	public function __construct( $plugin_name, $version ) {

		self::$plugin_name = $plugin_name;
		self::$version     = $version;


	} // __construct()

	/** @noinspection PhpUnused */
	/**
	 * @param $option_id
	 *
	 * @param $option_key
	 *
	 * @return bool|mixed
	 */
	public static function get_options_value( $option_id, $option_key = '' ) {

		$options_value = self::get_options( $option_id );


		if ( false !== $options_value ) {
			$options_value = ( ! empty( $option_key ) && isset( $options_value[ $option_key ] ) ) ? $options_value[ $option_key ] : $options_value;
		} else {
			$options_value = self::get_default_options( $option_id );
		}

		return $options_value;
	}

	/**
	 * @param $option_id
	 *
	 * @return mixed
	 */
	public static function get_options( $option_id ) {

		self::set_options( $option_id );

		return self::$options[ $option_id ];

	}

	/**
	 * Sets the private var $options
	 *
	 * @param $option_id
	 */
	protected static function set_options( $option_id ) {

		// Only set options if not already set
		if ( ! isset( self::$options[ $option_id ] ) ) {
			self::$options[ $option_id ] = get_option( self::$prefix . $option_id );
		}

	}

	/**
	 * @param $key
	 *
	 * @return bool|mixed
	 */
	public static function get_default_options( $key ) {

		$default_options = self::get_default_options_array();

		return isset( $default_options[ $key ] ) ? $default_options[ $key ] : false;
	}

	/**
	 * Plugin Default Options
	 *
	 * @return array
	 */
	public static function get_default_options_array() {

		$default_options = array(
			'test_site_expiry_in_hours' => 48
		);

		$default_options = apply_filters( 'utestdrive_admin_settings_default', $default_options );

		return $default_options;
	}


	/** @noinspection PhpUnused */
	public static function get_meta_prefix() {
		return apply_filters( 'utestdrive_global_meta_prefix', static::$prefix );
	}


} // class
