<?php
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Register the post type
 */
function volleyball_local_league_post_type_int() {
	
	$local_league_labels = array(
		'name'                  => _x( 'Leagues', 'Post type general name', 'zielkeDesign' ),
		'singular_name'         => _x( 'League', 'Post type singular name', 'zielkeDesign' ),
		'menu_name'             => _x( 'Local Leagues', 'Menu text', 'zielkeDesign' ),
		'name_admin_bar'        => _x( 'League', 'Add New on Toolbar', 'zielkeDesign' ),
		'add_new'               => __( 'Add New', 'zielkeDesign' ),
		'add_new_item'          => __( 'Add New League', 'zielkeDesign' ),
		'new_item'              => __( 'New League', 'zielkeDesign' ),
		'edit_item'             => __( 'Edit League', 'zielkeDesign' ),
		'view_item'             => __( 'View League', 'zielkeDesign' ),
		'all_items'             => __( 'All Leagues', 'zielkeDesign' ),
		'search_items'          => __( 'Search Leagues', 'zielkeDesign' ),
		'parent_item_colon'     => __( 'Parent Leagues:', 'zielkeDesign' ),
		'not_found'             => __( 'No Leagues found.', 'zielkeDesign' ),
		'not_found_in_trash'    => __( 'No Leagues found in Trash.', 'zielkeDesign' ),
		'featured_image'        => _x( 'League Thumbnail', 'zielkeDesign' ),
		'set_featured_image'    => _x( 'Set thumbnail', 'zielkeDesign' ),
		'remove_featured_image' => _x( 'Remove thumbnail', 'zielkeDesign' ),
		'use_featured_image'    => _x( 'Use as thumbnail', 'zielkeDesign' ),
		'archives'              => _x( 'Leagues archives', 'zielkeDesign' ),
		'insert_into_item'      => _x( 'Insert into League', 'zielkeDesign' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this League', 'zielkeDesign' ),
		'filter_items_list'     => _x( 'Filter Leagues list', 'zielkeDesign' ),
		'items_list_navigation' => _x( 'Leagues list navigation', 'zielkeDesign' ),
		'items_list'            => _x( 'Leagues list', 'zielkeDesign' ),
	);
	$local_league_args = array(
		'labels'             => $local_league_labels,
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
		'supports'           => array( 'title', 'editor', 'page-attributes' ),
        'show_in_rest'       => true,
	);
	register_post_type( 'local-league', $local_league_args );
	
}
add_action( 'init', 'volleyball_local_league_post_type_int' );


/**
 * Add meta boxes
 */
function volleyball_local_league_meta_boxes() {
	add_meta_box( 
		'local-league-league', 
		'Select League', 
		'local_league_league', 
		'local-league', 
		'normal',
	);
	add_meta_box( 
		'local-league-seasons', 
		'Seasons', 
		'local_league_seasons', 
		'local-league', 
		'normal',
	);
}
add_action( 'add_meta_boxes', 'volleyball_local_league_meta_boxes' );


/**
 * Display the meta box
 */
function local_league_league( $post ) {
	include( 'views/local_league_league.php' );
}
function local_league_seasons( $post ) {
	include( 'views/local_league_seasons.php' );
}


/**
 * Save the meta box
 */
function save_local_league_seasons( $post_id ) {
	// Check for varified nonce
	if ( ! isset( $_POST['volleyball_network_local_league_nonce'] ) ||
		! wp_verify_nonce( $_POST['volleyball_network_local_league_nonce'], 'volleyball_network_local_league_nonce' ) ){
		return;
	}else{
		// meta data
		if ( isset( $_REQUEST['local_league_league'] ) ) {
			update_post_meta( $post_id, 'local_league_league', sanitize_text_field(htmlentities($_REQUEST['local_league_league'])));
		}
		if ( isset( $_REQUEST['season_winter_registration'] ) ) {
			update_post_meta( $post_id, 'season_winter_registration', sanitize_text_field(htmlentities($_REQUEST['season_winter_registration'])));
		}
		if ( isset( $_REQUEST['season_winter_price'] ) ) {
			update_post_meta( $post_id, 'season_winter_price', sanitize_text_field(htmlentities($_REQUEST['season_winter_price'])));
		}
		if ( isset( $_REQUEST['season_spring_registration'] ) ) {
			update_post_meta( $post_id, 'season_spring_registration', sanitize_text_field(htmlentities($_REQUEST['season_spring_registration'])));
		}
		if ( isset( $_REQUEST['season_spring_price'] ) ) {
			update_post_meta( $post_id, 'season_spring_price', sanitize_text_field(htmlentities($_REQUEST['season_spring_price'])));
		}
		if ( isset( $_REQUEST['season_summer_registration'] ) ) {
			update_post_meta( $post_id, 'season_summer_registration', sanitize_text_field(htmlentities($_REQUEST['season_summer_registration'])));
		}
		if ( isset( $_REQUEST['season_summer_price'] ) ) {
			update_post_meta( $post_id, 'season_summer_price', sanitize_text_field(htmlentities($_REQUEST['season_summer_price'])));
		}
		if ( isset( $_REQUEST['season_fall_registration'] ) ) {
			update_post_meta( $post_id, 'season_fall_registration', sanitize_text_field(htmlentities($_REQUEST['season_fall_registration'])));
		}
		if ( isset( $_REQUEST['season_fall_price'] ) ) {
			update_post_meta( $post_id, 'season_fall_price', sanitize_text_field(htmlentities($_REQUEST['season_fall_price'])));
		}
	}
	
    // Avoid recursion, autosave, and revisions
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Get the first term from the 'city' taxonomy
    $terms = wp_get_post_terms( $post_id, 'city' );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        $city_slug = sanitize_title( $terms[0]->name );

        // Schedule an update to avoid recursion
        wp_schedule_single_event( time() + 1, 'update_post_slug_event', array( $post_id, $city_slug ) );
    }
}
add_action( 'save_post', 'save_local_league_seasons' );

