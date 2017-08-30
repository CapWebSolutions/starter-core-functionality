<?php
/**
 * Taxonomies
 *
 * This file registers any custom taxonomies
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


/**
 * Create Genre Taxonomy
 *
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

add_action( 'init', 'cptui_register_my_taxes' );
function cptui_register_my_taxes() {
	$labels = array(
		"name" => __( 'Genres', '' ),
		"singular_name" => __( 'Genre', '' ),
		);

	$args = array(
		"label" => __( 'Genre', '' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => false,
		"label" => "Genre",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'genre', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "genre", array( "genre" ), $args );

	$labels = array(
		"name" => __( 'Genre Tags', '' ),
		"singular_name" => __( 'Genre Tag', '' ),
		);

	$args = array(
		"label" => __( 'Genre Tags', '' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => false,
		"label" => "Genre Tags",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'genre_tag', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "genre_tag", array( "genre" ), $args );

// End cptui_register_my_taxes()
}