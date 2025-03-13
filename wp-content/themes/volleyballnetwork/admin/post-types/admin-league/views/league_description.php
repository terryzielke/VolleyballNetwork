<?php
	// Get post meta data
	$league_description = get_post_meta( $post->ID, 'league_description', true );
?>
<div class="frame">
    <?php wp_editor( $league_description, 'league_description', array('textarea_name' => 'league_description') ); ?>
</div>