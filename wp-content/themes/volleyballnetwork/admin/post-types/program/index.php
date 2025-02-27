<?php
/*
	Add additional meta fields to the standard WP Page post type
*/
if ( ! defined( 'WPINC' ) ) { die; }


/*
	POST TYPES
*/
function volleyball_program_post_type_int() {
	
	$program_labels = array(
		'name'                  => _x( 'Programs', 'Post type general name', 'zielkeDesign' ),
		'singular_name'         => _x( 'program', 'Post type singular name', 'zielkeDesign' ),
		'menu_name'             => _x( 'Programs', 'Admin Menu text', 'zielkeDesign' ),
		'name_admin_bar'        => _x( 'Program', 'Add New on Toolbar', 'zielkeDesign' ),
		'add_new'               => __( 'Add New', 'zielkeDesign' ),
		'add_new_item'          => __( 'Add New Program', 'zielkeDesign' ),
		'new_item'              => __( 'New Program', 'zielkeDesign' ),
		'edit_item'             => __( 'Edit Program', 'zielkeDesign' ),
		'view_item'             => __( 'View Program', 'zielkeDesign' ),
		'all_items'             => __( 'All Programs', 'zielkeDesign' ),
		'search_items'          => __( 'Search Programs', 'zielkeDesign' ),
		'parent_item_colon'     => __( 'Parent Programs:', 'zielkeDesign' ),
		'not_found'             => __( 'No Programs found.', 'zielkeDesign' ),
		'not_found_in_trash'    => __( 'No Programs found in Trash.', 'zielkeDesign' ),
		'featured_image'        => _x( 'Program Thumbnail', 'zielkeDesign' ),
		'set_featured_image'    => _x( 'Set thumbnail', 'zielkeDesign' ),
		'remove_featured_image' => _x( 'Remove thumbnail', 'zielkeDesign' ),
		'use_featured_image'    => _x( 'Use as thumbnail', 'zielkeDesign' ),
		'archives'              => _x( 'Programs archives', 'zielkeDesign' ),
		'insert_into_item'      => _x( 'Insert into Program', 'zielkeDesign' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Program', 'zielkeDesign' ),
		'filter_items_list'     => _x( 'Filter Programs list', 'zielkeDesign' ),
		'items_list_navigation' => _x( 'Programs list navigation', 'zielkeDesign' ),
		'items_list'            => _x( 'Programs list', 'zielkeDesign' ),
	);
	$program_args = array(
		'labels'             => $program_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'page',
		'has_archive'        => false,
		'hierarchical'       => true,
		'menu_position'      => 10,
		'menu_icon'          => 'dashicons-clipboard',
		'supports'           => array( 'title', 'editor', 'page-attributes' ),
        'show_in_rest'       => true,
	);
	register_post_type( 'program', $program_args );
	
}
add_action( 'init', 'volleyball_program_post_type_int' );


/*
  RENDER META BOX ON EDIT PAGE
*/
function volleyball_program_add_meta_boxes() {
	add_meta_box(
		'league',
		'Program Info',
		'program_league',
		'program',
		'normal'
	);
	add_meta_box(
		'schedule',
		'Program Schedule',
		'program_schedule',
		'program',
		'normal'
	);
}
add_action( 'add_meta_boxes', 'volleyball_program_add_meta_boxes' );

function program_league( $post ) {
	include( 'views/program_league.php' );
}
function program_schedule( $post ) {
	include( 'views/program_schedule.php' );
}


/*
	SAVE POST META
*/
function volleyball_program_save_post_meta_data( $post_id ){
	// Check for varified nonce
	if ( ! isset( $_POST['volleyball_network_program_nonce'] ) ||
		! wp_verify_nonce( $_POST['volleyball_network_program_nonce'], 'volleyball_network_program_nonce' ) ){
		return;
	}else{
		// meta data
		if ( isset( $_REQUEST['program_league'] ) ) {
			update_post_meta( $post_id, 'program_league', sanitize_text_field(htmlentities($_REQUEST['program_league'])));
		}
		if ( isset( $_REQUEST['program_season'] ) ) {
			update_post_meta( $post_id, 'program_season', sanitize_text_field(htmlentities($_REQUEST['program_season'])));
		}
		if ( isset( $_REQUEST['program_venue'] ) ) {
			update_post_meta( $post_id, 'program_venue', sanitize_text_field(htmlentities($_REQUEST['program_venue'])));
		}
		if ( isset( $_REQUEST['program_registration'] ) ) {
			update_post_meta( $post_id, 'program_registration', sanitize_text_field(htmlentities($_REQUEST['program_registration'])));
		}
		if ( isset( $_REQUEST['program_price'] ) ) {
			update_post_meta( $post_id, 'program_price', sanitize_text_field(htmlentities($_REQUEST['program_price'])));
		}
		if ( isset( $_REQUEST['program_start_date'] ) ) {
			update_post_meta( $post_id, 'program_start_date', sanitize_text_field(htmlentities($_REQUEST['program_start_date'])));
		}
		if ( isset( $_REQUEST['program_end_date'] ) ) {
			update_post_meta( $post_id, 'program_end_date', sanitize_text_field(htmlentities($_REQUEST['program_end_date'])));
		}
		// cant use sanitize_text_field for array
		update_post_meta( $post_id, 'program_days', $_REQUEST['program_days']);
		if ( isset( $_REQUEST['program_time'] ) ) {
			update_post_meta( $post_id, 'program_time', sanitize_text_field(htmlentities($_REQUEST['program_time'])));
		}
	}
}
add_action( 'save_post', 'volleyball_program_save_post_meta_data' );


/*
	CUSTOM COLUMNS
*/
function volleyball_program_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Program' ),
		'venue' => __( 'Venue' ),
		'league' => __( 'League' ),
		'age' => __( 'Age' ),
		'gender' => __( 'Gender' ),
	);
	return $columns;
}
add_filter( 'manage_program_posts_columns', 'volleyball_program_columns' );
// Add the data to the custom columns
function volleyball_program_custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'age' :
			$terms = get_the_terms( $post_id, 'age' );
			if ( !empty( $terms ) ) {
				$out = array();
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'program', 'age' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'age', 'display' ) )
					);
				}
				echo join( ', ', $out );
			} else {
				_e( 'No Age' );
			}
			break;
		case 'gender' :
			$terms = get_the_terms( $post_id, 'gender' );
			if ( !empty( $terms ) ) {
				$out = array();
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'program', 'gender' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'gender', 'display' ) )
					);
				}
				echo join( ', ', $out );
			} else {
				_e( 'CO-ED' );
			}
			break;
		case 'venue' :
			$venue_id = get_post_meta( $post_id, 'program_venue', true );
			$venue_city_taxonomies = get_the_terms($venue_id, 'city');
			echo esc_html($venue_city_taxonomies[0]->name).':<br>'.esc_html(get_the_title($venue_id));
			break;
		case 'league' :
			$league = get_post_meta( $post_id, 'program_league', true );
            $season_id = get_post_meta( $post_id, 'program_season', true );
            $league_seasons = get_post_meta( $league, 'seasons', true );

            $season_title = '';
            if ( !empty($league_seasons) && is_array($league_seasons) ) {
                foreach ( $league_seasons as $season ) {
                    if ( $season['id'] == $season_id ) {
                        $season_title = $season['season'] . ' - ' . $season['year'];
                        break;
                    }
                }
            }

            echo esc_html(get_the_title($league)) . ':<br>' . esc_html($season_title);
			break;
	}
}
add_action( 'manage_program_posts_custom_column' , 'volleyball_program_custom_columns', 10, 2 );
// Make the custom columns sortable
function volleyball_program_sortable_columns( $columns ) {
    $columns['age'] = 'age';
    $columns['gender'] = 'gender';
    $columns['venue'] = 'venue';
    $columns['league'] = 'league';
    return $columns;
}
add_filter( 'manage_edit-program_sortable_columns', 'volleyball_program_sortable_columns' );

