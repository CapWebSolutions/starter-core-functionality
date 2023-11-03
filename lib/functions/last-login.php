<?php
/**
 * Last Login
 *
 * This feature records the time a user logs in and saves it in the ‘last_login’ user meta. 
 * It also adds a sortable ‘Last Login’ admin column to the user list in the admin dashboard. 
 * Lastly it allows you to display the user’s last login via a [lastlogin] shortcode. 
 * The shortcode also lets you show a specific user’s last login by using the user_id variable 
 * [lastlogin user_id=2].This file contains any general functions
 *
 * @package      Core_Functionality
 * @since        2.0.0
 * @link         https://github.com/MattRyanCo/sjec-core-functionality
 * @author       Matt Ryan
 */
namespace capweb;


//Record user's last login to custom meta
add_action( 'wp_login', 'capweb_capture_login_time', 10, 2 );

function capweb_capture_login_time( $user_login, $user ) {
  update_user_meta( $user->ID, 'last_login', time() );
}

//Register new custom column with last login time
add_filter( 'manage_users_columns', 'capweb_user_last_login_column' );
add_filter( 'manage_users_custom_column', 'capweb_last_login_column', 10, 3 );

function capweb_user_last_login_column( $columns ) {
	$columns['last_login'] = 'Last Login';
	return $columns;
}

function capweb_last_login_column( $output, $column_id, $user_id ){
	if( $column_id == 'last_login' ) {
    $last_login = get_user_meta( $user_id, 'last_login', true );
    $date_format = 'M j, Y';
    $hover_date_format = 'F j, Y, g:i a';
    
		$output = $last_login ? '<div title="Last login: '.date( $hover_date_format, $last_login ).'">'.human_time_diff( $last_login ).'</div>' : 'No record';
	}
  
	return $output;
}

//Allow the last login columns to be sortable
add_filter( 'manage_users_sortable_columns', 'capweb_sortable_last_login_column' );
add_action( 'pre_get_users', 'capweb_sort_last_login_column' );

function capweb_sortable_last_login_column( $columns ) {
	return wp_parse_args( array(
	 	'last_login' => 'last_login'
	), $columns );
 
}

function capweb_sort_last_login_column( $query ) {
	if( !is_admin() ) {
		return $query;
	}
 
    /**
     * Check whether the get_current_screen function exists
     * because it is loaded only after 'admin_init' hook.
     */
    if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
	
		if( isset( $screen->base ) && $screen->base !== 'users' ) {
			return $query;
		}
	
		if( isset( $_GET[ 'orderby' ] ) && $_GET[ 'orderby' ] == 'last_login' ) {
	
			$query->query_vars['meta_key'] = 'last_login';
			$query->query_vars['orderby'] = 'meta_value';
	
		}
	}
 
  return $query;
}

//Add [lastlogin] shortcode
function capweb_lastlogin_shortcode( $atts ) {
  $atts = shortcode_atts(
  array(
      'user_id' => false,
  ), $atts, 'lastlogin' );

  $last_login = get_the_author_meta('last_login', $atts['user_id']);
  if( empty($last_login) ){ return false; };
  $the_login_date = human_time_diff($last_login);
  return $the_login_date; 
}

add_shortcode( 'lastlogin', 'capweb_lastlogin_shortcode' );