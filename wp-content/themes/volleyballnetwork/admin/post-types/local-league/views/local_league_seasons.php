<?php
	// Get post meta data
	$season_winter_registration = get_post_meta( $post->ID, 'season_winter_registration', true );
    $season_winter_price = get_post_meta( $post->ID, 'season_winter_price', true );
    $season_winter_start_date = get_post_meta( $post->ID, 'season_winter_start_date', true );
    $season_winter_end_date = get_post_meta( $post->ID, 'season_winter_end_date', true );
    $season_winter_note = get_post_meta( $post->ID, 'season_winter_note', true );
    $season_spring_registration = get_post_meta( $post->ID, 'season_spring_registration', true );
    $season_spring_price = get_post_meta( $post->ID, 'season_spring_price', true );
    $season_spring_start_date = get_post_meta( $post->ID, 'season_spring_start_date', true );
    $season_spring_end_date = get_post_meta( $post->ID, 'season_spring_end_date', true );
    $season_spring_note = get_post_meta( $post->ID, 'season_spring_note', true );
    $season_summer_registration = get_post_meta( $post->ID, 'season_summer_registration', true );
    $season_summer_price = get_post_meta( $post->ID, 'season_summer_price', true );
    $season_summer_start_date = get_post_meta( $post->ID, 'season_summer_start_date', true );
    $season_summer_end_date = get_post_meta( $post->ID, 'season_summer_end_date', true );
    $season_summer_note = get_post_meta( $post->ID, 'season_summer_note', true );
    $season_fall_registration = get_post_meta( $post->ID, 'season_fall_registration', true );
    $season_fall_price = get_post_meta( $post->ID, 'season_fall_price', true );
    $season_fall_start_date = get_post_meta( $post->ID, 'season_fall_start_date', true );
    $season_fall_end_date = get_post_meta( $post->ID, 'season_fall_end_date', true );
    $season_fall_note = get_post_meta( $post->ID, 'season_fall_note', true );

    // default start dates
    $default_winter_start_date  = date('Y') . '-02-01';
    $default_spring_start_date  = date('Y') . '-05-05';
    $default_summer_start_date  = date('Y') . '-07-02';
    $default_fall_start_date    = date('Y') . '-09-01';

    // default end dates
    $default_winter_end_date    = date('Y') . '-03-22';
    $default_spring_end_date    = date('Y') . '-06-14';
    $default_summer_end_date    = date('Y') . '-08-08';
    $default_fall_end_date      = date('Y') . '-12-01';
?>

<div class="frame">

	<h3>Winter</h3>
    <label for="season_winter_registration">Registration URL</label>
	<input type="text" name="season_winter_registration" id="season_winter_registration" value="<?= $season_winter_registration ?>" placeholder="https://" />
    <label for="season_winter_price">Price</label>
    <input type="text" name="season_winter_price" id="season_winter_price" value="<?= $season_winter_price ?>" placeholder="$0.00" />
    <label for="season_winter_start_date">Start Date</label>
    <input type="date" id="season_winter_start_date" name="season_winter_start_date" value="<?=($season_winter_start_date ? $season_winter_start_date : $default_winter_start_date )?>" />
    <label for="season_winter_end_date">End Date</label>
    <input type="date" id="season_winter_end_date" name="season_winter_end_date" value="<?=($season_winter_end_date ? $season_winter_end_date : $default_winter_end_date )?>" />
    <label for="season_winter_note">Note</label>
    <textarea id="season_winter_note" name="season_winter_note"><?=$season_winter_note?></textarea>

    <h3>Spring</h3>
    <label for="season_spring_registration">Registration URL</label>
    <input type="text" name="season_spring_registration" id="season_spring_registration" value="<?= $season_spring_registration ?>" placeholder="https://" />
    <label for="season_spring_price">Price</label>
    <input type="text" name="season_spring_price" id="season_spring_price" value="<?= $season_spring_price ?>" placeholder="$0.00" />
    <label for="season_spring_start_date">Start Date</label>
    <input type="date" id="season_spring_start_date" name="season_spring_start_date" value="<?=( $season_spring_start_date ? $season_spring_start_date : $default_spring_start_date )?>" />
    <label for="season_spring_end_date">End Date</label>
    <input type="date" id="season_spring_end_date" name="season_spring_end_date" value="<?=($season_spring_end_date ? $season_spring_end_date : $default_spring_end_date )?>" />
    <label for="season_spring_note">Note</label>
    <textarea id="season_spring_note" name="season_spring_note"><?=$season_spring_note?></textarea>

    <h3>Summer</h3>
    <label for="season_summer_registration">Registration URL</label>
    <input type="text" name="season_summer_registration" id="season_summer_registration" value="<?= $season_summer_registration ?>" placeholder="https://" />
    <label for="season_summer_price">Price</label>
    <input type="text" name="season_summer_price" id="season_summer_price" value="<?= $season_summer_price ?>" placeholder="$0.00" />
    <label for="season_summer_start_date">Start Date</label>
    <input type="date" id="season_summer_start_date" name="season_summer_start_date" value="<?=( $season_summer_start_date ? $season_summer_start_date : $default_summer_start_date )?>" />
    <label for="season_summer_end_date">End Date</label>
    <input type="date" id="season_summer_end_date" name="season_summer_end_date" value="<?=($season_summer_end_date ? $season_summer_end_date : $default_summer_end_date )?>" />
    <label for="season_summer_note">Note</label>
    <textarea id="season_summer_note" name="season_summer_note"><?=$season_summer_note?></textarea>

    <h3>Fall</h3>
    <label for="season_fall_registration">Registration URL</label>
    <input type="text" name="season_fall_registration" id="season_fall_registration" value="<?= $season_fall_registration ?>" placeholder="https://" />
    <label for="season_fall_price">Price</label>
    <input type="text" name="season_fall_price" id="season_fall_price" value="<?= $season_fall_price ?>" placeholder="$0.00" />
    <label for="season_fall_start_date">Start Date</label>
    <input type="date" id="season_fall_start_date" name="season_fall_start_date" value="<?=( $season_fall_start_date ? $season_fall_start_date : $default_fall_start_date )?>" />
    <label for="season_fall_end_date">End Date</label>
    <input type="date" id="season_fall_end_date" name="season_fall_end_date" value="<?=($season_fall_end_date ? $season_fall_end_date : $default_fall_end_date )?>" />
    <label for="season_fall_note">Note</label>
    <textarea id="season_fall_note" name="season_fall_note"><?=$season_fall_note?></textarea>

</div>