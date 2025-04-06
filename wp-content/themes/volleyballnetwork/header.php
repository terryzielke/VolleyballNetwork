<!DOCTYPE html>
<html lang="en">
<head>
	<meta charSet="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?=get_template_directory_uri()?>/assets/img/volleyball-network-icon.svg">
	<script src="https://kit.fontawesome.com/dd2ef627ee.js" crossorigin="anonymous"></script>
	<?php wp_head(); ?>
	<?php
		$postBody = '';
		if(is_home()){
			$postBody = 'blog-body';
		}
		elseif(is_404()){ 
			$postBody = 'oops-body';
		}
		elseif(is_author()){
			$postBody = 'author-body';
		}
		elseif(is_search()){
			$postBody = 'search-body';
		}
		else{
			global $post;
			if($post){
				$pageSlug = $post->post_name;
				$postBody = $pageSlug.'-body';
			}
		}
	?>
</head>
<body <?php body_class(); ?> id="<?=$postBody?>">

<!-- content -->
<div id="page">
	<!-- header -->
	<header id="header">
		<div class="utility-row row">
			<div class="col col-6">
				<?php
					get_search_form();
				?>
			</div>
			<div class="col col-6">
				<a id="utility-login" href="https://www.volleyballcalgary.ca/login?user_return_to=https%3A%2F%2Flogin.sportngin.com%2Fcheck_login%3Fnext_url%3Dhttps%3A%2F%2Fwww.volleyballcalgary.ca%2F" target="_blank">Login</a>
				<a id="utility-signup" href="https://user.sportngin.com/users/sign_up" target="_blank">Sign Up</a>
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
				<a id="program-search" href="/program-search/?province=<?=getUserProvince()?>"><span>Find a program</span></a>
				<button id="menu-button">
					<b class="bar bar1"></b>
					<b class="bar bar2"></b>
					<b class="bar bar3"></b>
				</button>
			</div>
		</div>
	</header>
	<!-- back to top -->
	<a id="back-to-top" href="#header">
		<i class="fas fa-caret-up"></i>
	</a>
	<!-- main -->
	<main id="main">