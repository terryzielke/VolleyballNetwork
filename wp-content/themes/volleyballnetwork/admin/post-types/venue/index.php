<?php
/*
	Add additional meta fields to the standard WP Page post type
*/
if ( ! defined( 'WPINC' ) ) { die; }


/*
	POST TYPES
*/
function volleyball_venue_post_type_int() {
	
	$venue_labels = array(
		'name'                  => _x( 'Venues', 'Post type general name', 'zielkeDesign' ),
		'singular_name'         => _x( 'Venue', 'Post type singular name', 'zielkeDesign' ),
		'menu_name'             => _x( 'Venues', 'Admin Menu text', 'zielkeDesign' ),
		'name_admin_bar'        => _x( 'Venue', 'Add New on Toolbar', 'zielkeDesign' ),
		'add_new'               => __( 'Add New', 'zielkeDesign' ),
		'add_new_item'          => __( 'Add New Venue', 'zielkeDesign' ),
		'new_item'              => __( 'New Venue', 'zielkeDesign' ),
		'edit_item'             => __( 'Edit Venue', 'zielkeDesign' ),
		'view_item'             => __( 'View Venue', 'zielkeDesign' ),
		'all_items'             => __( 'All Venues', 'zielkeDesign' ),
		'search_items'          => __( 'Search Venues', 'zielkeDesign' ),
		'parent_item_colon'     => __( 'Parent Venues:', 'zielkeDesign' ),
		'not_found'             => __( 'No Venues found.', 'zielkeDesign' ),
		'not_found_in_trash'    => __( 'No Venues found in Trash.', 'zielkeDesign' ),
		'featured_image'        => _x( 'Venue Thumbnail', 'zielkeDesign' ),
		'set_featured_image'    => _x( 'Set thumbnail', 'zielkeDesign' ),
		'remove_featured_image' => _x( 'Remove thumbnail', 'zielkeDesign' ),
		'use_featured_image'    => _x( 'Use as thumbnail', 'zielkeDesign' ),
		'archives'              => _x( 'Venues archives', 'zielkeDesign' ),
		'insert_into_item'      => _x( 'Insert into Venue', 'zielkeDesign' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Venue', 'zielkeDesign' ),
		'filter_items_list'     => _x( 'Filter Venues list', 'zielkeDesign' ),
		'items_list_navigation' => _x( 'Venues list navigation', 'zielkeDesign' ),
		'items_list'            => _x( 'Venues list', 'zielkeDesign' ),
	);
	$venue_args = array(
		'labels'             => $venue_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'page',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => 10,
		'menu_icon'          => 'dashicons-location',
		'supports'           => array( 'title', 'editor', 'page-attributes' ),
        'show_in_rest'       => true,
	);
	register_post_type( 'venue', $venue_args );
	
}
add_action( 'init', 'volleyball_venue_post_type_int' );


/*
  RENDER META BOX ON EDIT PAGE
*/
function volleyball_venue_add_meta_boxes() {
	add_meta_box(
		'address',
		'Address',
		'venue_address',
		'Venue',
		'side'
	);
}
add_action( 'add_meta_boxes', 'volleyball_venue_add_meta_boxes' );

// Content to display inside meta box
function venue_address( $post ) {
	include( 'views/venue_address.php' );
}


/*
	SAVE POST META
*/
function volleyball_venue_save_post_meta_data( $post_id ){
	// Check for varified nonce
	if ( ! isset( $_POST['volleyball_network_venue_nonce'] ) ||
		! wp_verify_nonce( $_POST['volleyball_network_venue_nonce'], 'volleyball_network_venue_nonce' ) ){
		return;
	}else{
		// can't use isset or sanitize on checkboxs
		update_post_meta( $post_id, 'venue_coming_soon', $_REQUEST['venue_coming_soon']);
		// address
		if ( isset( $_REQUEST['venue_address'] ) ) {
			update_post_meta( $post_id, 'venue_address', sanitize_text_field(htmlentities($_REQUEST['venue_address'])));
		}
		if ( isset( $_REQUEST['venue_postal_code'] ) ) {
			update_post_meta( $post_id, 'venue_postal_code', sanitize_text_field(htmlentities($_REQUEST['venue_postal_code'])));
		}
	}
}
add_action( 'save_post', 'volleyball_venue_save_post_meta_data' );

/*
	ADD CUSTOM COLUMNS
*/
function volleyball_venue_columns( $columns ) {
	$columns = array(
		'cb' => $columns['cb'],
		'title' => __( 'Venue' ),
		'city' => __( 'City' ),
		'state' => __( 'State' ),
		'date' => $columns['date'],
	);
	return $columns;
}
add_filter( 'manage_venue_posts_columns', 'volleyball_venue_columns' );

function volleyball_venue_custom_column( $column, $post_id ) {
	switch ( $column ) {
		case 'city':
            $terms = get_the_terms( $post_id, 'city' );
            if ( !empty( $terms ) ) {
                $out = array();
                foreach ( $terms as $term ) {
                    $out[] = sprintf( '<a href="%s">%s</a>',
                        esc_url( add_query_arg( array( 'post_type' => 'venue', 'city' => $term->slug ), 'edit.php' ) ),
                        esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'city', 'display' ) )
                    );
                }
                echo join( ', ', $out );
            } else {
                _e( 'No City' );
            }
			break;
		case 'state':
            $terms = get_the_terms( $post_id, 'state' );
            if ( !empty( $terms ) ) {
                $out = array();
                foreach ( $terms as $term ) {
                    $out[] = sprintf( '<a href="%s">%s</a>',
                        esc_url( add_query_arg( array( 'post_type' => 'venue', 'state' => $term->slug ), 'edit.php' ) ),
                        esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'state', 'display' ) )
                    );
                }
                echo join( ', ', $out );
            } else {
                _e( 'No State' );
            }
			break;
	}
}
add_action( 'manage_venue_posts_custom_column', 'volleyball_venue_custom_column', 10, 2 );

// sortable columns
function volleyball_venue_sortable_columns( $columns ) {
	$columns['city'] = 'city';
	$columns['state'] = 'state';
	return $columns;
}
add_filter( 'manage_edit-venue_sortable_columns', 'volleyball_venue_sortable_columns' );



/*
	REST API
*/
function add_custom_fields_to_venue_api( $response, $post, $request ) {
    if ( $post->post_type === 'venue' ) {

		$venue_address  	= get_post_meta( $post->ID, 'venue_address', true );
		$venue_postal_code	= get_post_meta( $post->ID, 'venue_postal_code', true );

        // Add each custom field to the response data.
        if(isset($venue_address)){
            $response->data['venue_address'] = $venue_address;
        }
        if(isset($venue_postal_code)){
            $response->data['venue_postal_code'] = $venue_postal_code;
        }
    }
    return $response;
}
add_filter( 'rest_prepare_venue', 'add_custom_fields_to_venue_api', 10, 3 );