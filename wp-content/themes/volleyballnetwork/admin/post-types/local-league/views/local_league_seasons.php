<?php
	// Get post meta data
	$season_winter_registration = get_post_meta( $post->ID, 'season_winter_registration', true );
    $season_winter_price = get_post_meta( $post->ID, 'season_winter_price', true );
    $season_spring_registration = get_post_meta( $post->ID, 'season_spring_registration', true );
    $season_spring_price = get_post_meta( $post->ID, 'season_spring_price', true );
    $season_summer_registration = get_post_meta( $post->ID, 'season_summer_registration', true );
    $season_summer_price = get_post_meta( $post->ID, 'season_summer_price', true );
    $season_fall_registration = get_post_meta( $post->ID, 'season_fall_registration', true );
    $season_fall_price = get_post_meta( $post->ID, 'season_fall_price', true );
?>

<div class="frame">

	<label for="season_winter_registration">Winter</label>
	<input type="text" name="season_winter_registration" id="season_winter_registration" value="<?= $season_winter_registration ?>" placeholder="Registration URL: https://" style="margin-bottom:10px;" />
    <input type="text" name="season_winter_price" id="season_winter_price" value="<?= $season_winter_price ?>" placeholder="Price: $0.00" />

    <label for="season_spring_registration">Spring</label>
    <input type="text" name="season_spring_registration" id="season_spring_registration" value="<?= $season_spring_registration ?>" placeholder="Registration URL: https://" style="margin-bottom:10px;" />
    <input type="text" name="season_spring_price" id="season_spring_price" value="<?= $season_spring_price ?>" placeholder="Price: $0.00" />

    <label for="season_summer_registration">Summer</label>
    <input type="text" name="season_summer_registration" id="season_summer_registration" value="<?= $season_summer_registration ?>" placeholder="Registration URL: https://" style="margin-bottom:10px;" />
    <input type="text" name="season_summer_price" id="season_summer_price" value="<?= $season_summer_price ?>" placeholder="Price: $0.00" />

    <label for="season_fall_registration">Fall</label>
    <input type="text" name="season_fall_registration" id="season_fall_registration" value="<?= $season_fall_registration ?>" placeholder="Registration URL: https://" style="margin-bottom:10px;" />
    <input type="text" name="season_fall_price" id="season_fall_price" value="<?= $season_fall_price ?>" placeholder="Price: $0.00" />

</div>