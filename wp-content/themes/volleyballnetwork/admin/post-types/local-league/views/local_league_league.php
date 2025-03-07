<?php	
	// Set post nonce
	wp_nonce_field('volleyball_network_local_league_nonce', 'volleyball_network_local_league_nonce');

	// Get post meta data
	$local_league_league = get_post_meta( $post->ID, 'local_league_league', true );
	$local_league_division = get_post_meta( $post->ID, 'local_league_division', true );
?>

<div class="frame">
    <label for="local_league_league">League Type</label>
    <select name="local_league_league" id="local_league_league">
        <?php
            $league_args = array(
                'post_type' => 'league',
                'posts_per_page' => -1,
            );
            $leagues = get_posts($league_args);
            foreach ($leagues as $league) {
                $selected = $local_league_league == $league->ID ? 'selected' : '';
                echo '<option value="' . $league->ID . '" ' . $selected . '>' . get_the_title($league->ID) . '</option>';
            }
        ?>	
    </select>

    <label for="local_league_division">Division</label>
    <select name="local_league_division" id="local_league_division">
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
                    $selected = $local_league_division == $division->ID ? 'selected' : '';
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
                    $selected = $local_league_division == $division->ID ? 'selected' : '';
                    echo '<option value="' . $division->ID . '" ' . $selected . '>' . get_the_title($division->ID) . '</option>';
                }
            }
        ?>
    </select>
</div>

<script>
    jQuery(document).ready(function($) {

        function updatePostPermalinkWithCity(){
            var city = $('#citychecklist').find('input:checked').first().val();
            var city_name = $('#citychecklist').find('input:checked').first().parent().text().toLowerCase().replace(/ /g, '');
            $('#sample-permalink #editable-post-name').append('-' + city_name);
        }

        $('#citychecklist').find('input').change(function(){
            updatePostPermalinkWithCity();
        });
        updatePostPermalinkWithCity();

    });
</script>