<?php

// SETUP
add_action('after_setup_theme', function(){
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	]);
	
	register_nav_menus([
        'primary' => 'Primary',
        'footer' => 'Footer',
    ]);
});



/*
    ENQUEUE STYLES & SCRIPTS
*/
add_action('wp_enqueue_scripts', function(){
	// CSS
	wp_dequeue_style('wp-block-library');
    $css_file = get_template_directory() . '/css/theme.min.css';
    $version = file_exists($css_file) ? filemtime($css_file) : '1.0.0'; // fallback version
    wp_enqueue_style('theme.min', get_stylesheet_directory_uri() . '/css/theme.min.css', array(), $version);
	// JS 
    wp_enqueue_script( 'theme-ts-script', get_template_directory_uri() . '/js/main.js', array(), filemtime(get_template_directory() . '/js/main.js'), true );
	/*
	// INC scripts
	wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/inc/slick/slick.css');
	wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/inc/slick/slick.min.js', array(), '20151215', true );
	wp_enqueue_script( 'jquery-visible', get_template_directory_uri() . '/inc/visible/jquery.visible.min.js');
	wp_enqueue_script( 'nearby', get_template_directory_uri() . '/inc/nearby/nearby.js');
    */
});
// ADMIN STYLES & SCRIPTS
add_action('admin_enqueue_scripts', function(){
	// css
	wp_enqueue_style( 'wp-color-picker' ); 
    wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
	wp_enqueue_style( 'admin_styles', get_template_directory_uri() . '/css/admin.css' );
    if (!current_user_can('administrator')){
        wp_enqueue_style( 'admin_admin_styles', get_template_directory_uri() . '/css/admin.admin.css' );
    }
	// js
    wp_enqueue_script('jquery');
 	wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script('ajax-script', get_template_directory_uri() . '/php/functions/ajax-scripts.js', array('jquery'), null, true);
    //wp_localize_script('custom-admin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
});


/*
  CUSTOM LOGIN PAGE
*/
add_action( 'login_enqueue_scripts', function(){
	wp_enqueue_style( 'login_page_styles', get_template_directory_uri() . '/css/login.css' );
});


/*
	INCLUDE ADMIN PAGES, PLUGINS, AND TAXONOMIES
*/
require get_template_directory() . '/admin/post-types/admin-league/index.php';
require get_template_directory() . '/admin/post-types/local-league/index.php';
require get_template_directory() . '/admin/post-types/venue/index.php';
require get_template_directory() . '/admin/post-types/program/index.php';
require get_template_directory() . '/admin/taxonomies/taxonomies.php';


/*
	POST TEMPLATES
*/
function volleyball_post_type_templates($postType){
	global $post;
	
	if($post->post_type == 'program'){
  		$postType = dirname( __FILE__ ) . '/admin/post-types/program/templates/single-program.php';
	}
    if($post->post_type == 'venue'){
  		$postType = dirname( __FILE__ ) . '/admin/post-types/venue/templates/single-venue.php';
    }
    if($post->post_type == 'league'){
        $postType = dirname( __FILE__ ) . '/admin/post-types/league/templates/single-league.php';
    }
        
	return $postType;
}
add_filter( 'single_template', 'volleyball_post_type_templates' );


/*
    PAGE TEMPLATES
*/
add_filter('theme_page_templates', function($templates) {
    $templates['php/templates/city-template.php'] = 'City';
    $templates['php/templates/contact-template.php'] = 'Contact';
    return $templates;
});

add_filter('template_include', function($template) {
    if (get_page_template_slug() === 'php/templates/default-template.php') {
        return get_theme_file_path('/php/templates/default-template.php');
    }
    return $template;
});


/*
    INCLUDE SHORTCODES
*/
require get_template_directory() . '/php/shortcodes/city-contact-info-list.php';



/*
	HEAD CLEANUP
*/
add_filter('emoji_svg_url', '__return_false');
add_action('after_setup_theme', function(){
	remove_action('rest_api_init', 'wp_oembed_register_route');
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head'); 
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'index_rel_link'); 
	remove_action('wp_head', 'parent_post_rel_link', 10);
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'start_post_rel_link', 10); 
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_oembed_add_discovery_links'); 
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_print_styles', 'print_emoji_styles');
});

