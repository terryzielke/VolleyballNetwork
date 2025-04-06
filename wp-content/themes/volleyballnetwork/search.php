<?php
	get_header();
	// Get the search term from the URL
	$search_query = get_search_query();
?>


<section class="header">
	<div class="container">
		<div class="text-container" style="margin-bottom: 0;">
			<h1>Search: <?=$search_query?></h1>
		</div>
        <div id="search-result-filters" class="header-section-filters">
		    <div class="text-container">
				<a class="result-filter" data-post-type="page">Pages</a>
				<a class="result-filter" data-post-type="post">Blog Posts</a>
                <a class="result-filter active" data-post-type="all">Show All</a>
            </div>
		</div>
	</div>
</section>

<section id="search-results-section" class="template-section">
	<div class="container">
		<div class="text-container">
			<div class="content">
				<?php
				
					$args = array(
						's' => $search_query,  // Search term
						'post_type' => array('post', 'page'),  // Post types to search
					);
					
					$query = new WP_Query($args);
					
					if ($query->have_posts()) :
						while ($query->have_posts()) : $query->the_post();
							// get the post type
							$post_type = str_replace(' ', '-', get_post_type());
							?>
							<div class="result" data-post-type="<?=$post_type?>">
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p><?php the_excerpt(); ?></p>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					else :
						echo '<p>No results found.</p>';
					endif;

				?>
			</div>
		</div>
	</div>
</section>


<?php get_footer(); ?>