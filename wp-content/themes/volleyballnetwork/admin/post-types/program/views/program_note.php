<?php	
    // Get post meta data
    $program_note	= get_post_meta( $post->ID, 'program_note', true );
?>

<div class="frame">
    <textarea id="program_note" name="program_note"><?=$program_note?></textarea>
</div>