function custom_admin_bar_modifications() {
    if (!current_user_can('administrator')) {
        global $wp_admin_bar;

        // Remove all admin bar nodes except 'site-name'
        $nodes = $wp_admin_bar->get_nodes();
        foreach ($nodes as $node) {
            if ($node->id !== 'site-name') {
                $wp_admin_bar->remove_node($node->id);
            }
        }

        // Add a "Log Out" link
        $wp_admin_bar->add_node(array(
            'id'    => 'logout-link',
            'title' => 'Log Out',
            'href'  => wp_logout_url(),
        ));
    }
}
add_action('wp_before_admin_bar_render', 'custom_admin_bar_modifications');

/*
    ADMIN CLEANUP
*/
function custom_dashboard_widgets() {
    if (!current_user_can('administrator')) {
        global $wp_meta_boxes;

        $dashboard_widgets = isset($wp_meta_boxes['dashboard']['normal']['core']) ? $wp_meta_boxes['dashboard']['normal']['core'] : array();
        $dashboard_widgets = array_merge($dashboard_widgets, isset($wp_meta_boxes['dashboard']['side']['core']) ? $wp_meta_boxes['dashboard']['side']['core'] : array());
        $dashboard_widgets = array_merge($dashboard_widgets, isset($wp_meta_boxes['dashboard']['normal']['high']) ? $wp_meta_boxes['dashboard']['normal']['high'] : array());
        $dashboard_widgets = array_merge($dashboard_widgets, isset($wp_meta_boxes['dashboard']['side']['high']) ? $wp_meta_boxes['dashboard']['side']['high'] : array());
        $dashboard_widgets = array_merge($dashboard_widgets, isset($wp_meta_boxes['dashboard']['normal']['default']) ? $wp_meta_boxes['dashboard']['normal']['default'] : array());
        $dashboard_widgets = array_merge($dashboard_widgets, isset($wp_meta_boxes['dashboard']['side']['default']) ? $wp_meta_boxes['dashboard']['side']['default'] : array());

        foreach ($dashboard_widgets as $widget_id => $widget) {
            if ($widget_id !== 'dashboard_right_now') {
                remove_meta_box($widget_id, 'dashboard', 'normal');
                remove_meta_box($widget_id, 'dashboard', 'side');
                remove_meta_box($widget_id, 'dashboard', 'high');
                remove_meta_box($widget_id, 'dashboard', 'default');
            }
        }
    }
}
add_action('wp_dashboard_setup', 'custom_dashboard_widgets');



/*
	ALLOW SVGS
*/
add_filter('upload_mimes', function($mimes){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
});



/*
	REMOVE ADMIN BAR
*/
if ( ! current_user_can( 'editor' ) && ! current_user_can( 'administrator' ) ) {
    show_admin_bar( false );
}
else{
    show_admin_bar( true );
}



/*
	REDIRECT FROM LOGOUT SCREEN TO HOME
*/
add_action('wp_logout',function(){
  wp_safe_redirect( home_url() );
  exit;
});



// SET PUBLIC STATUS
// update_option('blog_public', strpos(site_url(), '.zieke.design') ? '0' : '1');



// CUSTOM EXCERPT
// add_filter('excerpt_length', function($length){ return 20; });
// add_filter('excerpt_more', function($excerpt){ return '...'; });



/*
  Adds an additional function to retrieve formated page content without the H1.
*/
function get_the_content_with_formatting ($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}


