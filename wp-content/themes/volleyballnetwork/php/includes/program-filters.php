<?php
/**
 * PROGRAM FILTERS
 */
echo '<div id="filters">';


// get all location taxonomy terms
$location_taxonomy_filters = array('state', 'city');

foreach($location_taxonomy_filters as $taxonomy){
    // get UTL query string
    $url_term = str_replace('-', ' ', isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : 'default_value');
    $url_province = str_replace('-', ' ', isset($_GET['province']) ? $_GET['province'] : 'default_value');
    // get all terms for taxonomy
    $terms = get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ) );
    if( !empty($terms) ) {
        echo '<div class="filter">
                <label>' . ($taxonomy == 'state' ? 'Province' : ucfirst($taxonomy)) . '</label>
                <select id="' . $taxonomy . '" name="' . $taxonomy . '">
                    <option value="">Select ' . ($taxonomy == 'state' ? 'Province' : ucfirst($taxonomy)) . '</option>';
                    foreach($terms as $term) {
                        $selected = ( strtolower($url_term) == strtolower($term->name)) ? 'selected' : '';
                        if($taxonomy == 'state' && $selected == '') {
                            $selected = ( strtolower($url_province) == strtolower($term->name)) ? 'selected' : '';
                        }
                        echo '<option value="' . $term->name . '" ' . $selected . '>' . $term->name . '</option>';
                    }
                echo '</select>
        </div>';
    }
}


$url_programs = str_replace('-', ' ', isset($_GET['programs']) ? $_GET['programs'] : 'default_value');
// get all posts from league post type
$programs = get_posts( array(
    'post_type' => 'league',
    'status' => 'publish',
    'posts_per_page' => -1,
) );
if( !empty($programs) ) {
    echo '<div class="filter">
            <label>League</label>
            <select id="programs" name="programs">
                <option value="">Select League</option>';
                foreach($programs as $league) {
                    $selected = ( strtolower($url_programs) == strtolower($league->post_title)) ? 'selected' : '';
                    echo '<option value="' . $league->post_title . '" ' . $selected . '>' . $league->post_title . '</option>';
                }
            echo '</select>
    </div>';
}


$url_season = str_replace('-', ' ', isset($_GET['season']) ? $_GET['season'] : 'default_value');
// season array
$seasons = array('Spring', 'Summer', 'Fall', 'Winter');
if( !empty($seasons) ) {
    echo '<div class="filter">
            <label>Season</label>
            <select id="season" name="season">
                <option value="">Select Season</option>';
                foreach($seasons as $season) {
                    $selected = ( strtolower($url_season) == strtolower($season)) ? 'selected' : '';
                    echo '<option value="' . $season . '" ' . $selected . '>' . $season . '</option>';
                }
            echo '</select>
    </div>';
}


// get all demographic taxonomy terms
$demographic_taxonomy_filters = array('age', 'gender');

foreach($demographic_taxonomy_filters as $taxonomy){
    // get UTL query string
    $url_term = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : 'default_value';
    // get all terms for taxonomy
    $terms = get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ) );
    if( !empty($terms) ) {
        usort($terms, function ($a, $b) {
            return strnatcmp($a->name, $b->name);
        });
        echo '<div class="filter">
                <label>' . ucfirst($taxonomy) . '</label>
                <select id="' . $taxonomy . '" name="' . $taxonomy . '">
                    <option value="">Select ' . ucfirst($taxonomy) . '</option>';
                    foreach($terms as $term) {
                        $selected = ( strtolower($url_term) == strtolower($term->name)) ? 'selected' : '';
                        echo '<option value="' . $term->name . '" ' . $selected . '>' . $term->name . '</option>';
                    }
                echo '</select>
        </div>';
    }
}

/**
 * END OF FILTERS
 */
echo '</div>';
?>