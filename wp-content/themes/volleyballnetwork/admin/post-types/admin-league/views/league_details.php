<?php	
	// Set post nonce
	wp_nonce_field('volleyball_network_admin_league_nonce', 'volleyball_network_admin_league_nonce');
	// Get post meta data
	$league_program_info = get_post_meta( $post->ID, 'league_program_info', true );
	$league_format_info = get_post_meta( $post->ID, 'league_format_info', true );
	$league_expectation_info = get_post_meta( $post->ID, 'league_expectation_info', true );
?>
<style>
    .frame .row{
        display: flex;
        flex-wrap: wrap;
    }
    .frame .col-md-4{
        flex: 1;
        width: 100%;
        min-width: 400px;
    }
</style>

<div class="frame">
    <div class="row">
        <div class="col-md-4">
            <!-- WYSIWYG editor for program info -->
            <div class="form-group">
                <label for="league_program_info">Program Info</label>
                <?php wp_editor( $league_program_info, 'league_program_info', array('textarea_name' => 'league_program_info') ); ?>
            </div>
        </div>
        <div class="col-md-4">
            <!-- WYSIWYG editor for format info -->
            <div class="form-group">
                <label for="league_format_info">Format Info</label>
                <?php wp_editor( $league_format_info, 'league_format_info', array('textarea_name' => 'league_format_info') ); ?>
            </div>
        </div>
        <div class="col-md-4">
            <!-- WYSIWYG editor for expectation info -->
            <div class="form-group">
                <label for="league_expectation_info">Expectation Info</label>
                <?php wp_editor( $league_expectation_info, 'league_expectation_info', array('textarea_name' => 'league_expectation_info') ); ?>
            </div>
        </div>
    </div>
</div>