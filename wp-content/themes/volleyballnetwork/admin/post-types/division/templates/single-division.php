<?php get_header(); the_post(); ?>

<section class="header">
	<div class="container">
		<div class="text-container" style="margin-bottom: 0;">
			<h1><?= the_title() ?></h1>
		</div>
        <div id="league-filters">
		    <div class="text-container" style="margin-bottom: 0;">
                <?php
                    // get this division name
                    $division = strtolower(get_the_title());
                    // array of leagues
                    $leagues = array();
                    // get all local-leagues with the city taxonomy matching the division name
                    $args = array(
                        'post_type' => 'local-league',
                        'orderby' => 'title',
                        'order' => 'DESC',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'city',
                                'field' => 'name',
                                'terms' => $division
                            )
                        )
                    );
                    $local_leagues = new WP_Query($args);
                    while ($local_leagues->have_posts()) {
                        $local_leagues->the_post();
                        // output league filter button
                        echo '<a class="league-filter" data-league-id="' . get_the_ID() . '">' . get_the_title() . '</a>';
                        // add local league id to array
                        array_push($leagues, get_the_ID());
                    }
                    // reset wp_query
                    wp_reset_query();
                ?>
                <a class="league-filter active" data-league-id="all">Show All</a>
            </div>
        </div>
	</div>
</section>

<section class="page">
	<div class="container">
		<div class="text-container">
			<div class="content">
				<?= the_content() ?>
			</div>
		</div>
	</div>
</section>

