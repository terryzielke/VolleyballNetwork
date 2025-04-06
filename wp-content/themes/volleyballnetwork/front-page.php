<?php get_header(); ?>


<section id="video-section">
	<div class="video-background">
		<video autoplay loop muted playsinline>
			<source src="<?=get_template_directory_uri()?>/assets/video/IMG_5707.mp4" type="video/mp4">
		</video>
	</div>
	<div class="video-overlay">
		<h1>Discover Your Potential!</h1>
	</div>
</section>

<section id="intro-section" class="page">
	<?= the_content() ?>
</section>

<?php include('php/find-program-near-you.php') ?>

<section id="available-leagues-section" class="template-section">
	<div class="container">
		<div id="league-filters" class="row cell-row">
			<?php
				$leagues_HTML = '';
				// gety all league posts
				$leagues = get_posts(array(
					'post_type' => 'league',
					'orderby' => 'order',
					'order' => 'ASC',
					'posts_per_page' => -1
				));
				$i = 0;
				foreach($leagues as $league) {
					$league_id = $league->ID;
					$league_title = $league->post_title;
					$league_image = get_the_post_thumbnail_url($league_id);
					$league_link = get_permalink($league_id);
					$league_primary_header = get_post_meta( $league_id, 'league_primary_header', true );
					$league_sub_header = get_post_meta( $league_id, 'league_sub_header', true );
					$league_excerpt = get_post_meta( $league_id, 'league_excerpt', true );
					$league_ages = get_post_meta( $league_id, 'league_ages', true );

					echo '<div class="col col-12 col-md-3">
							<a class="tab league-filter visible '.($i == 0 ? ' active' : '').'" data-league-id="'.$league_id.'">'.$league_title.'</a>
						</div>';

					$leagues_HTML .= '<div class="league" data-league-id="'.$league_id.'" '.($i != 0 ? ' style="display:none;"' : '').'>
										<div class="row">
											<div class="col col-12 col-md-6">
												<figure>
													<img src="'.$league_image.'" alt="'.$league_title.'" />
													<span class="ages">'.$league_ages.'</span>
												</figure>
											</div>
											<div class="col col-12 col-md-6">
												<h2>'.($league_primary_header ? $league_primary_header : $league_title).'</h2>
												<h3>'.$league_sub_header.'</h3>
												<p>'.$league_excerpt.'</p>
												<a href="'.$league_link.'" class="btn orange">Learn More</a>
											</div>
										</div>
									</div>';
					$i++;
				}
			?>
		</div>
		<div class="text-container">
			<div id="leagues-section">
				<?php
					echo $leagues_HTML;
				?>
			</div>
		</div>
	</div>
</section>

<?php include('php/includes/become-an-affiliate-cta.php') ?>

<?php get_footer(); ?>