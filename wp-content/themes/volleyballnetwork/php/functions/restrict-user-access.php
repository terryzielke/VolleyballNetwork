<?php
/**
 * Restrict user access
 */
function hide_other_posts_for_editors( $query ) {
    if ( ! current_user_can('administrator') ) {
        global $pagenow;
        if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], array( 'post', 'page', 'league', 'local-league', 'program' ) ) ) {
            global $current_user;
            get_currentuserinfo();
            $query->set( 'author', $current_user->ID );
        }
    }
    return $query;
}
add_filter( 'pre_get_posts', 'hide_other_posts_for_editors' );


/**
 * Filter posts by user taxonomy terms for editors in the backend.
 */
function filter_posts_by_user_taxonomy_backend($query) {
    // Check if we're in the admin area and the current user is an editor.
    if (is_admin() && current_user_can('editor')) {
        // Get the current user.
        $current_user = wp_get_current_user();

        // Check if the user is logged in.
        if ($current_user->exists()) {
            // Get the user's taxonomy terms.
            $user_countries = get_user_meta($current_user->ID, 'user_country', true);
            $user_states = get_user_meta($current_user->ID, 'user_state', true);
            $user_cities = get_user_meta($current_user->ID, 'user_city', true);

            // Check if the query is for the post types we want to filter.
            if ($query->get('post_type') === 'local-league' || $query->get('post_type') === 'venue') {

                $tax_query = array('relation' => 'AND');

                if (!empty($user_countries)) {
                    $tax_query[] = array(
                        'taxonomy' => 'country',
                        'field' => 'term_id',
                        'terms' => is_array($user_countries) ? $user_countries : array($user_countries),
                    );
                }

                if (!empty($user_states)) {
                    $tax_query[] = array(
                        'taxonomy' => 'state',
                        'field' => 'term_id',
                        'terms' => is_array($user_states) ? $user_states : array($user_states),
                    );
                }

                if (!empty($user_cities)) {
                    $tax_query[] = array(
                        'taxonomy' => 'city',
                        'field' => 'term_id',
                        'terms' => is_array($user_cities) ? $user_cities : array($user_cities),
                    );
                }

                if (count($tax_query) > 1) {
                  $query->set('tax_query', $tax_query);
                }
            }
        }
    }
}
add_action('pre_get_posts', 'filter_posts_by_user_taxonomy_backend');

/** */
?>