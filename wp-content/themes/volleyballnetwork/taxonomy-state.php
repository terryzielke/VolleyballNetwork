<?php get_header(); the_post(); ?>

<section class="header">
	<div class="container">
		<div class="text-container" style="margin-bottom: 0;">
		    <h1 style="margin-top:0;"><?php single_term_title(); ?></h1>
		</div>
        <div id="league-filters">
		    <div class="text-container" style="margin-bottom: 0;">
                <?php
                    // get this province name
                    $province = single_term_title('', false);
                    // array of leagues
                    $leagues = array();
                    // get all local-leagues with the state taxonomy matching the $province
                    $args = array(
                        'post_type' => 'local-league',
                        'orderby' => 'order',
                        'status' => 'publish',
                        'order' => 'ASC',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'state',
                                'field' => 'name',
                                'terms' => $province
                            )
                        )
                    );
                    $local_leagues = new WP_Query($args);
                    while ($local_leagues->have_posts()) {
                        $local_leagues->the_post();
                        // output league filter button
                        echo '<a class="league-filter" data-league-id="' . get_the_ID() . '">' . get_the_title() . '</a>';
                        // add local league id to array
                        array_push($leagues, get_the_ID());
                    }
                    // reset wp_query
                    wp_reset_query();
                ?>
                <a class="league-filter active" data-league-id="all">Show All</a>

                <?php // include(get_template_directory() . '/php/includes/program-filters.php'); ?>
            </div>
        </div>
	</div>
</section>

<section id="leagues-section">
        <?php
            foreach($leagues as $league_id) {
                $league = get_post($league_id);
                $admin_league = get_post_meta($league_id, 'local_league_league', true);
                $league_title = get_the_title($admin_league);
                ?>
                <div class="league" data-league-id="<?=$league_id?>">
                    <div class="container">
                        <div class="text-container">
                                    <h2 class="orange"><?=$league->post_title?></h2>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h3>Program</h3>
                                            <p><?= get_post_meta($admin_league, 'league_program_info', true) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Format</h3>
                                            <p><?= get_post_meta($admin_league, 'league_format_info', true) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Expectation</h3>
                                            <p><?= get_post_meta($admin_league, 'league_expectation_info', true) ?></p>
                                        </div>
                                    </div> 
                                <?php


                                get_program_list($province,'',$league_title);
                                ?>
                            </div>
                        </div>
                    </div><!-- end league -->
                <?php
            }
        ?>
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