	</main><!--#main-->

	<footer id="footer">
		<div class="footer-content row">
			<div class="footer-logo col col-12 col-md-2">
				<a id="footer-logo" href="<?=get_site_url()?>">
					<img src="<?=get_template_directory_uri()?>/assets/img/volleyball-network-logo-white.svg" alt="<?=get_bloginfo('name')?>">
				</a>
			</div>
			<div class="footer-menu col col-12 col-md-10">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'footer',
						'menu_id'        => 'footer-menu',
						'depth'		     => '3'
					));
				?>
			</div>
		</div>
		<div class="legal">
			<div class="row">
				<div id="copywrite" class="col col-12 col-md-6">
					&copy; Copyright <?php echo date('Y').' '.get_bloginfo('name'); ?><b>|</b><a href="/privacy-policy">Privacy Policy</a><b>|</b><a href="/terms-of-service">Terms of Service</a>
				</div>
				<div id="webdeveloper" class="col col-12 col-md-6">
					<?php include('php/zielkedesign/zielkedesign-footer-icon.php'); ?>
				</div>
			</div>
		</div>
	</footer>

</div><!--#page-->

<?php wp_footer(); ?>