// Handle the sorting logic
function volleyball_program_orderby( $query ) {
    if ( ! is_admin() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'age' == $orderby ) {
        $query->set( 'meta_key', 'program_age' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'gender' == $orderby ) {
        $query->set( 'meta_key', 'program_gender' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'venue' == $orderby ) {
        $query->set( 'meta_key', 'program_venue' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'league' == $orderby ) {
        $query->set( 'meta_key', 'program_league' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'volleyball_program_orderby' );


/*
	REST API
*/
function add_custom_fields_to_program_api( $response, $post, $request ) {
    if ( $post->post_type === 'program' ) {

		$program_league = get_post_meta( $post->ID, 'program_league', true ); // Assuming you're using ACF.  Adapt if using other methods.
		$program_season = get_post_meta( $post->ID, 'program_season', true );

		$program_start_date	= get_post_meta( $post->ID, 'program_start_date', true );
		$program_end_date	= get_post_meta( $post->ID, 'program_end_date', true );
		$program_days		= get_post_meta( $post->ID, 'program_days', true );
		$program_time		= get_post_meta( $post->ID, 'program_time', true );

		$program_venue = get_post_meta( $post->ID, 'program_venue', true );

        // Add each custom field to the response data.
        if(isset($program_league)){
            $response->data['program_league'] = $program_league;
        }
        if(isset($program_season)){
            $response->data['program_season'] = $program_season;
        }
        if(isset($program_start_date)){
            $response->data['program_start_date'] = $program_start_date;
        }
        if(isset($program_end_date)){
            $response->data['program_end_date'] = $program_end_date;
        }
        if(isset($program_days)){
            $response->data['program_days'] = $program_days;
        }
        if(isset($program_time)){
            $response->data['program_time'] = $program_time;
        }
        if(isset($program_venue)){
            $response->data['program_venue'] = $program_venue;
        }
    }
    return $response;
}
add_filter( 'rest_prepare_program', 'add_custom_fields_to_program_api', 10, 3 );