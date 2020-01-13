<?php

namespace Utestdrive;

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// if class already defined, bail out
if ( class_exists( 'Utestdrive\Shortcode' ) ) {
	return;
}


/**
 * This class will create meta boxes for Taxonomies
 *
 * @package    Utestdrive
 * @subpackage Utestdrive/includes
 * @author     Rao < support@utestdrive.io>
 */
class Shortcode {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	protected $version;

	protected $prefix;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param $plugin_name
	 * @param $version
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->prefix = Globals::get_meta_prefix();

	}

	/**
	 * @hooked utd_reg_form
	 *
	 * @param array $atts
	 * @param null $content
	 * @param string $tag
	 *
	 * @return false|string
	 */
	public function hook_display_form( $atts = [], $content = null, $tag = '' ) {


	    /*
	     * Bail out if shortcode called on a sub site
	     */
		if ( ! is_main_site( get_current_blog_id() ) ) {
			return sprintf(
				'<div class="utd-response-cont"><p class="error">%s</p></div>',
				esc_html__( 'This shortcode is intended for main site only.', 'utestdrive' )
			);
		}

		// start output
		ob_start();
		$hide_form = false;
		if ( isset( $_GET['test_setup'] ) ) {
			echo '<div class="utd-response-cont">';
			switch ( sanitize_key( $_GET['test_setup'] ) ) {
				case 'success':
					$hide_form = true;
					$message   = isset( $_GET['message'] ) && ! empty( $_GET['message'] ) ? urldecode( $_GET['message'] ) : sprintf( '<span>%s</span> %s', esc_html__( 'Success!', 'utestdrive' ), esc_html__( 'Please check your email for login details.', 'utestdrive' ) );
					echo '<p class="success">' . $message . '</p>';
					break;
				case 'failed' :
					$message = isset( $_GET['message'] ) ? urldecode( $_GET['message'] ) : esc_html__( 'Unknown Error Occurred', 'utestdrive' );
					echo '<p class="error">' . $message . '</p>';
					break;
			}
			echo '</div>';
		}

		if ( ! $hide_form ) :
			?>
            <form id="utd-reg-form" action="" method="POST">
                <fieldset>
                    <input name="utd_user" id="utd-user" type="text" required="required"/>
                    <label for="utd-user"><?php esc_html_e( 'Your Name', 'utestdrive' ); ?></label>
                </fieldset>
                <fieldset>
                    <input name="utd_email" id="utd-email" type="email" required="required"/>
                    <label for="utd-email"><?php esc_html_e( 'Your Email', 'utestdrive' ); ?></label>
                </fieldset>
                <fieldset>
					<?php wp_nonce_field( 'create_test_drive', '_nonce_test_drive' ); ?>
                    <input type="submit" name="utd_register_submit"
                           value="<?php esc_html_e( 'Create Test Drive Setup', 'utestdrive' ); ?>"/>
                </fieldset>
            </form>
		<?php
		endif;

		// return output
		return ob_get_clean();

	}


}
