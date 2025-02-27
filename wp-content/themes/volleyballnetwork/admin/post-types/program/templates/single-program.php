<?php 
	get_header(); the_post(); 

	$program_address  		= get_post_meta( $post->ID, 'program_address', true );
	$program_phone			= get_post_meta( $post->ID, 'program_phone', true );
	$program_email			= get_post_meta( $post->ID, 'program_email', true );
	$program_coming_soon	= get_post_meta( $post->ID, 'program_coming_soon', true );
?>

<section class="header">
	<div class="container">
		<div class="text-container">
			<h1><?= the_title() ?></h1>
		</div>
	</div>
</section>

<?php if( $program_coming_soon ): ?>
	<section class="coming-soon">
		<div class="container">
			<div class="text-container">
				<h4 class="orange">There are no programs in this program yet.</h4>
			</div>
		</div>
<?php endif; ?>

<section class="page">
	<div class="container">
		<div class="text-container">
			<div class="content">
				<?= the_content() ?>
			</div>
		</div>
	</div>
</section>

<section class="contact">
	<div class="container">
		<div class="text-container">
			<h2>Contact Info</h2>
			<?=
				($program_address ? '<p><strong>Address:</strong> ' . $program_address . '</p>' : '').
				($program_phone ? '<p><strong>Phone:</strong> ' . $program_phone . '</p>' : '').
				($program_email ? '<p><strong>Email:</strong> ' . $program_email . '</p>' : '');
			?>

		</div>
	</div>

<?php get_footer(); ?>