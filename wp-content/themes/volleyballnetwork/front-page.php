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

<section id="intro-section">
	<center class="container">
		<div class="text-container">
			<H1 class="blue">Welcome to The Volleyball Network</H1>
			<p>Join a movement that's changing the game! The Volleyball Network is a nationwide community built for athletes, coaches, and organizations who are passionate about growing the sport beyond the traditional club system.</p>
		</div>
		<div class="cell-row row">
			<div class="col col-12 col-md-4">
				<div class="cell visible">
					<h3 class="blue">Athletes</h3>
					<p>Whether you're just starting out or looking to take your skills to the next level, we provide high-quality training, development opportunities, and exclusive resources to help you improve and compete with confidence.</p>
				</div>
			</div>
			<div class="col col-12 col-md-4">
				<div class="cell visible">
					<h3 class="blue">Coaches</h3>
					<p>Access cutting-edge training materials, coaching courses, and a support network of like-minded professionals dedicated to raising the standard of grassroots volleyball.</p>
				</div>
			</div>
			<div class="col col-12 col-md-4">
				<div class="cell visible">
					<h3 class="blue">Organization</h3>
					<p>Looking to expand your program's reach? The Volleyball Network connects you with proven strategies, mentorship, and a thriving community to help you grow and succeed.</p>
				</div>
			</div>
		</div>
		<div class="text-container">
			<p>At the Volleyball Network, we believe in accessibility, opportunity, and a shared passion for the game. Whether you're here to train, teach, or build—you're part of something bigger.</p>
			<br>
			<h3 class="blue">Explore. Connect. Elevate your game.</h3>
			<a href="/contact" class="btn orange">Join the Volleyball Network today!</a>
		</div>
	</center>
</section>

<?php include('php/find-program-near-you.php') ?>

<section id="available-leagues-section">
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
			<div id="league-container">
				<?php
					echo $leagues_HTML;
				?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>