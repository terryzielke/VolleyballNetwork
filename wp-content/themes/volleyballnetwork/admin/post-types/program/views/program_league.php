<?php	
	// Set post nonce
	wp_nonce_field('volleyball_network_program_nonce', 'volleyball_network_program_nonce');
	// Get post meta data
	$program_league = get_post_meta( $post->ID, 'program_league', true );
	$program_division = get_post_meta( $post->ID, 'program_division', true );
	$program_season = get_post_meta( $post->ID, 'program_season', true );
	$program_venue = get_post_meta( $post->ID, 'program_venue', true );
	$program_registration = get_post_meta( $post->ID, 'program_registration', true );
	$program_price = get_post_meta( $post->ID, 'program_price', true );
?>

<div class="frame">

	<label for="program_league">League</label>
	<select name="program_league" id="program_league">
		<?php
            if (!current_user_can('administrator')) {
				$current_user = wp_get_current_user();
				if ($current_user && $current_user->ID){
					$user_division = get_user_meta($current_user->ID, 'user_division', true);
					$league_args = array(
						'post_type' => 'local-league',
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'meta_query' => array(
							array(
								'key' => 'local_league_division',
								'value' => $user_division,
								'compare' => '=',
							),
						),
					);
					$leagues = get_posts($league_args);
					foreach ($leagues as $league) {
						$selected = $program_league == $league->ID ? 'selected' : '';
						echo '<option value="' . $league->ID . '" ' . $selected . '>' . get_the_title($league->ID) . '</option>';
					}
				}
			}
			else{
				$league_args = array(
					'post_type' => 'local-league',
                    'post_status' => 'publish',
					'posts_per_page' => -1,
				);
				$leagues = get_posts($league_args);
				foreach ($leagues as $league) {
					$selected = $program_league == $league->ID ? 'selected' : '';
					echo '<option value="' . $league->ID . '" ' . $selected . '>' . get_the_title($league->ID) . '</option>';
				}
			}
		?>
	</select>

    <label for="program_division">Division</label>
    <select name="program_division" id="program_division">
        <?php
            // if user is not admin, get user_division meta from profile
            if (!current_user_can('administrator')) {
                $user_division = get_user_meta(get_current_user_id(), 'user_division', true);

                // get all divisions where the division is the same as the user's division
                $division_args = array(
                    'post_type' => 'division',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'p' => $user_division,
                    'suppress_filters' => true,
                );
                $divisions = get_posts($division_args);
                foreach ($divisions as $division) {
                    $selected = $program_division == $division->ID ? 'selected' : '';
                    echo '<option value="' . $division->ID . '" ' . $selected . '>' . get_the_title($division->ID) . '</option>';
                }
            }
            else{
                $division_args = array(
                    'post_type' => 'division',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );
                $divisions = get_posts($division_args);
                foreach ($divisions as $division) {
                    $selected = $program_division == $division->ID ? 'selected' : '';
                    echo '<option value="' . $division->ID . '" ' . $selected . '>' . get_the_title($division->ID) . '</option>';
                }
            }
        ?>
    </select>

	<label for="program_season">Season</label>
	<select name="program_season" id="program_season" season="<?= $program_season ?>">
		<option value="winter" <?=($program_season == 'winter' ? ' selected="selected"' : '')?> >Winter</option>
		<option value="spring" <?=($program_season == 'spring' ? ' selected="selected"' : '')?> >Spring</option>
		<option value="summer" <?=($program_season == 'summer' ? ' selected="selected"' : '')?> >Summer</option>
		<option value="fall" <?=($program_season == 'fall' ? ' selected="selected"' : '')?> >Fall</option>
	</select>

	<label for="program_venue">Venue</label>
	<select name="program_venue">
		<?php

			if (!current_user_can('administrator')) {
				//get the city taxonomy terms for the post with the id $user_division
				$city_terms = get_the_terms( $user_division, 'city' );
				//get the term ids for the city terms
				$city_ids = array();
				foreach ( $city_terms as $city_term ) {
					$city_ids[] = $city_term->term_id;
				}
				// get all venues where the city taxonomy term matches the user's division
				$venue_args = array(
					'post_type' => 'venue',
					'posts_per_page' => -1,
					'tax_query' => array(
						array(
							'taxonomy' => 'city',
							'field' => 'term_id',
							'terms' => $city_ids,
						),
					),
				);
				$venues = get_posts($venue_args);
				foreach ($venues as $venue) {
					$selected = $program_venue == $venue->ID ? 'selected' : '';
					echo '<option value="' . $venue->ID . '" ' . $selected . '>' . get_the_title($venue->ID) . '</option>';
				}
			}
			else{
				$venue_args = array(
					'post_type' => 'venue',
					'posts_per_page' => -1,
				);
				$venues = get_posts($venue_args);
				foreach ($venues as $venue) {
					$selected = $program_venue == $venue->ID ? 'selected' : '';
					echo '<option value="' . $venue->ID . '" ' . $selected . '>' . get_the_title($venue->ID) . '</option>';
				}
			}
		?>
	</select>
	
	<label for="program_registration" style="color:#FFA736">Override Season Registration URL</label>
	<input type="text" name="program_registration" id="program_registration" value="<?= $program_registration ?>" placeholder="https://">

	<label for="program_price" style="color:#FFA736">Override Season Price</label>
	<input type="text" name="program_price" id="program_price" value="<?= $program_price ?>" placeholder="$0.00">
</div>