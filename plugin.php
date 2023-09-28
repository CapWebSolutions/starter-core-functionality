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

// Taxonomies
include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/taxonomies.php' );

// General
include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/general.php' );
include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/helper-functions.php' );
include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/last-login.php' );

// Post Types
include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/post-types.php' );


// TGMPA library and related for Metabox.io
include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'metabox/example.php' );

// Woo tweaks. Only if WooCommerce active.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/wootweaks.php' );
}

// Gravity Forms tweaks. This should always be used if Gravity Forms active. Which one to use??
if ( in_array( 'gravityforms/gravityforms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include_once( CORE_FUNCTIONALITY_PLUGIN_DIR . 'lib/functions/gravitytweaks.php' );
}