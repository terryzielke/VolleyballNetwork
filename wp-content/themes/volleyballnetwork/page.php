<?php get_header(); the_post(); ?>

<section class="header">
	<div class="container">
		<div class="text-container">
			<h1><?= the_title() ?></h1>
		</div>
	</div>
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