<?php
/**
 * AJAX handler for fetching seasons based on a selected league
 */
function fetch_league_seasons() {
    if (!isset($_POST['league_id'])) {
        wp_send_json_error(['message' => 'No league selected']);
        wp_die();
    }

    $league_id = intval($_POST['league_id']);

    // Fetch seasons (assuming 'season' is a custom post type related to 'league')
    $season_args = array(
        'post_type' => 'season',  // Change this if seasons are stored differently
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'league_id', // Ensure this is the correct meta key
                'value' => $league_id,
                'compare' => '='
            )
        )
    );

    $seasons = get_posts($season_args);
    
    if (!$seasons) {
        wp_send_json_error(['message' => 'No seasons found']);
        wp_die();
    }

    $options = [];
    foreach ($seasons as $season) {
        $options[] = [
            'id' => $season->ID,
            'name' => get_the_title($season->ID),
        ];
    }

    wp_send_json_success(['seasons' => $options]);
    wp_die();
}

add_action('wp_ajax_fetch_league_seasons', 'fetch_league_seasons');
add_action('wp_ajax_nopriv_fetch_league_seasons', 'fetch_league_seasons'); // For non-logged-in users if needed
/**/



/**
 * AJAX handler for fetching cities based on a selected state
 */
function get_city_meta_callback() {
    $term_id = intval($_POST['term_id']);

    $country_id = get_term_meta($term_id, 'city_country', true);
    $state_id = get_term_meta($term_id, 'city_state', true);

    wp_send_json_success(array(
        'country_id' => $country_id,
        'state_id' => $state_id
    ));
}

add_action('wp_ajax_get_city_meta', 'get_city_meta_callback');
add_action('wp_ajax_nopriv_get_city_meta', 'get_city_meta_callback');

?>