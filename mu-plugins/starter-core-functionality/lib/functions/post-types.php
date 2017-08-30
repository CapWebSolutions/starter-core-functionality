<?php
/**
 * Post Types
 *
 * This file registers any custom post types
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

add_action( 'init', 'cptui_register_my_cpts' );
function cptui_register_my_cpts() {

	$labels = array(
		"name"                  => __( 'Pieces', '' ),
		"singular_name"         => __( 'Piece', '' ),
		"menu_name"             => __( 'Pieces', '' ),
		"description"           => "Poet's written works",
		"name_admin_bar"        => _x( 'Piece', '' ),
		"all_items"             => __( 'All Pieces', '' ),
		"add_new"               => __( 'Add New Piece', '' ),
		"add_new_item"          => __( 'Add New Piece', '' ),
		"edit_item"             => __( 'Edit Piece', '' ),
		"new_item"              => __( 'Add New Piece', '' ),
		"view_item"             => __( 'View Piece', '' ),
		"search_items"          => __( 'Seach Pieces', '' ),
		"not_found"             => __( 'No Pieces Found', '' ),
		"not_found_in_trash"    => __( 'No Pieces Found In trash', '' ),
		"parent_item_colon"     => __( 'Piece Parent Item', '' ),
		"featured_image"        => __( 'Piece Featured Image', '' ),
		"set_featured_image"    => __( 'Set Piece Featured Image', '' ),
		"remove_featured_image" => __( 'Remove Piece Featured Image', '' ),
		"use_featured_image"    => __( 'Use Piece Featured Image', '' ),
		"archives"              => __( 'Piece Archives', '' ),
		"insert_into_item"      => __( 'Insert into Piece', '' ),
		"uploaded_to_this_item" => __( 'Uploaded to Piece', '' ),
		"filter_items_list"     => __( 'Filter Piece List', '' ),
		"items_list_navigation" => __( 'Pieces Navigation', '' ),
		"items_list"            => __( 'Pieces List', '' ),
		"parent_item_colon"     => __( 'Piece Parent Item:', '' ),
		);

	$supports = array(
		'title',
		'editor',
		'excerpt',
		'thumbnail',
		'author',
		'custom-fields',
		'revisions',
	);

	$args = array(
		"labels"              => $labels,
		"supports"            => $supports,
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => false,
		"rest_base"           => "",
		"has_archive"         => true,
		"show_in_menu"        => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,
		"rewrite"             => array( "slug" => "piece", ),
		"query_var"           => true,
		'menu_icon'           => 'dashicons-welcome-write-blog',

	);
	register_post_type( 'piece', $args );

	// End of cptui_register_my_cpts()
}
