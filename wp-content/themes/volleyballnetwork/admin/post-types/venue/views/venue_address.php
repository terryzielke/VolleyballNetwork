<?php	
	// Set post nonce
	wp_nonce_field('volleyball_network_venue_nonce', 'volleyball_network_venue_nonce');
	// Get post meta data
	$venue_address  	= get_post_meta( $post->ID, 'venue_address', true );
	$venue_postal_code	= get_post_meta( $post->ID, 'venue_postal_code', true );
?>

<div class="frame">
	<label for="venue_address">Street Address</label>
	<input type="text" id="venue_address" name="venue_address" value="<?=$venue_address?>" />
	<sup>*Do not include city, or state</sup>
	
	<label for="venue_postal_code">Postal Code</label>
	<input type="text" id="venue_postal_code" name="venue_postal_code" value="<?=$venue_postal_code?>" />
</div>