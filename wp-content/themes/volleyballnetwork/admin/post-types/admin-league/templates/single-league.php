<?php
get_header();

$league_description = get_post_meta( get_the_id(), 'league_description', true );
$league_program_info = get_post_meta( get_the_id(), 'league_program_info', true );
$league_format_info = get_post_meta( get_the_id(), 'league_format_info', true );
$league_expectation_info = get_post_meta( get_the_id(), 'league_expectation_info', true );
?>

<section class="header">
	<div class="container">
		<div class="text-container" style="margin-bottom: 0;">
		    <h1 style="margin-top:0;"><?= get_the_title() ?></h1>
		</div>
	</div>
</section>

<?php if($league_program_info && $league_format_info && $league_expectation_info): ?>
<section id="league-details-section" class="template-section">
    <div class="container">
        <div class="text-container">
            <div class="row">
                <?php if($league_program_info): ?>
                <div class="col-md-4">
                    <h3>Program</h3>
                    <p><?= $league_program_info ?></p>
                </div>
                <?php endif; ?>
                <?php if($league_format_info): ?>
                <div class="col-md-4">
                    <h3>Format</h3>
                    <p><?= $league_format_info ?></p>
                </div>
                <?php endif; ?>
                <?php if($league_expectation_info): ?>
                <div class="col-md-4">
                    <h3>Expectation</h3>
                    <p><?= $league_expectation_info ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- end league -->
</section>
<?php endif; ?>

<section id="league-description-section" class="page template-section">
	<div class="container">
		<div class="text-container">
			<div class="content">
				<?= $league_description ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>