<section class="programs-section">
    <div class="container">
        <div class="text-container">
            <div id="league-container">
                <?php
                    foreach($leagues as $league_id) {
                        $league = get_post($league_id);
                        $admin_league = get_post_meta($league_id, 'local_league_league', true);
                        ?>
                        <div class="league" data-league-id="<?=$league_id?>">
                            <h2 class="orange"><?=$league->post_title?></h2>
                            <div class="row">
                                <div class="col-md-4">
                                    <h3>Program</h3>
                                    <p><?= get_post_meta($admin_league, 'league_program_info', true) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h3>Format</h3>
                                    <p><?= get_post_meta($admin_league, 'league_format_info', true) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h3>Expectation</h3>
                                    <p><?= get_post_meta($admin_league, 'league_expectation_info', true) ?></p>
                                </div>
                            </div> 
                        <?php
                        // get all programs where the program_league matches the league id
                        $args = array(
                            'post_type' => 'program',
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => 'program_league',
                                    'value' => $league_id
                                )
                            )
                        );
                        $programs = new WP_Query($args);
                        echo '<ul class="programs">';
                        while ($programs->have_posts()) {
                            $programs->the_post();

                            // array of terms for gender taxonomy
                            $gender = get_the_terms( get_the_ID(), 'gender');
                            // comma separated list
                            $gender_list = '';
                            foreach($gender as $g) {
                                $gender_list .= $g->name . ' ';
                            }
                            // array of terms for age taxonomy
                            $age = get_the_terms( get_the_ID(), 'age');
                            // get lowest age
                            $lowest_age = 18;
                            foreach($age as $a) {
                                if ($a->name < $lowest_age) {
                                    $lowest_age = $a->name;
                                }
                            }
                            // get highest age
                            $highest_age = 0;
                            foreach($age as $a) {
                                if ($a->name > $highest_age) {
                                    $highest_age = $a->name;
                                }
                            }
                            $age_range = $lowest_age . ' - ' . $highest_age;

                            // get program meta data
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
                            $days = '';
                            foreach($program_days as $d) {
                                if($days != '') {
                                    $days .= ', ';
                                }
                                $days .= $d;
                            }
                            // get venue meta data
                            $venue_address = get_post_meta($program_venue, 'venue_address', true);
                            $venue_city = get_the_terms( $program_venue, 'city');
                            $city = '';
                            foreach($venue_city as $c) {
                                $city .= $c->name . ' ';
                            }
                            $venue_state = get_the_terms( $program_venue, 'state');
                            $state = '';
                            foreach($venue_state as $s) {
                                $state .= $s->name . ' ';
                            }
                            $venue_postal_code = get_post_meta($program_venue, 'venue_postal_code', true);
                            // get league meta data
                            $this_season_start_date = '';
                            $this_season_end_date = '';
                            $this_season_registration = '';
                            $this_season_price = '';
                            if($program_season == 'winter'){
                                $this_season_start_date = get_post_meta($program_league, 'season_winter_start_date', true);
                                $this_season_end_date = get_post_meta($program_league, 'season_winter_end_date', true);
                                $this_season_registration = get_post_meta($program_league, 'season_winter_registration', true);
                                $this_season_price = get_post_meta($program_league, 'season_winter_price', true);
                            }
                            elseif($program_season == 'spring'){
                                $this_season_start_date = get_post_meta($program_league, 'season_spring_start_date', true);
                                $this_season_end_date = get_post_meta($program_league, 'season_spring_end_date', true);
                                $this_season_registration = get_post_meta($program_league, 'season_spring_registration', true);
                                $this_season_price = get_post_meta($program_league, 'season_spring_price', true);
                            }
                            elseif($program_season == 'summer'){
                                $this_season_start_date = get_post_meta($program_league, 'season_summer_start_date', true);
                                $this_season_end_date = get_post_meta($program_league, 'season_summer_end_date', true);
                                $this_season_registration = get_post_meta($program_league, 'season_summer_registration', true);
                                $this_season_price = get_post_meta($program_league, 'season_summer_price', true);
                            }
                            elseif($program_season == 'fall'){
                                $this_season_start_date = get_post_meta($program_league, 'season_fall_start_date', true);
                                $this_season_end_date = get_post_meta($program_league, 'season_fall_end_date', true);
                                $this_season_registration = get_post_meta($program_league, 'season_fall_registration', true);
                                $this_season_price = get_post_meta($program_league, 'season_fall_price', true);
                            }
                            ?>
                            <li class="program">
                                <div class="program-header">
                                    <h3><?=get_the_title($program_venue)?> - <span><?=get_the_title($program_league)?> - </span><span>Age <?=$age_range?><span class="<?=$gender_list?>"></span></span></h3>
                                </div>
                                <div class="program-details">
                                    <div class="row">
                                        <div class="col col-12 col-md-1">
                                            <div>
                                                <h4 class="orange"><?=$program_season?></h4>
                                            </div>
                                        </div>
                                        <div class="col col-12 col-md-3">
                                            <h4><span><?=$city?></span></h4>
                                            <p><span><a href="https://www.google.com/maps/search/<?=$venue_address.' '.$city.', '.$state.'. '.$venue_postal_code?>" target="_blank"><?=$venue_address?></a></span></p>
                                            <p><span><?=$city?></span>, <span><?=$state?></span>. <span><?=$venue_postal_code?></span></p>
                                        </div>
                                        <div class="col col-12 col-md-3">
                                            <h4>Schedule</h4>
                                            <p><strong>Start Date: </strong><?=($program_start_date ? $program_start_date : $this_season_start_date)?></p>
                                            <p><strong>End Date: </strong><?=($program_end_date ? $program_end_date : $this_season_end_date)?></p>
                                            <p><strong>Days: </strong><span><?=$days?></span></p>
                                            <p><strong>Time: </strong><span><?=$program_start_time?></span> - <span><?=$program_end_time?></span></p>
                                        </div>
                                        <div class="col col-12 col-md-3">
                                            <h4>Details</h4>
                                            <p><strong>Price: </strong><?=($program_price ? $program_price : $this_season_price)?></p>
                                            <p><strong>Gender: </strong><?=$gender_list?></p>
                                        </div>
                                        <div class="col col-12 col-md-2">
                                            <a href="<?=($program_registration ? $program_registration : $this_season_registration)?>" target="_blank" class="button btn"> Register Now </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                        echo '</ul>';
                        wp_reset_query();
                        ?>
                        </div><!-- end league -->
                        <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</section>

<?php get_footer(); ?>