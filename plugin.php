<?php
/**
 * Plugin Name: Starter Core Functionality
 * Plugin URI: https://github.com/CapWebSolutions/starter-core-functionality
 * Description: This contains all your site's core functionality so that it is theme independent. Customized by capwebsolutions.com.
 * Version: 2.0.0
 * Author: Cap Web Solutions
 * Author URI: https://capwebsolutions.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// namespace capweb;

// Plugin Directory
define( 'CORE_FUNCTIONALITY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if( ! class_exists( 'Gamajo_Template_Loader' ) ) {
	require CORE_FUNCTIONALITY_PLUGIN_DIR . 'includes/class-gamajo-template-loader.php';
}


/**
 * Get all the include files for the theme.
 *
 * @author CapWebSolutions
 */
function capweb_include_core_functionality_inc_files() {
	$files = [
		'lib/functions/',
		'lib/metabox/', // Custom Post Types, Taxonomy, Display
		'lib/templates/display-custom-fields.php',
		'lib/metabox-io-example.php', // TGMPA library and related for Metabox.io
	];

	foreach ( $files as $include ) {
		// $include = trailingslashit( get_stylesheet_directory() ) . $include;
		$include = trailingslashit( CORE_FUNCTIONALITY_PLUGIN_DIR ) . $include;

		// error_log( ' $include' . var_export( $include, true ) );  // Log file to debug 
		// Allows inclusion of individual files or all .php files in a directory.
		if ( is_dir( $include ) ) {
			foreach ( glob( $include . '*.php' ) as $file ) {
				require $file;
			}
		} else {
			require $include;
		}
	}
}
capweb_include_core_functionality_inc_files();
