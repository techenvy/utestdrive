<?php

if ( ! function_exists( 'var_export_pretty' ) ) :

	function var_export_pretty( $var ) {
		if ( ! UTESTDRIVE_DEBUG_DISPLAY ) {
			return null;
		}
		echo '<pre>';
		var_export( $var );
		echo '</pre>';
	}

endif;


if ( ! function_exists( 'var_dump_pretty' ) ) :

	function var_dump_pretty( $var, $color = '' ) {
		if ( ! UTESTDRIVE_DEBUG_DISPLAY ) {
			return null;
		}

		echo "<pre style='color : {$color}'>";
		var_export( $var );
		echo '</pre>';

	}

endif;

if ( ! function_exists( 'var_dump_die' ) ) :

	function var_dump_die( $var ) {
		if ( ! UTESTDRIVE_DEBUG_DISPLAY ) {
			return null;
		}
		echo '<pre>';
		var_export( $var );
		echo '</pre>';
		wp_die();
	}

endif;

if ( ! function_exists( 'us_write_log' ) ) :

	function us_write_log( $identifier, $log_line ) {

		$log_line = is_array( $log_line ) || is_object( $log_line ) ? var_export( $log_line, true ) : $log_line;
		$hash     = '';
		$fn       = UTESTDRIVE_DIR_PATH . '/' . $identifier . '-' . $hash . '.log';
		file_put_contents( $fn, date( 'Y-m-d H:i:s' ) . ' - ' . $log_line . PHP_EOL, FILE_APPEND );

	}

endif;

if ( ! function_exists( 'wp_roles_array' ) ) :

	function wp_roles_array() {
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $role => $details ) {
			$roles[ esc_attr( $role ) ] = translate_user_role( $details['name'] );
		}

		return $roles;
	}

endif;
