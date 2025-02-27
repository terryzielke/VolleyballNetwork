<?php	
	// Set post nonce
	wp_nonce_field('volleyball_network_program_nonce', 'volleyball_network_program_nonce');
	// Get post meta data
	$program_league = get_post_meta( $post->ID, 'program_league', true );
	$program_season = get_post_meta( $post->ID, 'program_season', true );
	$program_venue = get_post_meta( $post->ID, 'program_venue', true );
	$program_registration = get_post_meta( $post->ID, 'program_registration', true );
	$program_price = get_post_meta( $post->ID, 'program_price', true );
?>

<div class="frame">

	<label for="program_league">League</label>
	<select name="program_league" id="program_league">
		<?php
		$current_user = wp_get_current_user();
		if ($current_user && $current_user->ID) {
			if (current_user_can('administrator')) {
				// Administrator: Show all leagues
				$local_league_args = array(
					'post_type' => 'local-league',
					'posts_per_page' => -1,
				);
			} else {
				// Non-administrator: Filter by user's city
				$user_cities = wp_get_object_terms($current_user->ID, 'user_city', array('fields' => 'ids'));

				if (!empty($user_cities)) {
					$local_league_args = array(
						'post_type' => 'local-league',
						'posts_per_page' => -1,
						'tax_query' => array(
							array(
								'taxonomy' => 'city',
								'field'    => 'term_id',
								'terms'    => $user_cities,
							),
						),
					);
				} else {
					$local_league_args = false; // Set to false to handle no city case
					echo '<option value="">No leagues available for your city.</option>';
				}
			}
			if ($local_league_args !== false) { // Check if we should perform a get_posts()
				$leagues = get_posts($local_league_args);

				foreach ($leagues as $league) {
					$selected = isset($program_league) && $program_league == $league->ID ? 'selected' : '';
	
					// Get the terms for the current league
					$terms = wp_get_post_terms($league->ID, 'city', array('fields' => 'names'));
					$term_names = !empty($terms) ? implode(', ', $terms) : ''; // Combine term names
	
					$option_text = get_the_title($league->ID);
					if (!empty($term_names)) {
						$option_text .= ' - ' . $term_names; // Append terms to title
					}
	
					echo '<option value="' . $league->ID . '" ' . $selected . '>' . $option_text . '</option>';
				}
			}

		} else {
			echo '<option value="">User not logged in or city not found.</option>';
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
			$venue_args = array(
				'post_type' => 'venue',
				'posts_per_page' => -1,
			);
			$venues = get_posts($venue_args);
			foreach ($venues as $venue) {
				$selected = $program_venue == $venue->ID ? 'selected' : '';
				echo '<option value="' . $venue->ID . '" ' . $selected . '>' . get_the_title($venue->ID) . '</option>';
			}
		?>
	</select>

	<label for="program_registration">Registration URL</label>
	<input type="text" name="program_registration" id="program_registration" value="<?= $program_registration ?>" placeholder="https://">

	<label for="program_price">Price</label>
	<input type="text" name="program_price" id="program_price" value="<?= $program_price ?>" placeholder="$0.00">

</div>