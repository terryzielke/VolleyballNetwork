<?php
	// Get post meta data
	$league_primary_header = get_post_meta( $post->ID, 'league_primary_header', true );
	$league_sub_header = get_post_meta( $post->ID, 'league_sub_header', true );
	$league_excerpt = get_post_meta( $post->ID, 'league_excerpt', true );
?>

<div class="frame">
    <label for="league_primary_header">Primary Header</label>
    <input type="text" name="league_primary_header" value="<?= $league_primary_header ?>" placeholder="<?=get_the_title()?>" />

    <label for="league_sub_header">Sub Header</label>
    <input type="text" name="league_sub_header" value="<?= $league_sub_header ?>" />

    <label for="league_excerpt">Excerpt</label>
    <?php wp_editor( $league_excerpt, 'league_excerpt', array('textarea_name' => 'league_excerpt') ); ?>
</div>