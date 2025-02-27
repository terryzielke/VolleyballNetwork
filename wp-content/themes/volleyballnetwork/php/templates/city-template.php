<?php
/*
Template Name: City
*/
?>
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

<section class="programs-section">
    <div class="container">
        <div class="text-container">
            <h2 class="orange">Programs</h2>
            <ul class="programs-container">
                <?php
                    $page_id = get_the_ID();
                    $args = array(
                        'post_type' => 'league',
                        'posts_per_page' => -1
                    );
                    $programs = new WP_Query($args);
                    while ($programs->have_posts()) {
                        $programs->the_post();

                        $program_venue = get_post_meta( $post->ID, 'program_venue', true );
                        $venue_city_taxonomy = get_the_terms($program_venue, 'city')[0]->name;
                        if ( strtolower($venue_city_taxonomy) == strtolower(get_the_title($page_id))):
                            ?>
                            <li class="program">
                                <h2><a href="<?= get_permalink() ?>"><?= the_title() ?></a></h2>
                                <p><?= the_content() ?></p>
                                <p><a href="<?= get_permalink() ?>" class="btn blue">Get More Info</a></p>
                            </li>
                            <?php
                        endif;
                    }
                ?>
            </ul>
        </div>
    </div>
</section>

<?php get_footer(); ?>