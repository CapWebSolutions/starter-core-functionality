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
function be_core_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
		return $r; // Not a plugin update request. Bail immediately.
	}
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}
add_filter( 'http_request_args', 'be_core_functionality_hidden', 5, 2 );

// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Add Genesis theme support for WooCommerce
add_theme_support( 'genesis-connect-woocommerce' );

// Remove theme and plugin editor links
add_action( 'admin_init','cws_hide_editor_and_tools' );
function cws_hide_editor_and_tools() {
	remove_submenu_page( 'themes.php','theme-editor.php' );
	remove_submenu_page( 'plugins.php','plugin-editor.php' );
}

/*
 * Prevent the Jetpack publicize connections from being auto-selected,
 * so you need to manually select them if you’d like to publicize something.
 * Source: http://jetpack.me/2013/10/15/ever-accidentally-publicize-a-post-that-you-didnt/
 */
add_filter( 'publicize_checkbox_default', '__return_false' );

// Re-enable links manager. Source: http://codex.wordpress.org/Links_Manager
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * Remove Menu Items
 *
 * @since 1.0.0
 *
 * Remove unused menu items by adding them to the array.
 * See the commented list of menu items for reference.
 */
function be_remove_menus() {
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
add_action( 'admin_menu', 'be_remove_menus' );

/**
 * Customize Admin Bar Items
 *
 * @since 1.0.0
 * @link http://wp-snippets.com/addremove-wp-admin-bar-links/
 */
function be_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'new-link', 'new-content' );
}
add_action( 'wp_before_admin_bar_render', 'be_admin_bar_items' );


/**
 * Customize Menu Order
 *
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 */
function be_custom_menu_order( $menu_ord ) {
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
add_filter( 'custom_menu_order', 'be_custom_menu_order' );
add_filter( 'menu_order', 'be_custom_menu_order' );

// Disable WPSEO columns on edit screen
add_filter( 'wpseo_use_page_analysis', '__return_false' );

// * Automatically link Twitter names to Twitter URL
// Ref: https://www.nutsandboltsmedia.com/how-to-create-a-custom-functionality-plugin-and-why-you-need-one/
function twtreplace( $content ) {
	$twtreplace = preg_replace( '/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/','$1<a href="http://twitter.com/$2" target="_blank" rel="nofollow">@$2</a>',$content );
	return $twtreplace;
}
add_filter( 'the_content', 'twtreplace' );
add_filter( 'comment_text', 'twtreplace' );

//
// Force  IE to NOT use compatibility mode
// Ref: https://www.nutsandboltsmedia.com/how-to-create-a-custom-functionality-plugin-and-why-you-need-one/
add_filter( 'wp_headers', 'wsm_keep_ie_modern' );
function wsm_keep_ie_modern( $headers ) {
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false ) ) {
		$headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
	}
		return $headers;
}
//
// * Customize search form input box text
// * Ref: https://my.studiopress.com/snippets/search-form/
add_filter( 'genesis_search_text', 'sp_search_text' );
function sp_search_text( $text ) {
	// return esc_attr( 'Search my blog...' );
	return esc_attr( 'Search ' . get_bloginfo( $show = '', 'display' ) );
	get_permalink();
}

//
// Enqueue / register needed scripts
// Load Font Awesome
add_action( 'wp_enqueue_scripts', 'cws_enqueue_needed_scripts' );
function cws_enqueue_needed_scripts() {
	// font-awesome
	// Ref: application of these fonts: https://sridharkatakam.com/using-font-awesome-wordpress/
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
}

//
// Add custom logo to login page
// Requires a transparent logo file in the theme's images folder named 'login_logo.png'
add_action( 'login_head', 'custom_loginlogo' );
function custom_loginlogo() {
	echo '<style type="text/css">
h1 a {background-image: url(' . get_bloginfo( 'template_directory' ) . '/images/login_logo.png) !important; }
</style>';
}

// Custom avatar_size
add_filter( 'avatar_defaults', 'add_custom_gravatar' );
function add_custom_gravatar( $avatar_defaults ) {
	 $myavatar = get_stylesheet_directory_uri() . '/images/custom-gravatar.jpg';
	 $avatar_defaults[ $myavatar ] = 'Custom Gravatar';
	 return $avatar_defaults;
}

add_filter( 'comment_form_defaults', 'cd_pre_comment_text' );
/**
 * Change the text output that appears before the comment form
 * Note: Logged in user will not see this text.
 *
 * @author Carrie Dils <http://www.carriedils.com>
 * @uses comment_notes_before <http://codex.wordpress.org/Function_Reference/comment_form>
 *  ref: http://www.carriedils.com/customize-wordpress-comments/
 */
function cd_pre_comment_text( $arg ) {
	$arg['comment_notes_before'] = "Want to see your pic by your comment? Get a free custom avatar at <a href='http://www.gravatar.com' target='_blank' >Gravatar</a>.";
	return $arg;
}

// ref: http://www.carriedils.com/customize-wordpress-comments/
add_action( 'pre_ping', 'disable_self_ping' );
function disable_self_ping( &$links ) {
	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
			unset( $links[ $l ] );
		}
	}
}


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

// Custom 404 Pages ===================================================
// Call the sitemap generator
// Source: http://www.carriedils.com/custom-404-wordpress-html-sitemap/
// remove_action( 'genesis_loop', 'genesis_404' ); // Remove the default Genesis 404 content
add_action( 'genesis_loop', 'cd_custom_404' ); // Add function for custom 404 content
function cd_custom_404() {
	if ( is_404() ) {
		get_template_part( '/partials/sitemap' ); // Plop in our customized sitemap code
	}
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
 * Remove title from individual pages. 
 */
// add_action( 'get_header', 'remove_titles_from_pages' );
function remove_titles_from_pages() {
	// Add list of page slugs in array
    if ( is_page(array('about', 'main') ) ) {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    }
}

