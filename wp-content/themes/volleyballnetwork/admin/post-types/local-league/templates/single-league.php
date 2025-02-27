<?php 
    get_header(); the_post(); 

    $league_registration_info = get_post_meta( $post->ID, 'league_registration_info', true );
?>

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

<section class="registration">
    <div class="container">
        <div class="text-container">
            <h2>Registration Info</h2>
            <div class="registration-info">
                <?php
                    // Output the WYSIWYG content with formatting
                    echo wpautop( do_shortcode( $league_registration_info ) );
                ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>