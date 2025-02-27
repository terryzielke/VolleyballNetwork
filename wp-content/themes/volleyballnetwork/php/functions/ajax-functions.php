<?php
/*
	AJAX HOOKS
*
function get_league_seasons() {
    if (isset($_POST['league_id'])) {
        $league_id = intval($_POST['league_id']);
        $seasons = get_post_meta($league_id, 'seasons', true);

        if (!empty($seasons)) {
            wp_send_json_success($seasons);
        } else {
            wp_send_json_error('No seasons found');
        }
    } else {
        wp_send_json_error('Invalid league ID');
    }
}
add_action('wp_ajax_get_league_seasons', 'get_league_seasons');
/**/


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
?>