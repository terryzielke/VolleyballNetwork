<?php get_header(); the_post(); ?>

<section class="header">
	<div class="container">
		<div class="row">
			<div class="col col-12">
				<h1><?= the_title() ?></h1>
			</div>
		</div>
	</div>
</section>

<section class="page">
	<div class="container">
		<div class="row">
			<div class="col col-12">
				<div class="content">
					<?= the_content() ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>