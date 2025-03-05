<?php
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Post Types
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
		'capability_type'    => 'page',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => 10,
		'menu_icon'          => 'dashicons-networking',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'show_in_rest'       => true,
	);
	register_post_type( 'league', $league_args );
	
}
add_action( 'init', 'volleyball_league_post_type_int' );

/**
 * Hide menu from non-admins
 */
function hide_league_menu() {
    if (!current_user_can('administrator')) {
        remove_menu_page('edit.php?post_type=league');
    }
}
add_action('admin_menu', 'hide_league_menu');


/**
 * Meta Boxes
 */
function volleyball_admin_league_add_meta_boxes() {
	add_meta_box(
		'league-details',
		'Details',
		'league_details',
		'league',
		'normal'
	);
	add_meta_box(
		'league-ages',
		'Ages',
		'league_ages',
		'league',
		'side'
	);
	add_meta_box(
		'league-header',
		'Header',
		'league_header',
		'league',
		'advanced',
		'high'
	);
}
add_action( 'add_meta_boxes', 'volleyball_admin_league_add_meta_boxes' );

function league_details( $post ) {
	include( 'views/league_details.php' );
}
function league_ages( $post ) {
	include( 'views/league_ages.php' );
}
function league_header( $post ) {
	include( 'views/league_header.php' );
}

/**
 * Move advanced meta boxes above the default editor
 */
function volleyball_admin_league_move_meta_boxes() {
	remove_meta_box( 'league-header', 'league', 'advanced' );
	add_meta_box( 'league-header', 'Header', 'league_header', 'league', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'volleyball_admin_league_move_meta_boxes', 999 );


/**
 * Save Meta Boxes
 */
function volleyball_admin_league_save_meta_boxes( $post_id ) {
    // Check if our nonce is set.
    if (!isset($_POST['volleyball_network_admin_league_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['volleyball_network_admin_league_nonce'], 'volleyball_network_admin_league_nonce')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

	if ( isset( $_REQUEST['league_primary_header'] ) ) {
		update_post_meta( $post_id, 'league_primary_header', sanitize_text_field(htmlentities($_REQUEST['league_primary_header'])));
	}
	if ( isset( $_REQUEST['league_sub_header'] ) ) {
		update_post_meta( $post_id, 'league_sub_header', sanitize_text_field(htmlentities($_REQUEST['league_sub_header'])));
	}
	if ( isset( $_REQUEST['league_ages'] ) ) {
		update_post_meta( $post_id, 'league_ages', sanitize_text_field(htmlentities($_REQUEST['league_ages'])));
	}

    // Sanitize and save the data
    if (isset($_POST['league_excerpt'])) {
		update_post_meta($post_id, 'league_excerpt', wp_kses_post($_POST['league_excerpt']));
    }

    if (isset($_POST['league_program_info'])) {
        update_post_meta($post_id, 'league_program_info', wp_kses_post($_POST['league_program_info']));
    }

    if (isset($_POST['league_format_info'])) {
        update_post_meta($post_id, 'league_format_info', wp_kses_post($_POST['league_format_info']));
    }

    if (isset($_POST['league_expectation_info'])) {
        update_post_meta($post_id, 'league_expectation_info', wp_kses_post($_POST['league_expectation_info']));
    }
}
add_action( 'save_post', 'volleyball_admin_league_save_meta_boxes' );