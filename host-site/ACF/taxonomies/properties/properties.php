<?php
add_action( 'init', function() {
	register_taxonomy( 'property', array(
	0 => 'grant',
), array(
	'labels' => array(
		'name' => 'Properties',
		'singular_name' => 'Property',
		'menu_name' => 'Properties',
		'all_items' => 'All Properties',
		'edit_item' => 'Edit Property',
		'view_item' => 'View Property',
		'update_item' => 'Update Property',
		'add_new_item' => 'Add New Property',
		'new_item_name' => 'New Property Name',
		'search_items' => 'Search Properties',
		'not_found' => 'No properties found',
		'no_terms' => 'No properties',
		'items_list_navigation' => 'Properties list navigation',
		'items_list' => 'Properties list',
		'back_to_items' => '← Go to properties',
		'item_link' => 'Property Link',
		'item_link_description' => 'A link to a property',
	),
	'public' => true,
	'hierarchical' => true,
	'show_in_menu' => true,
	'show_in_rest' => true,
	'show_tagcloud' => false,
	'show_admin_column' => true,
) );
} );

