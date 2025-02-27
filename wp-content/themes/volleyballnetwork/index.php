<?php
	get_header();
	$latest_post = get_posts(['numberposts' => 1 ]);
	$latest_posts = get_posts(['numberposts' => 12 , 'post__not_in' => [$latest_post[0]->ID]]);
?>

<section id="blog">
	<div class="frame">
		<h1>Blog</h1>
		<div class="columns">
			<?php if($latest_post) { setup_postdata($latest_post); ?>
			<div class="column">
				<div class="post alt-large">
					<div class="image" style="background-image:url(<?=get_the_post_thumbnail_url(null, 'full')?>)"></div>
					<div class="content">
						<h3 class="title"><?=get_the_title()?></h3>
						<div class="excerpt"><?=get_the_excerpt()?></div>
					</div>
					<a class="link" href="<?=get_the_permalink()?>"></a>
				</div>
			</div>
			<?php } ?>
			<?php if($latest_posts) { ?>
			<div class="column">
				<?php foreach(array_slice($latest_posts,0,3) as $post) { setup_postdata($post);?>
				<div class="post">
					<div class="image" style="background-image: url(<?=get_the_post_thumbnail_url(null, 'full')?>)"></div>
					<div class="content">
						<h4 class="title"><?=get_the_title()?></h4>
					</div>
					<a class="link" href="<?=get_the_permalink()?>"></a>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>

<section class="BLOG-PREVIEW">
	<?php foreach(array_slice($latest_posts,3,12) as $post) { setup_postdata($post);?>	
	<div class="frame">
        <div class="columns">
            <div class="column">
                <div class="post">
                    <div class="image" style="background-image: url(<?=get_the_post_thumbnail_url(null, 'full')?>)"></div>
                    <div class="content">
                        <h5 class="title"><?=get_the_title()?></h5>
                        <p><?= get_the_excerpt() ?></p>
					</div>
					<p class="read-more">
						Read More
					</p>
                    <a class="link" href="<?=get_the_permalink()?>"></a>
                </div>
            </div>
        </div>
	</div>
	<?php } ?>
</section>

<?php get_footer(); ?>