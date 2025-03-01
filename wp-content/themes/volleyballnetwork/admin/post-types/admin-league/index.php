<?php
if ( ! defined( 'WPINC' ) ) { die; }

/*
	POST TYPES
*/
function volleyball_league_post_type_int() {
	
	$league_labels = array(
		'name'                  => _x( 'Admin Leagues', 'Post type general name', 'zielkeDesign' ),
		'singular_name'         => _x( 'Admin League', 'Post type singular name', 'zielkeDesign' ),
		'menu_name'             => _x( 'Admin Leagues', 'Admin Menu text', 'zielkeDesign' ),
		'name_admin_bar'        => _x( 'Admin League', 'Add New on Toolbar', 'zielkeDesign' ),
		'add_new'               => __( 'Add New', 'zielkeDesign' ),
		'add_new_item'          => __( 'Add New Admin League', 'zielkeDesign' ),
		'new_item'              => __( 'New Admin League', 'zielkeDesign' ),
		'edit_item'             => __( 'Edit Admin League', 'zielkeDesign' ),
		'view_item'             => __( 'View Admin League', 'zielkeDesign' ),
		'all_items'             => __( 'All Admin Leagues', 'zielkeDesign' ),
		'search_items'          => __( 'Search Admin Leagues', 'zielkeDesign' ),
		'parent_item_colon'     => __( 'Parent Admin Leagues:', 'zielkeDesign' ),
		'not_found'             => __( 'No Admin Leagues found.', 'zielkeDesign' ),
		'not_found_in_trash'    => __( 'No Admin Leagues found in Trash.', 'zielkeDesign' ),
		'featured_image'        => _x( 'Admin League Thumbnail', 'zielkeDesign' ),
		'set_featured_image'    => _x( 'Set thumbnail', 'zielkeDesign' ),
		'remove_featured_image' => _x( 'Remove thumbnail', 'zielkeDesign' ),
		'use_featured_image'    => _x( 'Use as thumbnail', 'zielkeDesign' ),
		'archives'              => _x( 'Admin Leagues archives', 'zielkeDesign' ),
		'insert_into_item'      => _x( 'Insert into Admin League', 'zielkeDesign' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Admin League', 'zielkeDesign' ),
		'filter_items_list'     => _x( 'Filter Admin Leagues list', 'zielkeDesign' ),
		'items_list_navigation' => _x( 'Admin Leagues list navigation', 'zielkeDesign' ),
		'items_list'            => _x( 'Admin Leagues list', 'zielkeDesign' ),
	);
	$league_args = array(
		'labels'             => $league_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'page',/*
        'capabilities'        => array(
            'create_posts' => 'do_not_allow', // Prevents non-admins from creating
            'edit_posts'   => 'edit_others_posts', // Only admins can edit others' posts.
            'edit_post'    => 'edit_others_posts',
            'delete_posts' => 'delete_others_posts', // Only admins can delete others' posts.
            'delete_post'  => 'delete_others_posts',
            'publish_posts' => 'publish_posts', //Only admins can publish
            'read_post'    => 'read',
            'read_private_posts' => 'read_private_posts'
        ),*/
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => 10,
		'menu_icon'          => 'dashicons-networking',
		'supports'           => array( 'title', 'editor', 'page-attributes' ),
        'show_in_rest'       => true,
	);
	register_post_type( 'league', $league_args );
	
}
add_action( 'init', 'volleyball_league_post_type_int' );

/*
	HIDE MENU ITEM FOR NON-ADMINS
*/
function hide_league_menu() {
    if (!current_user_can('administrator')) {
        remove_menu_page('edit.php?post_type=league');
    }
}
add_action('admin_menu', 'hide_league_menu');