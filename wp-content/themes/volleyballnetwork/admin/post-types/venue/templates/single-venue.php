<?php 
	get_header(); the_post(); 

	$venue_city  			= get_post_meta( $post->ID, 'venue_city', true );
	$venue_address  		= get_post_meta( $post->ID, 'venue_address', true );
	$venue_phone			= get_post_meta( $post->ID, 'venue_phone', true );
	$venue_email			= get_post_meta( $post->ID, 'venue_email', true );
?>

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

<section class="contact">
	<div class="container">
		<div class="text-container">
			<h2>Contact Info</h2>
			<?=
				($venue_city ? '<p><strong>City:</strong> <a href="'.get_permalink($venue_city).'">' . get_the_title($venue_city) . '</a></p>' : '').
				($venue_address ? '<p><strong>Address:</strong> ' . $venue_address . '</p>' : '').
				($venue_phone ? '<p><strong>Phone:</strong> ' . $venue_phone . '</p>' : '').
				($venue_email ? '<p><strong>Email:</strong> ' . $venue_email . '</p>' : '');
			?>

		</div>
	</div>

<?php get_footer(); ?>