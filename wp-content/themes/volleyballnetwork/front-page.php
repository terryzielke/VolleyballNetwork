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



<?php get_footer(); ?>