/*
	REMOVE ADMIN MENUS
*/
add_action('admin_menu', function(){
	remove_menu_page('edit-comments.php');
    if (!current_user_can('administrator')) {
        remove_menu_page('edit.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('tools.php');
        remove_menu_page('upload.php');
    }
});
add_action('wp_before_admin_bar_render', function(){
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
});



/*
  CHECK FOR BLOG PAGES
*/
function is_blog () {
    return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
}



/*
  OVERRIDE THE EMAIL FROM VALUE
*/
function mail_name( $email ){
	return 'Alberta Craft Alliance';
}
add_filter( 'wp_mail_from_name', 'mail_name' );



/*
	GET BROWSER AND OS INFO
*/
$user_agent = $_SERVER['HTTP_USER_AGENT'];

function getOS() { 

    global $user_agent;

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
                          '/windows nt 10/i'      =>  'microsoftwindows',
                          '/windows nt 6.3/i'     =>  'microsoftwindows',
                          '/windows nt 6.2/i'     =>  'microsoftwindows',
                          '/windows nt 6.1/i'     =>  'microsoftwindows',
                          '/windows nt 6.0/i'     =>  'microsoftwindows',
                          '/windows nt 5.2/i'     =>  'microsoftwindows',
                          '/windows nt 5.1/i'     =>  'microsoftwindows',
                          '/windows xp/i'         =>  'microsoftwindows',
                          '/windows nt 5.0/i'     =>  'microsoftwindows',
                          '/windows me/i'         =>  'microsoftwindows',
                          '/win98/i'              =>  'microsoftwindows',
                          '/win95/i'              =>  'microsoftwindows',
                          '/win16/i'              =>  'microsoftwindows',
                          '/macintosh|mac os x/i' =>  'appleosx',
                          '/mac_powerpc/i'        =>  'appleosx',
                          '/linux/i'              =>  'linux',
                          '/ubuntu/i'             =>  'ubuntu',
                          '/iphone/i'             =>  'appleios',
                          '/ipod/i'               =>  'appleios',
                          '/ipad/i'               =>  'appleios',
                          '/android/i'            =>  'android',
                          '/blackberry/i'         =>  'blackberry',
                          '/webos/i'              =>  'mobilephone'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {

    global $user_agent;

    $browser        = "Unknown Browser";

    $browser_array = array(
                            '/msie/i'      => 'internetexplorer',
                            '/firefox/i'   => 'firefox',
                            '/safari/i'    => 'safari',
                            '/chrome/i'    => 'chrome',
                            '/edge/i'      => 'edge',
                            '/opera/i'     => 'opera',
                            '/netscape/i'  => 'netscape',
                            '/maxthon/i'   => 'maxthon',
                            '/konqueror/i' => 'konqueror',
                            '/mobile/i'    => 'handheldbrowser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}



/*
	FORMINATOR HOOKS
*/
add_filter('forminator_custom_form_submit_errors', function ($submit_errors, $form_id, $field_data_array) {
    foreach ($field_data_array as $field) {
        $value = $field['value'];
        
        // Check if ".ru" is present in any field
        if (strpos($value, '.ru') !== false) {
            $submit_errors[] = __('Submission blocked: ".ru" is not allowed.', 'albertacraftalliance');
            break;
        }
        
        // Check if the field is an email and contains only English characters
        if (!preg_match('/^[\x20-\x7F]+$/', $value)) {
            $submit_errors[] = __('Submission blocked: Please use only English characters in the email.', 'albertacraftalliance');
            break;
        }
    }
    return $submit_errors;
}, 10, 3);


/*
    CONVERT TAXONOMY CHECKBOXES TO RADIO BUTTONS
*/
function admin_footer_javascript_scripts() {

    if ( is_admin() && ! current_user_can('administrator') ) {
        $allowed_taxonomies = array( 'state', 'country', 'city' );
        $user_id = get_current_user_id();

        echo '<script type="text/javascript">';
        foreach ( $allowed_taxonomies as $taxonomy ) {
            $user_terms = get_user_meta( $user_id, 'user_' . $taxonomy, true );
            if ( is_array( $user_terms ) ) {
                echo "localStorage.setItem('user_" . $taxonomy . "', JSON.stringify(" . json_encode( $user_terms ) . "));\n";
            } else {
                echo "localStorage.removeItem('user_" . $taxonomy . "');\n";
            }
        }
        echo '</script>';
    }

    echo '<script>
            jQuery(document).ready(function($) {
                $("#taxonomy-city input[type=checkbox]").each(function() {
                    $(this).attr("type", "radio");
                });
                $("#taxonomy-state input[type=checkbox]").each(function() {
                    $(this).attr("type", "radio");
                });
                $("#taxonomy-country input[type=checkbox]").each(function() {
                    $(this).attr("type", "radio");
                });
            });


            jQuery(document).ready(function($) {
                var allowedTaxonomies = ["state", "country", "city"];

                allowedTaxonomies.forEach(function(taxonomy) {
                
                    var userTerms = JSON.parse(localStorage.getItem("user_" + taxonomy)); // Retrieve user terms from localStorage

                    if (userTerms) {
                        $("#" + taxonomy + "checklist input[type=radio]").each(function() {
                            var termId = parseInt($(this).val());
                            if (!userTerms.includes(termId)) {
                                $(this).closest("li").remove();
                            }
                        });
                    }
                });
            });
        </script>';
}
add_action('admin_footer', 'admin_footer_javascript_scripts');


/*
    PHP FUNCTIONS
*/
require get_template_directory() . '/php/functions/location-functions.php';
require get_template_directory() . '/php/functions/restrict-user-access.php';
require get_template_directory() . '/php/functions/ajax-functions.php';