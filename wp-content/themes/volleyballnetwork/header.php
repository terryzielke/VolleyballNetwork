<!DOCTYPE html>
<html lang="en">
<head>
	<meta charSet="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?=get_template_directory_uri()?>/assets/img/volleyball-network-icon.svg">
	<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">-->
	<script src="https://kit.fontawesome.com/dd2ef627ee.js" crossorigin="anonymous"></script>
	<!-- fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Squada+One&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> id="<?php if(is_home()){echo 'blog-body';}elseif(is_404()){ echo 'oops-body';}elseif(is_author()){echo 'author-body';}else{global $post; $pageSlug = $post->post_name; echo $pageSlug.'-body';} ?>">

<!-- content -->
<div id="page">
	<!-- header -->
	<header id="header">
		<div class="utility-row row">
			<div class="col col-6">
				<a id="utility-find-program">Find a program<span> near you</span></a>
			</div>
			<div class="col col-6">
				<a id="utility-login">Login</a>
				<a id="utility-signup">Sign Up</a>
			</div>
		</div>
		<div class="header-row row">
			<div class="col col-6 col-md-2 left">
				<a id="header-logo" href="<?=get_site_url()?>">
					<img src="<?=get_template_directory_uri()?>/assets/img/volleyball-network-logo.svg" alt="<?=get_bloginfo('name')?>">
				</a>
			</div>
			<div class="col col-8 center">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'depth'		     => '0'
					));
				?>
			</div>
			<div class="col col-6 col-md-2 right">
				<?php
					get_search_form();
				?>
				<button id="menu-button">
					<b class="bar bar1"></b>
					<b class="bar bar2"></b>
					<b class="bar bar3"></b>
				</button>
			</div>
		</div>
	</header>
	<!-- main -->
	<main id="main">