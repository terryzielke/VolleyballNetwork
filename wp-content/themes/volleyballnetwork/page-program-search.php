<?php get_header(); the_post(); ?>

<section id="filters-section">
	<div class="container">
		<h3>Find a Program</h3>
		<?php
			get_program_filters();
			
			//include('php/includes/program-filters.php');
		?>
	</div>
</section>


<section class="programs-section">
    <div class="container">
		<div id="program-container">
			<?php
				get_program_list();

				//include('php/includes/program-list.php');
			
			?>
		</ul>
    </div>
</section>

<?php get_footer(); ?>