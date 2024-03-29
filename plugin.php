<?php
/**
 * Plugin Name: Starter Core Functionality
 * Plugin URI: https://github.com/CapWebSolutions/starter-core-functionality
 * Description: This contains all your site's core functionality so that it is theme independent.
 * Version: 2.0.1
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

namespace capweb;

// Define needed constants
define( 'CORE_FUNCTIONALITY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); //location of plugin folder on disk
define( 'CORE_FUNCTIONALITY_PLUGIN_URI', plugin_dir_url( __FILE__ ) );  //location of plugin folder in wp-content
define( 'CORE_FUNCTIONALITY_THEME_DIR', get_stylesheet_directory() );   // Used in checking location of logo file
define( 'CORE_FUNCTIONALITY_PLUGIN_VERSION',get_plugin_data(__FILE__ )['Version'] ); 

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
		'lib/templates/', // custom templates for metbox fields
		'lib/metabox-io-example.php', // TGMPA library and related for Metabox.io
	];

	foreach ( $files as $include ) {
		$include = trailingslashit( CORE_FUNCTIONALITY_PLUGIN_DIR ) . $include;
		// Allows inclusion of individual files or all .php files in a directory.
		if ( is_dir( $include ) ) {
			foreach ( glob( $include . '*.php' ) as $file ) {
				require $file;  // all php files from folder
			}
		} else {
			require $include;    // single php file
		}
	}
}
capweb_include_core_functionality_inc_files();
