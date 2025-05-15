<?php get_header(); ?>

<section class="header" style="background-image: url(<?= get_the_post_thumbnail_url(null, 'full') ?>);">
	<span class="overlay"></span>
	<div class="container">
		<div class="text-container" style="max-width: 9999px;">
			<h1><?=get_the_archive_title()?></h1>
		</div>
	</div>
</section>

<section class="template-section archive-section">
    <div class="container">
        <div class="posts">
            <?php
                $post_count = wp_count_posts('post')->publish;
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();

                        $ID = get_the_ID();
                        $title = get_the_title();
                        $date = get_the_date();
                        $image = get_the_post_thumbnail_url();
                        $link = get_permalink();
                        $excerpt = wp_trim_words(get_the_content(), 40, '...');
                                $excerpt = preg_replace( '/\[[^\]]+\]/', '', $excerpt );

                        echo '<div class="post">
                                <a href="'.$link.'">
                                    <figure>
                                        <img src="'.$image.'" alt="'.$title.'"></img>
                                    </figure>
                                    <h3>'.$title.'</h3>
                                    <h4>'.$date.'</h4>
                                    <p>'.$excerpt.'</p>
                                </a>
                            </div>';
                    }

                } else {
                    echo '<p>No posts found.</p>';
                }
            ?>
            <?php if ($post_count > 6): ?>
                <div class="pageination">
                    <?php
                        // Pagination
                        echo paginate_links([
                            'total'   => $wp_query->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                        ]);
                    ?>
                </div>
            <?php endif; ?>
        </div>
		<div class="sidebar">
			<?php
				$categories = get_terms(array(
					'taxonomy'   => 'category',
					'hide_empty' => false,
				));
				if (!empty($categories) && !is_wp_error($categories)) {
					echo '<h4>Categories</h4><ul>';
					foreach ($categories as $category) {
						$category_link = get_term_link($category);
						echo '<li><a href="' . esc_url($category_link) . '">' . esc_html($category->name) . '</a></li>';
					}
					echo '</ul>';
				}
				$query = new WP_Query(array(
					'post_type'      => 'post',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'posts_per_page' => 5,
				));
				if ($query->have_posts()) {
					echo '<h4>Recent Posts</h4><ul>';
					while ($query->have_posts()) {
						$query->the_post();
						echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
					}
					echo '</ul>';
					wp_reset_postdata(); // Reset post data
				} else {
					echo '<p>No case studies found.</p>';
				}
			?>
		</div>
    </div>
</section>



<?php get_footer(); ?>