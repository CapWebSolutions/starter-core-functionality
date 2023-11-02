<?php
/**
 * General
 *
 * This file contains any general functions
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/capwebsolutions/starter-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @copyright    Copyright (c) 2017, Matt Ryan
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Don't Update Plugin
 *
 * @since 1.0.0
 *
 * This prevents you being prompted to update if there's a public plugin
 * with the same name.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array  $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function capweb_core_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
		return $r; // Not a plugin update request. Bail immediately.
	}
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}
add_filter( 'http_request_args', 'capweb_core_functionality_hidden', 5, 2 );

// Enqueue / register needed scripts & styles
add_action( 'wp_enqueue_scripts', 'capweb_enqueue_needed_scripts' );
/**
 * Enque Needed Scripts
 * @since 1.0.0
 *
 * Enqueue scripts and styles needed by core functionality.
 *
 * @author Matt Ryan
 *
 * @param void
 * @return void
 */
function capweb_enqueue_needed_scripts() {
	wp_enqueue_style( 'core-functionality', CORE_FUNCTIONALITY_PLUGIN_DIR . 'assets/css/core-functionality.css', array(), null, true );
}

// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Remove theme and plugin editor links
add_action( 'admin_init','cws_hide_editor_and_tools' );
function cws_hide_editor_and_tools() {
	remove_submenu_page( 'themes.php','theme-editor.php' );
	remove_submenu_page( 'plugins.php','plugin-editor.php' );
}

// Add the filter and function, returning the widget title only if the first character is not "!"
// Author: Stephen Cronin
// Author URI: http://www.scratch99.com/
add_filter( 'widget_title', 'remove_widget_title' );
function remove_widget_title( $widget_title ) {
	if ( substr ( $widget_title, 0, 1 ) == '!' )
		return;
	else 
		return ( $widget_title );
}

/**
 * Remove Menu Items
 *
 * @since 1.0.0
 *
 * Remove unused menu items by adding them to the array.
 * See the commented list of menu items for reference.
 */
function capweb_remove_menus() {
	global $menu;
	$restricted = array( __( 'Links' ) );
	// Example:
	// $restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	end( $menu );
	while ( prev( $menu ) ) {
		$value = explode( ' ',$menu[ key( $menu ) ][0] );
		if ( in_array( $value[0] != null?$value[0]:'' , $restricted ) ) {unset( $menu[ key( $menu ) ] );}
	}
}
add_action( 'admin_menu', 'capweb_remove_menus' );

/**
 * Customize Admin Bar Items
 *
 * @since 1.0.0
 * @link http://wp-snippets.com/addremove-wp-admin-bar-links/
 */
function capweb_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'new-link', 'new-content' );
}
add_action( 'wp_before_admin_bar_render', 'capweb_admin_bar_items' );


/**
 * Customize Menu Order
 *
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 */
function capweb_custom_menu_order( $menu_ord ) {
	if ( ! $menu_ord ) { return true;
	}
	return array(
		'index.php', // this represents the dashboard link
		'edit.php?post_type=page', // the page tab
		'edit.php', // the posts tab
		'edit-comments.php', // the comments tab
		'upload.php', // the media manager
	);
}
add_filter( 'custom_menu_order', 'capweb_custom_menu_order' );
add_filter( 'menu_order', 'capweb_custom_menu_order' );

// Disable WPSEO columns on edit screen
add_filter( 'wpseo_use_page_analysis', '__return_false' );

// We will make use of widget_title filter to 
//dynamically replace custom tags with html tags

add_filter( 'widget_title', 'accept_html_widget_title' );
function accept_html_widget_title( $mytitle ) { 

  // The sequence of String Replacement is important!!
  
	$mytitle = str_replace( '[link', '<a', $mytitle );
	$mytitle = str_replace( '[/link]', '</a>', $mytitle );
    $mytitle = str_replace( ']', '>', $mytitle );

	return $mytitle;
}

//Move Yoast to the Bottom of editor screen
function capweb_move_yoast_to_bottom() {
    return 'low';
}
add_filter( 'wpseo_metabox_prio', 'capweb_move_yoast_to_bottom');


 
//Auto Add Alt Tags
/* Automatically set the image Title, Alt-Text, Caption & Description upon upload
--------------------------------------------------------------------------------------*/
add_action( 'add_attachment', 'capweb_set_image_meta_upon_image_upload' );
function capweb_set_image_meta_upon_image_upload( $post_ID ) {
 
	// Check if uploaded file is an image, else do nothing
 
	if ( wp_attachment_is_image( $post_ID ) ) {
 
		$my_image_title = get_post( $post_ID )->post_title;
 
		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );
 
		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$my_image_title = ucwords( strtolower( $my_image_title ) );
 
		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'		=> $post_ID,			// Specify the image (ID) to be updated
			'post_title'	=> $my_image_title,		// Set image Title to sanitized title
			//'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
			//'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
		);
 
		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );
 
		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );
 
	} 
}
