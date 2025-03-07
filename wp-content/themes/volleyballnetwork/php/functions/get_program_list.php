<?php

function get_programs_list($state_filter = '', $city_filter = '', $league_filter = '') {
    ob_start();
    
    echo '<ul id="programs">';

    $args = array(
        'post_type' => 'program',
        'status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
    );
    $programs = new WP_Query($args);
    while ($programs->have_posts()) {
        $programs->the_post();

        $gender = get_the_terms(get_the_ID(), 'gender');
        $gender_list = $gender ? implode(' ', wp_list_pluck($gender, 'name')) : '';

        $age = get_the_terms(get_the_ID(), 'age');
        $ages_list = $age ? implode(',', wp_list_pluck($age, 'name')) : '';

        $age_values = array_map(function($a) { return (int) $a->name; }, $age ?: []);
        $lowest_age = $age_values ? min($age_values) : 18;
        $highest_age = $age_values ? max($age_values) : 0;
        $age_range = "$lowest_age - $highest_age";

        $program_league = get_post_meta(get_the_ID(), 'program_league', true);
        $program_venue = get_post_meta(get_the_ID(), 'program_venue', true);
        $program_season = get_post_meta(get_the_ID(), 'program_season', true);
        $program_price = get_post_meta(get_the_ID(), 'program_price', true);
        $program_registration = get_post_meta(get_the_ID(), 'program_registration', true);
        $program_start_date = get_post_meta(get_the_ID(), 'program_start_date', true);
        $program_end_date = get_post_meta(get_the_ID(), 'program_end_date', true);
        $program_start_time = get_post_meta(get_the_ID(), 'program_start_time', true);
        $program_end_time = get_post_meta(get_the_ID(), 'program_end_time', true);
        $program_days = get_post_meta(get_the_ID(), 'program_days', true);
        $days = is_array($program_days) ? implode(', ', $program_days) : '';

        $venue_address = get_post_meta($program_venue, 'venue_address', true);
        $venue_city = get_the_terms($program_venue, 'city');
        $city = $venue_city ? implode(' ', wp_list_pluck($venue_city, 'name')) : '';
        $venue_state = get_the_terms($program_venue, 'state');
        $state = $venue_state ? implode(' ', wp_list_pluck($venue_state, 'name')) : '';
        $venue_postal_code = get_post_meta($program_venue, 'venue_postal_code', true);

        if (($state_filter && stripos($state, $state_filter) === false) ||
            ($city_filter && stripos($city, $city_filter) === false) ||
            ($league_filter && stripos(get_the_title($program_league), $league_filter) === false)) {
            continue;
        }

        $season_meta_keys = [
            'winter' => 'season_winter',
            'spring' => 'season_spring',
            'summer' => 'season_summer',
            'fall' => 'season_fall'
        ];
        $season_key = $season_meta_keys[$program_season] ?? '';

        $this_season_start_date = get_post_meta($program_league, "{$season_key}_start_date", true);
        $this_season_end_date = get_post_meta($program_league, "{$season_key}_end_date", true);
        $this_season_registration = get_post_meta($program_league, "{$season_key}_registration", true);
        $this_season_price = get_post_meta($program_league, "{$season_key}_price", true);
        ?>
        <li class="program" data-state="<?= esc_attr($state) ?>" data-city="<?= esc_attr($city) ?>" data-programs="<?= esc_attr(get_the_title($program_league)) ?>" data-season="<?= esc_attr($program_season) ?>" data-ages="<?= esc_attr($ages_list) ?>" data-gender="<?= esc_attr($gender_list) ?>">
            <div class="program-header">
                <h3><?= esc_html(get_the_title($program_venue)) ?> - <span><?= esc_html(get_the_title($program_league)) ?> - </span><span>Age <?= esc_html($age_range) ?><span class="<?= esc_attr($gender_list) ?>"></span></span></h3>
            </div>
            <div class="program-details">
                <div class="row">
                    <div class="col col-12 col-md-1">
                        <div>
                            <h4 class="orange"><?= esc_html($program_season) ?></h4>
                        </div>
                    </div>
                    <div class="col col-12 col-md-3">
                        <h4><span><?= esc_html($city) ?></span></h4>
                        <p><span><a href="https://www.google.com/maps/search/<?= urlencode("$venue_address $city, $state $venue_postal_code") ?>" target="_blank"><?= esc_html($venue_address) ?></a></span></p>
                        <p><span><?= esc_html($city) ?></span>, <span><?= esc_html($state) ?></span>. <span><?= esc_html($venue_postal_code) ?></span></p>
                    </div>
                    <div class="col col-12 col-md-3">
                        <h4>Schedule</h4>
                        <p><strong>Start Date: </strong><?= esc_html($program_start_date ?: $this_season_start_date) ?></p>
                        <p><strong>End Date: </strong><?= esc_html($program_end_date ?: $this_season_end_date) ?></p>
                        <p><strong>Days: </strong><span><?= esc_html($days) ?></span></p>
                        <p><strong>Time: </strong><span><?= esc_html($program_start_time) ?></span> - <span><?= esc_html($program_end_time) ?></span></p>
                    </div>
                    <div class="col col-12 col-md-3">
                        <h4>Details</h4>
                        <p><strong>Price: </strong><?= esc_html($program_price ?: $this_season_price) ?></p>
                        <p><strong>Gender: </strong><?= esc_html($gender_list) ?></p>
                    </div>
                    <div class="col col-12 col-md-2">
                        <a href="<?= esc_url($program_registration ?: $this_season_registration) ?>" target="_blank" class="button btn"> Register Now </a>
                    </div>
                </div>
            </div>
        </li>
        <?php
    }
    wp_reset_query();
    echo '</ul>';
    
    return ob_get_clean();
}

?>