<?php

function get_program_filters($filters = ['state', 'city', 'league', 'season', 'age', 'gender']) {
    echo '<div id="filters">';

    if (in_array('state', $filters) || in_array('city', $filters)) {
        $location_taxonomy_filters = ['state', 'city'];
        foreach ($location_taxonomy_filters as $taxonomy) {
            if (!in_array($taxonomy, $filters)) {
                continue;
            }

            $url_term = str_replace('-', ' ', isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '');
            $url_province = str_replace('-', ' ', isset($_GET['province']) ? $_GET['province'] : '');
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ]);

            if (!empty($terms)) {
                echo '<div class="filter">
                        <label>' . ($taxonomy == 'state' ? 'Province' : ucfirst($taxonomy)) . '</label>
                        <select id="' . $taxonomy . '" name="' . $taxonomy . '">
                            <option value="">Select ' . ($taxonomy == 'state' ? 'Province' : ucfirst($taxonomy)) . '</option>';
                foreach ($terms as $term) {
                    $selected = (strtolower($url_term) == strtolower($term->name)) ? 'selected' : '';
                    if ($taxonomy == 'state' && $selected == '') {
                        $selected = (strtolower($url_province) == strtolower($term->name)) ? 'selected' : '';
                    }
                    if(getUserProvince() == strtolower($term->name) && $selected == ''){
                        $selected = 'selected';
                    }
                    echo '<option value="' . $term->name . '" ' . $selected . '>' . $term->name . '</option>';
                }
                echo '</select>
                    </div>';
            }
        }
    }

    if (in_array('league', $filters)) {
        $url_programs = str_replace('-', ' ', isset($_GET['programs']) ? $_GET['programs'] : '');
        $programs = get_posts([
            'post_type' => 'league',
            'status' => 'publish',
            'posts_per_page' => -1,
        ]);

        if (!empty($programs)) {
            echo '<div class="filter">
                    <label>League</label>
                    <select id="programs" name="programs">
                        <option value="">Select League</option>';
            foreach ($programs as $league) {
                $selected = (strtolower($url_programs) == strtolower($league->post_title)) ? 'selected' : '';
                echo '<option value="' . $league->post_title . '" ' . $selected . '>' . $league->post_title . '</option>';
            }
            echo '</select>
                </div>';
        }
    }

    if (in_array('season', $filters)) {
        $url_season = str_replace('-', ' ', isset($_GET['season']) ? $_GET['season'] : '');
        $seasons = ['Spring', 'Summer', 'Fall', 'Winter'];

        if (!empty($seasons)) {
            echo '<div class="filter">
                    <label>Season</label>
                    <select id="season" name="season">
                        <option value="">Select Season</option>';
            foreach ($seasons as $season) {
                $selected = (strtolower($url_season) == strtolower($season)) ? 'selected' : '';
                echo '<option value="' . $season . '" ' . $selected . '>' . $season . '</option>';
            }
            echo '</select>
                </div>';
        }
    }

    if (in_array('age', $filters) || in_array('gender', $filters)) {
        $demographic_taxonomy_filters = ['age', 'gender'];
        foreach ($demographic_taxonomy_filters as $taxonomy) {
            if(!in_array($taxonomy, $filters)){
                continue;
            }

            $url_term = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ]);

            if (!empty($terms)) {
                usort($terms, function ($a, $b) {
                    return strnatcmp($a->name, $b->name);
                });
                echo '<div class="filter">
                        <label>' . ucfirst($taxonomy) . '</label>
                        <select id="' . $taxonomy . '" name="' . $taxonomy . '">
                            <option value="">Select ' . ucfirst($taxonomy) . '</option>';
                foreach ($terms as $term) {
                    $selected = (strtolower($url_term) == strtolower($term->name)) ? 'selected' : '';
                    echo '<option value="' . $term->name . '" ' . $selected . '>' . $term->name . '</option>';
                }
                echo '</select>
                    </div>';
            }
        }
    }

    echo '</div>';
}
?>