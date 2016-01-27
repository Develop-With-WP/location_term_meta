<?php
/*
Plugin Name: Term Meta Series
Plugin URI: http://www.github.com/mrbobbybryant/location_term_meta
Description: Used to demonstrate how to work with the term meta feature introduced in WordPress 4.4.
Author: Bobby Bryant
Version: 1.0.0
Author URI: twitter.com/mrbobbybryant
*/

namespace Location_Term_Meta;

function register_location_taxonomy() {
	$labels = array(
		'name'              => _x( 'Locations', 'text-domain' ),
		'singular_name'     => _x( 'Location', 'text-domain' ),
		'search_items'      => __( 'Search Locations', 'text-domain' ),
		'all_items'         => __( 'All Locations', 'text-domain' ),
		'parent_item'       => __( 'Parent Location', 'text-domain' ),
		'parent_item_colon' => __( 'Parent Location:', 'text-domain' ),
		'edit_item'         => __( 'Edit Location', 'text-domain' ),
		'update_item'       => __( 'Update Location', 'text-domain' ),
		'add_new_item'      => __( 'Add New Location', 'text-domain' ),
		'new_item_name'     => __( 'New Location Name', 'text-domain' ),
		'menu_name'         => __( 'Location', 'text-domain' ),
	);

	register_taxonomy( 'location', 'post', array( 'labels' => $labels ) );
}
add_action( 'init', __NAMESPACE__ . '\register_location_taxonomy' );