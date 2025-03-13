<?php get_header(); the_post(); ?>

<section class="header">
	<div class="container">
		<div class="text-container" style="margin-bottom: 0;">
		    <h1 style="margin-top:0;"><?= get_the_title() ?></h1>
		</div>
	</div>
</section>

<section id="league-details">
    <div class="container">
        <div class="text-container">
            <div class="row">
                <div class="col-md-4">
                    <h3>Program</h3>
                    <p><?= get_post_meta(get_the_id(), 'league_program_info', true) ?></p>
                </div>
                <div class="col-md-4">
                    <h3>Format</h3>
                    <p><?= get_post_meta(get_the_id(), 'league_format_info', true) ?></p>
                </div>
                <div class="col-md-4">
                    <h3>Expectation</h3>
                    <p><?= get_post_meta(get_the_id(), 'league_expectation_info', true) ?></p>
                </div>
            </div>
        </div>
    </div><!-- end league -->
</section>

<section class="page">
	<div class="container">
		<div class="text-container">
			<div class="content">
				<?= the_content() ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>