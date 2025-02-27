<?php
/*
Template Name: Contact
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

<section class="contact-info-section">
    <div class="container">
        <div class="text-container">
            <div class="row cell-row">
                <div class="col col-12 col-md-6">
                    <div class="cell">
                    <h2>Winnipeg</h2>
                    <p>
                        <strong>Address:</strong><br>
                        3 Myles Robinson Way<br>
                        Winnipeg, Manitoba R3X 1M6
                    </p>
                    <p><strong>Phone:</strong> 204-471-1111</p>
                    <p><strong>Office Hours:</strong> 9:00am – 4:00pm daily.</p>
                    </div>
                </div>
                <div class="col col-12 col-md-6">
                <div class="cell">
                    <h2>Calgary</h2>
                    <p>
                        <strong>Address:</strong><br>
                        One Executive Place<br>
                        1816 Crowchild Trail NW #700<br>
                        Calgary, Alberta   T2M 3Y7
                    </p>
                    <p><strong>Phone:</strong> 403-510-1784</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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