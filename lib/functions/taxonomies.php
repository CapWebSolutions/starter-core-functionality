<?php
/**
 * Taxonomies
 *
 * This file registers any custom taxonomies
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/capwebsolutions/starter-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @copyright    Copyright (c) 2017, Matt Ryan
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

 namespace capweb;

/**
 * Create Genre Taxonomy
 *
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

add_action( 'init', __NAMESPACE__ . '\_register_my_taxes' );
function _register_my_taxes() {
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

// End capweb_register_my_taxes()
}