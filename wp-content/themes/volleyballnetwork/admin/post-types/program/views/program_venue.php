<?php
	// Set post nonce
	wp_nonce_field('volleyball_network_program_nonce', 'volleyball_network_program_nonce');
	
	// Get post meta data
	$program_venue = get_post_meta( $post->ID, 'program_venue', true );
?>

<div class="frame">
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
</div>