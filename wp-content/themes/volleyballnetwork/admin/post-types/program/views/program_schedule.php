<?php	
    // Get post meta data
    $program_start_date	= get_post_meta( $post->ID, 'program_start_date', true );
    $program_end_date	= get_post_meta( $post->ID, 'program_end_date', true );
    $program_days		= get_post_meta( $post->ID, 'program_days', true );
    $program_time		= get_post_meta( $post->ID, 'program_time', true );
    $program_start_time	= get_post_meta( $post->ID, 'program_start_time', true );
    $program_end_time	= get_post_meta( $post->ID, 'program_end_time', true );
?>

<div class="frame">
    <label for="program_days">Days</label>
    <div id="program_days">
        <label><input type="checkbox" name="program_days[]" value="Sunday" <?=($program_days ? (in_array('Sunday', $program_days) ? 'checked' : '') : '') ?> /> Sunday</label>
        <label><input type="checkbox" name="program_days[]" value="Monday" <?=($program_days ? (in_array('Monday', $program_days) ? 'checked' : '') : '') ?> /> Monday</label>
        <label><input type="checkbox" name="program_days[]" value="Tuesday" <?=($program_days ? (in_array('Tuesday', $program_days) ? 'checked' : '') : '') ?> /> Tuesday</label>
        <label><input type="checkbox" name="program_days[]" value="Wednesday" <?=($program_days ? (in_array('Wednesday', $program_days) ? 'checked' : '') : '') ?> /> Wednesday</label>
        <label><input type="checkbox" name="program_days[]" value="Thursday" <?=($program_days ? (in_array('Thursday', $program_days) ? 'checked' : '') : '') ?> /> Thursday</label>
        <label><input type="checkbox" name="program_days[]" value="Friday" <?=($program_days ? (in_array('Friday', $program_days) ? 'checked' : '') : '') ?> /> Friday</label>
        <label><input type="checkbox" name="program_days[]" value="Saturday" <?=($program_days ? (in_array('Saturday', $program_days) ? 'checked' : '') : '') ?> /> Saturday</label>
    </div>
    
    <label for="program_time">Time (being removed)</label>
    <input type="text" id="program_time" name="program_time" value="<?=$program_time?>" />
    <label for="program_start_time">Start Time</label>
    <input type="time" id="program_start_time" name="program_start_time" value="<?=$program_start_time?>" />
    <label for="program_end_time">End Time</label>
    <input type="time" id="program_end_time" name="program_end_time" value="<?=$program_end_time?>" />

    <label for="program_start_date" style="color:#FFA736">Override Season Start Date</label>
    <input type="date" id="program_start_date" name="program_start_date" value="<?=$program_start_date?>" />
    <label for="program_end_date" style="color:#FFA736">Override Season End Date</label>
    <input type="date" id="program_end_date" name="program_end_date" value="<?=$program_end_date?>" />
</div>