<?php

/**
 * Hide all program posts and local-league posts if the program_division or local_league_division meta value does not match the user's user_division meta value.
 */
function hide_programs_and_local_leagues( $query ) {
    if ( ! current_user_can('administrator') ) {
        global $pagenow;
        if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], array( 'program', 'local-league' ) ) ) {
            global $current_user;
            get_currentuserinfo();
            $user_division = get_user_meta( $current_user->ID, 'user_division', true );
            $meta_key = 'program_division';
            if ( 'local-league' === $_GET['post_type'] ) {
                $meta_key = 'local_league_division';
            }
            $query->set( 'meta_query', array(
                array(
                    'key'     => $meta_key,
                    'value'   => $user_division,
                    'compare' => '=',
                ),
            ) );
        }
    }
    return $query;
}
add_filter( 'pre_get_posts', 'hide_programs_and_local_leagues' ); 


/**
 * Hide all venue posts where the city taxonomy term does not match the terms set for the users division.
 */
function hide_venues( $query ) {
    if ( ! current_user_can('administrator') ) {
        global $pagenow;
        if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'venue' === $_GET['post_type'] ) {
            global $current_user;
            get_currentuserinfo();
            $user_division = get_user_meta( $current_user->ID, 'user_division', true );

            //get the city taxonomy terms for the post with the id $user_division
            $city_terms = get_the_terms( $user_division, 'city' );
            //get the term ids for the city terms
            $city_ids = array();
            foreach ( $city_terms as $city_term ) {
                $city_ids[] = $city_term->term_id;
            }
            $query->set( 'tax_query', array(
                array(
                    'taxonomy' => 'city',
                    'field'    => 'term_id',
                    'terms'    => $city_ids,
                ),
            ) );
        }
    }
    return $query;
}
add_filter( 'pre_get_posts', 'hide_venues' );

?>