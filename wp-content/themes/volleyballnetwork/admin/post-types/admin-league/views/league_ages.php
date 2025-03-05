<?php
	// Get post meta data
	$league_ages = get_post_meta( $post->ID, 'league_ages', true );
?>

<div class="frame">
    <input type="text" name="league_ages" value="<?= $league_ages ?>" />
</div>