<?php get_header(); the_post(); ?>

<section id="filters-section" class="template-section">
	<div class="container">
		<h3>Find a Program
			<a id="toggle-filters"><i class="fa-solid fa-filter"></i></a>
		</h3>
		<?php
			get_program_filters();
		?>
	</div>
</section>


<section class="programs-section template-section">
    <div class="container">
		<div id="program-container">
			<?php
				get_program_list();
			?>
		</ul>
    </div>
</section>

<?php get_footer(); ?>