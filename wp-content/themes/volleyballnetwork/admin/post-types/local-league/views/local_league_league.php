<?php	
	// Set post nonce
	wp_nonce_field('volleyball_network_local_league_nonce', 'volleyball_network_local_league_nonce');

	// Get post meta data
	$local_league_league = get_post_meta( $post->ID, 'local_league_league', true );
?>

<div class="frame">
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