// Hook for scheduled event
function update_post_slug( $post_id, $city_slug ) {
    $post = array(
        'ID'        => $post_id,
        'post_name' => sanitize_title( get_post_field( 'post_title', $post_id ) . '-' . $city_slug ),
    );

    wp_update_post( $post );
}
add_action( 'update_post_slug_event', 'update_post_slug', 10, 2 );


/**
 * Custom columns
 */
function volleyball_local_league_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Local League' ),
		'league' => __( 'League' ),
		'city' => __( 'City' ),
		'state' => __( 'State/Provice' ),
		'country' => __( 'Country' ),
	);
	return $columns;
}
add_filter( 'manage_local-league_posts_columns', 'volleyball_local_league_columns' );

/**
 * Custom columns content
 */
function volleyball_local_league_columns_content( $column, $post_id ) {
	switch ( $column ) {
		case 'league':
			$local_league_league = get_post_meta( $post_id, 'local_league_league', true );
			echo $local_league_league ? get_the_title( $local_league_league ) : '';
			break;
		case 'country':
			// get terms
			$terms = get_the_terms( $post_id, 'country' );
			// if terms
			if ( !empty( $terms ) ) {
				// output
				$out = array();
				// loop
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'local-league', 'country' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'country', 'display' ) )
					);
				}
				echo join( ', ', $out );
			} else {
				_e( 'No Country' );
			}
			break;
		case 'state':
			// get terms
			$terms = get_the_terms( $post_id, 'state' );
			// if terms
			if ( !empty( $terms ) ) {
				// output
				$out = array();
				// loop
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'local-league', 'state' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'state', 'display' ) )
					);
				}
				echo join( ', ', $out );
			} else {
				_e( 'No State' );
			}
			break;
		case 'city':
			// get terms
			$terms = get_the_terms( $post_id, 'city' );
			// if terms
			if ( !empty( $terms ) ) {
				// output
				$out = array();
				// loop
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'local-league', 'city' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'city', 'display' ) )
					);
				}
				echo join( ', ', $out );
			} else {
				_e( 'No City' );
			}
			break;

	}
}
add_action( 'manage_local-league_posts_custom_column', 'volleyball_local_league_columns_content', 10, 2 );

/**
 * Make columns sortable
 */
function volleyball_local_league_columns_sortable( $columns ) {
	$columns['league'] = 'league';
	$columns['country'] = 'country';
	$columns['state'] = 'state';
	$columns['city'] = 'city';
	return $columns;
}
add_filter( 'manage_edit-local-league_sortable_columns', 'volleyball_local_league_columns_sortable' );


/**
 * Rest API
 */
function volleyball_local_league_rest_api( $data ) {
	// get post
	$post = get_post( $data['id'] );
	// if post
	if ( $post->post_type === 'local-league' ) {
		// meta data
		$local_league_league = get_post_meta( $post->ID, 'local_league_league', true );
		$season_winter_registration = get_post_meta( $post->ID, 'season_winter_registration', true );
		$season_winter_price = get_post_meta( $post->ID, 'season_winter_price', true );
		$season_spring_registration = get_post_meta( $post->ID, 'season_spring_registration', true );
		$season_spring_price = get_post_meta( $post->ID, 'season_spring_price', true );
		$season_summer_registration = get_post_meta( $post->ID, 'season_summer_registration', true );
		$season_summer_price = get_post_meta( $post->ID, 'season_summer_price', true );
		$season_fall_registration = get_post_meta( $post->ID, 'season_fall_registration', true );
		$season_fall_price = get_post_meta( $post->ID, 'season_fall_price', true );
		// add to response
		if(isset($local_league_league)){
			$response->data['local_league_league'] = $local_league_league;
		}
		if(isset($season_winter_registration)){
			$response->data['season_winter_registration'] = $season_winter_registration;
		}
		if(isset($season_winter_price)){
			$response->data['season_winter_price'] = $season_winter_price;
		}
		if(isset($season_spring_registration)){
			$response->data['season_spring_registration'] = $season_spring_registration;
		}
		if(isset($season_spring_price)){
			$response->data['season_spring_price'] = $season_spring_price;
		}
		if(isset($season_summer_registration)){
			$response->data['season_summer_registration'] = $season_summer_registration;
		}
		if(isset($season_summer_price)){
			$response->data['season_summer_price'] = $season_summer_price;
		}
		if(isset($season_fall_registration)){
			$response->data['season_fall_registration'] = $season_fall_registration;
		}
		if(isset($season_fall_price)){
			$response->data['season_fall_price'] = $season_fall_price;
		}
	}
	return $response;
}
add_filter( 'rest_prepare_local-league', 'volleyball_local_league_rest_api' );
?>