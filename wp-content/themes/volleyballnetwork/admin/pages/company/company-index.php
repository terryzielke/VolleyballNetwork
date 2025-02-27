<?php
/*
	ABORT
	
	If this file is called directly, abort.
*/
if ( ! defined( 'WPINC' ) ) { die; }


/*
	DASHBOARD PAGES
*/
function zielkedesign_company_dashboard_page(){
	add_menu_page( 
		'Company Information', // page title
		'Company Info', // menu title
		'edit_posts', // capabilities
		'company-information', // page slug
		'display_zielkedesign_company_page', // page content call
		'dashicons-admin-site-alt3',
		1
	);
}
add_action( 'admin_menu', 'zielkedesign_company_dashboard_page' );

function display_zielkedesign_company_page(){
    include('company-page.php'); 
}


/*
	SETTINGS
*/
function zilekedesign_company_information_settings() {
	// copmany name
	register_setting( 'zcomp_settings', 'company_name' );
	// slogan
	register_setting( 'zcomp_settings', 'company_slogan' );
	// logo
	register_setting( 'zcomp_settings', 'company_logo' );
	// address
	register_setting( 'zcomp_settings', 'company_address' );
	// contact info
	register_setting( 'zcomp_settings', 'company_phone' );
	register_setting( 'zcomp_settings', 'company_fax' );
	register_setting( 'zcomp_settings', 'company_email' );
	// opperating hours
	register_setting( 'zcomp_settings', 'zcomp_monday' ); 
	register_setting( 'zcomp_settings', 'zcomp_tuesday' );
	register_setting( 'zcomp_settings', 'zcomp_wednesday' );
	register_setting( 'zcomp_settings', 'zcomp_thursday' );
	register_setting( 'zcomp_settings', 'zcomp_friday' );
	register_setting( 'zcomp_settings', 'zcomp_saturday' );
	register_setting( 'zcomp_settings', 'zcomp_sunday' );
	register_setting( 'zcomp_settings', 'zcomp_holidays' );
	// brand colors
	register_setting( 'zcomp_settings', 'zcomp_color_name_one' );
	register_setting( 'zcomp_settings', 'zcomp_color_code_one' );
	register_setting( 'zcomp_settings', 'zcomp_color_name_two' );
	register_setting( 'zcomp_settings', 'zcomp_color_code_two' );
	register_setting( 'zcomp_settings', 'zcomp_color_name_three' );
	register_setting( 'zcomp_settings', 'zcomp_color_code_three' );
	register_setting( 'zcomp_settings', 'zcomp_color_name_four' );
	register_setting( 'zcomp_settings', 'zcomp_color_code_four' );
	register_setting( 'zcomp_settings', 'zcomp_color_name_five' );
	register_setting( 'zcomp_settings', 'zcomp_color_code_five' );
}
add_action( 'admin_init', 'zilekedesign_company_information_settings' );


/*
	ADMINI BAR MENU
*/
function add_xn_admin_bar() {
	
	// get colors
	$zcomp_color_name_one = esc_attr( get_option('zcomp_color_name_one'));
	$zcomp_color_code_one = esc_attr( get_option('zcomp_color_code_one'));
	$zcomp_color_name_two = esc_attr( get_option('zcomp_color_name_two'));
	$zcomp_color_code_two = esc_attr( get_option('zcomp_color_code_two'));
	$zcomp_color_name_three = esc_attr( get_option('zcomp_color_name_three'));
	$zcomp_color_code_three = esc_attr( get_option('zcomp_color_code_three'));
	$zcomp_color_name_four = esc_attr( get_option('zcomp_color_name_four'));
	$zcomp_color_code_four = esc_attr( get_option('zcomp_color_code_four'));
	$zcomp_color_name_five = esc_attr( get_option('zcomp_color_name_five'));
	$zcomp_color_code_five = esc_attr( get_option('zcomp_color_code_five'));

	global $wp_admin_bar;
	
	$wp_admin_bar->add_menu( array(
		'id' => 'zcomp_dropdown',
		'title' => __( 'Quick Colors')
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'zcomp_dropdown',
		'id' => 'zcomp_one',
		'title' => $zcomp_color_name_one,
		'href' => $zcomp_color_code_one,
	));
	$wp_admin_bar->add_menu( array(
		'parent' => 'zcomp_dropdown',
		'id' => 'zcomp_two',
		'title' => $zcomp_color_name_two,
		'href' => $zcomp_color_code_two,
	));
	$wp_admin_bar->add_menu( array(
		'parent' => 'zcomp_dropdown',
		'id' => 'zcomp_three',
		'title' => $zcomp_color_name_three,
		'href' => $zcomp_color_code_three,
	));
	$wp_admin_bar->add_menu( array(
		'parent' => 'zcomp_dropdown',
		'id' => 'zcomp_four',
		'title' => $zcomp_color_name_four,
		'href' => $zcomp_color_code_four,
	));
	$wp_admin_bar->add_menu( array(
		'parent' => 'zcomp_dropdown',
		'id' => 'zcomp_five',
		'title' => $zcomp_color_name_five,
		'href' => $zcomp_color_code_five,
	));

}
add_action('admin_bar_menu', 'add_xn_admin_bar',100);


/*
	ENGUEUE SCRIPTS
*/
function zcomp_frontend_scripts() {
	// CSS
	//wp_enqueue_style( 'zcomp_styles',	plugin_dir_url( __FILE__ ) . 'css/zcomp_admin.css');
	// JS
	//wp_enqueue_script( 'zcomp_scripts', plugin_dir_url( __FILE__ ) . 'js/zcomp_scripts.js', array('jquery'), 'all' );
}
add_action( 'wp_enqueue_scripts', 'zcomp_frontend_scripts' );

function zcomp_backend_scripts() {
	// CSS
	//wp_enqueue_style( 'zcomp_admin_css',	plugin_dir_url( __FILE__ ) . 'css/zcomp_admin.css');
	// JS
	//wp_enqueue_script( 'zcomp_admin_js', plugin_dir_url( __FILE__ ) . 'js/zcomp_admin.js', array('jquery', 'wp-color-picker' ), 'all' );
}
add_action( 'admin_enqueue_scripts', 'zcomp_backend_scripts' );