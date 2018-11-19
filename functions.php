<?php
/*
Plugin Name: Change Upload Directory
Plugin URI: https://github.com/nwcybersolutions/Change-Upload-Directory
Description: Removes Screen Options Globally
Author: Northwest Cyber Solutions
Author URI: https://nwcybersolutions.com
Version: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: Change Upload Directory
Domain Path: /languages
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

// Check WordPress Version.
global $wp_version;

if ( ! is_admin() || is_multisite() || version_compare( $wp_version, '3.5' ) < 0 ) {
	return;
}

/*------------------------------------------------------------------------------------------------*/
/* !CONSTANTS =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

define( 'SF_UUPE_VERSION', '1.0.4' );


/*------------------------------------------------------------------------------------------------*/
/* !INIT ======================================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'load-options-media.php', 'uupe_init' );
add_action( 'load-options.php',       'uupe_init' );

function uupe_init() {
	if ( ! get_option( 'upload_url_path' ) && ! ( get_option( 'upload_path' ) !== 'wp-content/uploads' && get_option( 'upload_path' ) ) ) {
		register_setting( 'media', 'upload_path',     'esc_attr' );
		register_setting( 'media', 'upload_url_path', 'esc_url' );
		add_settings_field( 'upload_path',     __( 'Store uploads in this folder' ), 'uupe_upload_path',     'media', 'uploads', array( 'label_for' => 'upload_path' ) );
		add_settings_field( 'upload_url_path', __( 'Full URL path to files' ),       'uupe_upload_url_path', 'media', 'uploads', array( 'label_for' => 'upload_url_path' ) );
	}
}


function uupe_upload_path( $args ) {
	global $wp_version;
	?>
	<input name="upload_path" type="text" id="upload_path" value="<?php echo esc_attr( get_option( 'upload_path' ) ); ?>" class="regular-text code" />
	<p class="description"><?php
		if ( version_compare( $wp_version, '4.4' ) < 0 ) {
			_e( 'Default is <code>wp-content/uploads</code>' );
		} else {
			/* translators: %s: wp-content/uploads */
			printf( __( 'Default is %s' ), '<code>wp-content/uploads</code>' );
		}
	?></p>
	<?php
}


function uupe_upload_url_path( $args ) {
	?>
	<input name="upload_url_path" type="text" id="upload_url_path" value="<?php echo esc_attr( get_option( 'upload_url_path' ) ); ?>" class="regular-text code" />
	<p class="description"><?php _e( 'Configuring this is optional. By default, it should be blank.' ); ?></p>
	<?php
}
