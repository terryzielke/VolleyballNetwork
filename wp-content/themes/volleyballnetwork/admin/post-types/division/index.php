<?php
if ( ! defined( 'WPINC' ) ) { die; }

/*
	POST TYPES
*/
function volleyball_division_post_type_int() {
	
	$division_labels = array(
		'name'                  => _x( 'Divisions', 'Post type general name', 'zielkeDesign' ),
		'singular_name'         => _x( 'division', 'Post type singular name', 'zielkeDesign' ),
		'menu_name'             => _x( 'Divisions', 'Admin Menu text', 'zielkeDesign' ),
		'name_admin_bar'        => _x( 'Division', 'Add New on Toolbar', 'zielkeDesign' ),
		'add_new'               => __( 'Add New', 'zielkeDesign' ),
		'add_new_item'          => __( 'Add New Division', 'zielkeDesign' ),
		'new_item'              => __( 'New Division', 'zielkeDesign' ),
		'edit_item'             => __( 'Edit Division', 'zielkeDesign' ),
		'view_item'             => __( 'View Division', 'zielkeDesign' ),
		'all_items'             => __( 'All Divisions', 'zielkeDesign' ),
		'search_items'          => __( 'Search Divisions', 'zielkeDesign' ),
		'parent_item_colon'     => __( 'Parent Divisions:', 'zielkeDesign' ),
		'not_found'             => __( 'No Divisions found.', 'zielkeDesign' ),
		'not_found_in_trash'    => __( 'No Divisions found in Trash.', 'zielkeDesign' ),
		'featured_image'        => _x( 'Division Thumbnail', 'zielkeDesign' ),
		'set_featured_image'    => _x( 'Set thumbnail', 'zielkeDesign' ),
		'remove_featured_image' => _x( 'Remove thumbnail', 'zielkeDesign' ),
		'use_featured_image'    => _x( 'Use as thumbnail', 'zielkeDesign' ),
		'archives'              => _x( 'Divisions archives', 'zielkeDesign' ),
		'insert_into_item'      => _x( 'Insert into Division', 'zielkeDesign' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Division', 'zielkeDesign' ),
		'filter_items_list'     => _x( 'Filter Divisions list', 'zielkeDesign' ),
		'items_list_navigation' => _x( 'Divisions list navigation', 'zielkeDesign' ),
		'items_list'            => _x( 'Divisions list', 'zielkeDesign' ),
	);
	$division_args = array(
		'labels'             => $division_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'page',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => 10,
		'menu_icon'          => 'dashicons-admin-site-alt3',
		'supports'           => array( 'title', 'editor', 'page-attributes' ),
        'show_in_rest'       => true,
	);
	register_post_type( 'division', $division_args );
	
}
add_action( 'init', 'volleyball_division_post_type_int' );

/*
	HIDE MENU ITEM FOR NON-ADMINS
*/
function hide_division_menu() {
    if (!current_user_can('administrator')) {
        remove_menu_page('edit.php?post_type=division');
    }
}
add_action('admin_menu', 'hide_division_menu');