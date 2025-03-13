<?php
/**
 * Initialize the main plugin file.
 *
 * @category Class
 * @package  Wp_Cal_Embed_Lite
 * @author   Vinny Alves
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://usestrict.net
 */

//phpcs:disable WordPress.Files.FileName

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

/**
 * Plugin Name:     UseStrict's Calendly Embedder
 * Plugin URI:      https://usestrict.net/cal-embedder-pro
 * Description:     Simple embedding for Calendly (and soon various other calendar services).
 * Author:          Vinny Alves
 * Author URI:      https://usestrict.consulting
 * Text Domain:     cal-embedder-lite
 * Domain Path:     /language
 * Version:         1.1.7
 *
 * @package         Wp_Cal_Embed_Lite
 */

if ( ! class_exists( 'Wp_Cal_Embed_Lite' ) ) :

	/**
	 * This is the main plugin class.
	 *
	 * @package Wp_Cal_Embed_Lite
	 */
	class Wp_Cal_Embed_Lite {

		/**
		 * VERSION constant
		 *
		 * @var string
		 */
		const VERSION = '1.1.6.2';

		/**
		 * Holds our environment variables.
		 *
		 * @var array
		 */
		private $environment;


		/**
		 * Calendly API URL v1
		 *
		 * @var string
		 */
		private $calendly_api_url_v1 = 'https://calendly.com/api/v1';

		/**
		 * Calendly API URL v2
		 *
		 * @var string
		 */
		private $calendly_api_url_v2 = 'https://api.calendly.com/';

		/**
		 * The key of the transient which stores Calendly data
		 *
		 * @var string
		 */
		private static $transient = 'wpcalel-user-data';

		/**
		 * Our settings name.
		 *
		 * @var string
		 */
		private $settings_name = __CLASS__;

		/**
		 * Holds the plugin settings
		 *
		 * @var object
		 */
		public $settings;



		/**
		 * Dummy constructor.
		 */
		private function __construct() {
			/* do nothing */ }



		/**
		 * Singleton.
		 *
		 * @return Wp_Cal_Embed_Lite
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new self();
				$instance->setup_environment();
				$instance->get_settings();
				$instance->add_actions();
			}

			return $instance;
		}


		/**
		 * Sets up our environment.
		 */
		private function setup_environment() {
			$this->environment = (object) array(
				'js_url'          => plugins_url( 'assets/js', __FILE__ ),
				'css_url'         => plugins_url( 'assets/css', __FILE__ ),
				'plugin_file'     => plugin_dir_path( __FILE__ ),
				'plugin_basename' => plugin_basename( __FILE__ ),
			);
		}

		/**
		 * Gets the settings from the database.
		 */
		private function get_settings() {
			if ( is_admin() ) {
				$this->settings = (object) get_option( __CLASS__, array() );
			}
		}

		/**
		 * Add actions.
		 */
		private function add_actions() {
			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_action( 'init', array( $this, 'add_shortcode' ) );
			add_action( 'wp_ajax_wpcalel-userinfo', array( $this, 'ajax_get_user_info' ) );

			// Register menu if Pro is not installed.
			if ( false === has_filter( 'wpcalep-handler' ) ) {
				add_action( 'admin_menu', array( $this, 'add_options_page' ) );
				add_action( 'admin_init', array( $this, 'register_setting' ) );
			}
		}


		/**
		 * Load i18n.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'cal-embedder-lite', false, dirname( untrailingslashit( plugin_basename( __FILE__ ) ) ) . '/language' );
		}


		/**
		 * Adds the shortcode support.
		 */
		public function add_shortcode() {
			add_shortcode( 'wpcalel', array( $this, 'wpcalel_shortcode' ) );
		}


		/**
		 * Shortcode handler.
		 *
		 * @param array             $atts                  The shortcode attributes.
		 * @param mixed NULL|string $content   The shortcode content.
		 * @return string
		 */
		public function wpcalel_shortcode( $atts = array(), $content = null ) {
			$a = shortcode_atts(
				array(
					'type'   => 'calendly',
					'widget' => 'inline', // inline, popup, or link.
					'url'    => 'url',
				),
				$atts,
				'cal-embedder-lite'
			);

			if ( empty( $a['url'] ) ) {
				return __( 'You MUST provide a URL for the Calendly shortcode', 'cal-embedder-lite' );
			}

			$type   = $a['type'];
			$widget = $a['widget'];

			// Shortcircuit if we have pro handlers.
			if ( false !== has_filter( 'wpcalep_handler' ) ) {
				return apply_filters( 'wpcalep_handler', $atts, $content ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
			}

			if ( ! method_exists( $this, "{$type}_{$widget}_shortcode" ) ) {
										/* translators: 1: the type of shortcode 2: the widget type */
				return sprintf( esc_html__( 'I do not know how to handle these attributes. type: "%1$s", widget: "%2$s"', 'cal-embedder-lite' ), $type, $widget );
			}

			static $registered_scripts = null;
			if ( null === $registered_scripts ) {
				wp_enqueue_style( 'wpcalel-calendly', 'https://calendly.com/assets/external/widget.css', array(), self::VERSION );
				wp_enqueue_script( 'wpcalel-calendly', 'https://assets.calendly.com/assets/external/widget.js', array(), self::VERSION, $in_footer = true );
				wp_enqueue_script( 'wpcalel-calendly-handler', $this->environment->js_url . '/calendly.js', array( 'jquery', 'wpcalel-calendly' ), self::VERSION, $in_footer = true );
				wp_localize_script(
					'wpcalel-calendly-handler',
					'wpcalel',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'wpcalel-calendly' ),
					)
				);

				$registered_scripts = true;
			}

			return call_user_func( array( $this, "{$type}_{$widget}_shortcode" ), $atts, $content );
		}


		/**
		 * Calendly link widget handler.
		 *
		 * @param array             $atts      The shortcode attributes.
		 * @param mixed NULL|string $content   The shortcode content.
		 * @return string
		 */
		private function calendly_link_shortcode( $atts, $content = null ) {
			static $count = 0;
			$count++;

			$a = shortcode_atts(
				array(
					'url'                       => '',

					// Link Widget settings.
					'text'                      => __( 'Schedule time with me', 'cal-embedder-lite' ),

					// Booking page settings.
					'classes'                   => '',
					'styles'                    => '',
					'prefill'                   => false,
					'query_str'                 => false,
					'a1'                        => '',
					'a2'                        => '',
					'a3'                        => '',
					'a4'                        => '',
					'a5'                        => '',
					'a6'                        => '',
					'a7'                        => '',
					'a8'                        => '',
					'a9'                        => '',
					'a10'                       => '',
					'hide_gdpr_banner'          => false,
					'hide_landing_page_details' => false,
					'hide_event_type_details'   => false,
					'background_color'          => '',
					'text_color'                => '',
					'primary_color'             => '', // Button and link colors.
				),
				$atts,
				'cal-embedder-lite'
			);

			$a['prefill']                   = filter_var( $a['prefill'], FILTER_VALIDATE_BOOLEAN );
			$a['query_str']                 = filter_var( $a['query_str'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_gdpr_banner']          = filter_var( $a['hide_gdpr_banner'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_landing_page_details'] = filter_var( $a['hide_landing_page_details'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_event_type_details']   = filter_var( $a['hide_event_type_details'], FILTER_VALIDATE_BOOLEAN );

			// Sanitize/normalize the URL.
			$parsed   = wp_parse_url( $a['url'] );
			$a['url'] = 'https://calendly.com/' . preg_replace( '@^/+@', '', trailingslashit( $parsed['path'] ) );

			$colors = array();
			if ( $a['background_color'] ) {
				$colors['background_color'] = $a['background_color'];
			}
			if ( $a['text_color'] ) {
				$colors['text_color'] = $a['text_color'];
			}
			if ( $a['primary_color'] ) {
				$colors['primary_color'] = $a['primary_color'];
			}

			if ( ! empty( $colors ) ) {
				foreach ( $colors as &$color ) {
					$color = str_replace( '#', '', $color ); // Strip out any # characters.
				}

				$a['url'] = add_query_arg( $colors, $a['url'] );
			}

			$hide_fields = array(
				'hide_gdpr_banner'          => $a['hide_gdpr_banner'],
				'hide_landing_page_details' => $a['hide_landing_page_details'],
				'hide_event_type_details'   => $a['hide_event_type_details'],
			);
			$a['url']    = add_query_arg( $hide_fields, $a['url'] );

			// Sanitize the classes (remove dots and normalize the number of spaces between each one).
			$classes = preg_replace( '/[ ]*\./', ' ', $a['classes'] );

			ob_start();
			?>
<!-- UseStrict's Calendly Embedder - Calendly link widget start -->
<a href="#" id="wpcalel-calendly-link-<?php echo esc_attr( $count ); ?>"
class="<?php echo esc_attr( $classes ); ?>" 
style="<?php echo esc_attr( $a['styles'] ); ?>"
data-wpcalel-type="link"
data-wpcalel-url="<?php echo esc_url( $a['url'] ); ?>"
data-wpcalel-prefill="<?php echo $a['prefill'] ? 'true' : 'false'; ?>"
data-wpcalel-query-str="<?php echo $a['query_str'] ? 'true' : 'false'; ?>"
>
			<?php echo esc_html( $a['text'] ); ?>
</a>
<!-- UseStrict's Calendly Embedder - Calendly link widget end -->
			<?php

			$content = ob_get_clean();

			return $content;
		}


		/**
		 * Calendly popup widget handler.
		 *
		 * @param array             $atts      The shortcode attributes.
		 * @param mixed NULL|string $content   The shortcode content.
		 * @return string
		 */
		private function calendly_popup_shortcode( $atts, $content = null ) {
			static $count = 0;
			$count++;

			$a = shortcode_atts(
				array(
					'url'                       => '',

					// Popup Widget settings.
					'text'                      => __( 'Schedule time with me', 'cal-embedder-lite' ),
					'color'                     => '006bff',
					'textColor'                 => 'ffffff',
					'branding'                  => false,

					// Booking page settings.
					'prefill'                   => false,
					'query_str'                 => false,
					'a1'                        => '',
					'a2'                        => '',
					'a3'                        => '',
					'a4'                        => '',
					'a5'                        => '',
					'a6'                        => '',
					'a7'                        => '',
					'a8'                        => '',
					'a9'                        => '',
					'a10'                       => '',
					'hide_gdpr_banner'          => false,
					'hide_landing_page_details' => false,
					'hide_event_type_details'   => false,
					'background_color'          => '',
					'text_color'                => '',
					'primary_color'             => '', // Button and link colors.
				),
				$atts,
				'cal-embedder-lite'
			);

			$a['prefill']                   = filter_var( $a['prefill'], FILTER_VALIDATE_BOOLEAN );
			$a['query_str']                 = filter_var( $a['query_str'], FILTER_VALIDATE_BOOLEAN );
			$a['branding']                  = filter_var( $a['branding'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_gdpr_banner']          = filter_var( $a['hide_gdpr_banner'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_landing_page_details'] = filter_var( $a['hide_landing_page_details'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_event_type_details']   = filter_var( $a['hide_event_type_details'], FILTER_VALIDATE_BOOLEAN );

			// Sanitize/normalize the URL.
			$parsed   = wp_parse_url( $a['url'] );
			$a['url'] = 'https://calendly.com/' . preg_replace( '@^/+@', '', trailingslashit( $parsed['path'] ) );

			$button_colors = array();
			if ( $a['color'] ) {
				$button_colors['color'] = $a['color'];
			}
			if ( $a['textColor'] ) {
				$button_colors['textColor'] = $a['textColor'];
			}

			// Normalize # characters.
			if ( ! empty( $button_colors ) ) {
				foreach ( $button_colors as $key => $color ) {
					$color     = str_replace( '#', '', $color );
					$a[ $key ] = '#' . $color;
				}
			}

			$colors = array();
			if ( $a['background_color'] ) {
				$colors['background_color'] = $a['background_color'];
			}
			if ( $a['text_color'] ) {
				$colors['text_color'] = $a['text_color'];
			}
			if ( $a['primary_color'] ) {
				$colors['primary_color'] = $a['primary_color'];
			}

			if ( ! empty( $colors ) ) {
				foreach ( $colors as &$color ) {
					$color = str_replace( '#', '', $color ); // Strip out any # characters.
				}

				$a['url'] = add_query_arg( $colors, $a['url'] );
			}

			$hide_fields = array(
				'hide_gdpr_banner'          => $a['hide_gdpr_banner'],
				'hide_landing_page_details' => $a['hide_landing_page_details'],
				'hide_event_type_details'   => $a['hide_event_type_details'],
			);
			$a['url']    = add_query_arg( $hide_fields, $a['url'] );

			ob_start();
			?>
<!-- UseStrict's Calendly Embedder - Calendly popup widget start -->
<div id="wpcalel-calendly-popup-<?php echo esc_attr( $count ); ?>"
	data-wpcalel-type="popup"
	data-wpcalel-url="<?php echo esc_url( $a['url'] ); ?>"
	data-wpcalel-prefill="<?php echo $a['prefill'] ? 'true' : 'false'; ?>"
	data-wpcalel-query-str="<?php echo $a['query_str'] ? 'true' : 'false'; ?>"
	data-wpcalel-text="<?php echo esc_attr( $a['text'] ); ?>"
	data-wpcalel-color="<?php echo esc_attr( $a['color'] ); ?>"
	data-wpcalel-textColor="<?php echo esc_attr( $a['textColor'] ); ?>"
	data-wpcalel-branding="<?php echo $a['branding'] ? 'true' : 'false'; ?>"
			<?php foreach ( range( 1, 10 ) as $key ) : ?>
				<?php if ( ! empty( $a[ 'a' . $key ] ) ) : ?>
	data-wpcalel-<?php echo esc_attr( 'a' . $key ); ?>="<?php echo esc_attr( $a[ 'a' . $key ] ); ?>"
		<?php endif; ?>
	<?php endforeach; ?> 
></div>
<!-- UseStrict's Calendly Embedder - Calendly popup widget end -->

			<?php

			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Calendly Inline shortcode handler.
		 *
		 * @see https://help.calendly.com/hc/en-us/articles/360020052833-Advanced-embed-options
		 * @param array             $atts      The shortcode attributes.
		 * @param mixed NULL|string $content   The shortcode content.
		 * @return string
		 */
		private function calendly_inline_shortcode( $atts, $content = null ) {
			static $count = 0;
			$count++;

			$a = shortcode_atts(
				array(
					'url'                       => '',

					// Booking page settings.
					'prefill'                   => false,
					'query_str'                 => false,
					'min-width'                 => '320px',
					'height'                    => '650px',
					'classes'                   => '',
					'styles'                    => 'overflow-x:hidden;overflow-y:hidden',
					'a1'                        => '',
					'a2'                        => '',
					'a3'                        => '',
					'a4'                        => '',
					'a5'                        => '',
					'a6'                        => '',
					'a7'                        => '',
					'a8'                        => '',
					'a9'                        => '',
					'a10'                       => '',
					'hide_gdpr_banner'          => false,
					'hide_landing_page_details' => false,
					'hide_event_type_details'   => false,
					'background_color'          => '',
					'text_color'                => '',
					'primary_color'             => '', // Button and link colors.
				),
				$atts,
				'cal-embedder-lite'
			);

			$a['prefill']                   = filter_var( $a['prefill'], FILTER_VALIDATE_BOOLEAN );
			$a['query_str']                 = filter_var( $a['query_str'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_gdpr_banner']          = filter_var( $a['hide_gdpr_banner'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_landing_page_details'] = filter_var( $a['hide_landing_page_details'], FILTER_VALIDATE_BOOLEAN );
			$a['hide_event_type_details']   = filter_var( $a['hide_event_type_details'], FILTER_VALIDATE_BOOLEAN );

			// Sanitize/normalize the URL.
			$parsed   = wp_parse_url( $a['url'] );
			$a['url'] = 'https://calendly.com/' . preg_replace( '@^/+@', '', trailingslashit( $parsed['path'] ) );

			$colors = array();
			if ( $a['background_color'] ) {
				$colors['background_color'] = $a['background_color'];
			}
			if ( $a['text_color'] ) {
				$colors['text_color'] = $a['text_color'];
			}
			if ( $a['primary_color'] ) {
				$colors['primary_color'] = $a['primary_color'];
			}

			if ( ! empty( $colors ) ) {
				foreach ( $colors as &$color ) {
					$color = str_replace( '#', '', $color ); // Strip out any # characters.
				}

				$a['url'] = add_query_arg( $colors, $a['url'] );
			}

			$hide_fields = array(
				'hide_gdpr_banner'          => $a['hide_gdpr_banner'],
				'hide_landing_page_details' => $a['hide_landing_page_details'],
				'hide_event_type_details'   => $a['hide_event_type_details'],
			);
			$a['url']    = add_query_arg( $hide_fields, $a['url'] );

			// Sanitize the classes (remove dots and normalize the number of spaces between each one).
			$classes = preg_replace( '/[ ]*\./', ' ', $a['classes'] );

			ob_start();
			?>
<!-- UseStrict's Calendly Embedder - Calendly inline widget start -->
<div id="wpcalel-calendly-inline-<?php echo esc_attr( $count ); ?>"
	class="calendly-inline-widget <?php echo esc_attr( $classes ); ?>" 
	style="min-width:<?php echo esc_attr( $a['min-width'] ); ?>; height:<?php echo esc_attr( $a['height'] ); ?>; <?php echo esc_attr( $a['styles'] ); ?>" 
	data-auto-load="false"
	data-wpcalel-type="inline"
	data-wpcalel-url="<?php echo esc_url( $a['url'] ); ?>"
	data-wpcalel-prefill="<?php echo $a['prefill'] ? 'true' : 'false'; ?>"
	data-wpcalel-query-str="<?php echo $a['query_str'] ? 'true' : 'false'; ?>"
			<?php foreach ( range( 1, 10 ) as $key ) : ?>
				<?php if ( ! empty( $a[ 'a' . $key ] ) ) : ?>
	data-wpcalel-<?php echo esc_attr( 'a' . $key ); ?>="<?php echo esc_attr( $a[ 'a' . $key ] ); ?>"
		<?php endif; ?>
	<?php endforeach; ?> 
>
	<div class="calendly-spinner">
		<div class="calendly-bounce1"></div><div class="calendly-bounce2"></div><div class="calendly-bounce3"></div>
	</div>
</div>
<!-- UseStrict's Calendly Embedder - Calendly inline widget end -->
			<?php

			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Ajax handler for user-info prefill.
		 */
		public function ajax_get_user_info() {
			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : null;

			if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'wpcalel-calendly' ) ) {
				wp_send_json_error( __( 'Missing or bad nonce', 'cal-embedder-lite' ), 500 );
			}

			$data = array();
			if ( is_user_logged_in() ) {
				$user              = wp_get_current_user();
				$data['name']      = $user->display_name;
				$data['firstName'] = $user->first_name;
				$data['lastName']  = $user->last_name;
				$data['email']     = $user->user_email;
			}

			wp_send_json_success( $data, 200 );
		}


		/**
		 * Adds the settings menu item.
		 */
		public function add_options_page() {
			$settings_page = add_options_page(
				'UseStrict\'s Calendly Embedder',
				'UseStrict\'s Calendly Embedder',
				'manage_options',
				__CLASS__,
				array( $this, 'show_admin' )
			);

			$this->settings_page = $settings_page;

			// Add CSS and JS.
			add_action( 'admin_head-' . $settings_page, array( $this, 'add_admin_scripts' ) );

			add_filter( 'plugin_action_links', array( $this, 'add_plugin_link' ), 10, 2 );
		}

		/**
		 * Adds the admin JS and CSS files
		 */
		public function add_admin_scripts() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_style( 'wpcalel-settings-admin', $this->environment->css_url . '/admin-settings.css', array( 'wp-color-picker' ), self::VERSION );
			wp_enqueue_script( 'wpcalel-settings-admin', $this->environment->js_url . '/admin-settings.js', array( 'jquery', 'wp-color-picker' ), self::VERSION, $in_footer = true );

			$min = defined( 'WP_DEBUG' ) && WP_DEBUG ? '.min' : '';
			wp_enqueue_script( 'wpcalel-jquery-template', $this->environment->js_url . "/jquery-tmpl{$min}.js", array( 'jquery' ), self::VERSION, $in_footer = true );
		}

		/**
		 * Shows the settings screen.
		 */
		public function show_admin() {
			$wpcalel_url_info = get_transient( self::$transient );

			if ( empty( $wpcalel_url_info ) ) {
				$wpcalel_url_info = isset( $this->settings->api_key ) || isset( $this->settings->pat ) ? $this->get_calendly_data() : array();
			}

			include __DIR__ . '/admin-settings.tmpl.php';
		}

		/**
		 * Whitelists our settings
		 */
		public function register_setting() {
			register_setting( $this->settings_name, $this->settings_name, array( $this, 'validate_settings' ) );
		}


		/**
		 * Fetches Calendly information, routing the request to the correct API version.
		 */
		private function get_calendly_data() {
			return isset( $this->settings->api_key ) ? $this->get_calendly_data_v1() : $this->get_calendly_data_v2();
		}


		/**
		 * Fetches Calendly information via the API version 2.
		 */
		private function get_calendly_data_v2() {
			$pat  = $this->settings->pat;
			$args = $this->build_args_for_pat( $pat );
			$data = array();

			// Get the user.
			$user = $this->get_calendly_user( $args );

			if ( ! $user ) {
				add_settings_error( __CLASS__, esc_attr( 'Personal Access Token' ), __( 'The Personal Access Token used is incorrect or has been revoked.', 'cal-embedder-lite' ), 'error' );
				return;
			}

			// Get the Event Types.
			$response = wp_remote_get( $this->calendly_api_url_v2 . '/event_types?user=' . $user->uri, $args );

			if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );

				/* translators:  1: the Calendly account holder's name */
				$owner     = sprintf( __( 'Profile (%1$s)', 'cal-embedder-lite' ), $user->name );
				$owner_url = $user->scheduling_url;

				$data[ $owner_url ] = $owner;

				foreach ( $body->collection as $event_type ) {
					if ( $event_type->scheduling_url === $owner_url ) {
						continue;
					}

					$name = $event_type->name;
					$url  = $event_type->scheduling_url;

					$data[ $url ] = $name;
				}

				/**
				 * Store the transient for 2 days. We automatically refresh after that.
				 */
				set_transient( self::$transient, $data, DAY_IN_SECONDS * 2 );
			}

			return $data;
		}


		/**
		 * Fetches the Calendly user given the PAT
		 *
		 * @param array $args         The header arguments.
		 * @return object|boolean
		 */
		public function get_calendly_user( $args ) {
			$response = wp_remote_get( $this->calendly_api_url_v2 . '/users/me', $args );

			$user = false;
			if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );

				$user = (object) array(
					'uri'            => $body->resource->uri,
					'name'           => $body->resource->name,
					'scheduling_url' => $body->resource->scheduling_url,
				);
			}

			return $user;
		}


		/**
		 * Fetches Calendly information via the API version 1.
		 */
		private function get_calendly_data_v1() {
			$api_key = $this->settings->api_key;

			$args = array(
				'headers' => array(
					'X-TOKEN' => $this->settings->api_key,
				),
			);

			$data = array();

			// Get the Event Types.
			$response = wp_remote_get( $this->calendly_api_url_v1 . '/users/me/event_types?include=owner', $args );

			if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );

				/* translators:  1: the Calendly account holder's name */
				$owner     = sprintf( __( 'Profile (%1$s)', 'cal-embedder-lite' ), $body->included[0]->attributes->name );
				$owner_url = $body->included[0]->attributes->url;

				$data[ $owner_url ] = $owner;

				foreach ( $body->data as $event_type ) {
					if ( $event_type->attributes->url === $owner_url ) {
						continue;
					}

					$name = $event_type->attributes->name;
					$url  = $event_type->attributes->url;

					$data[ $url ] = $name;
				}

				/**
				 * Store the transient for 2 days. We automatically refresh after that.
				 */
				set_transient( self::$transient, $data, DAY_IN_SECONDS * 2 );
			}

			return $data;
		}


		/**
		 * Ensure some basic settings
		 *
		 * @param array $_post POSTed values.
		 * @return string
		 */
		public static function validate_settings( $_post ) {
			ksort( $_post );
			$user = false;
			foreach ( $_post as $key => $value ) {
				switch ( $key ) {
					case 'api_key':
						if ( $value && empty( $_post['disconnect'] ) ) {
							add_settings_error( __CLASS__, esc_attr( 'API Key' ), __( 'The API Key is deprecated. Use a Personal Access Token instead.', 'cal-embedder-lite' ) );
							unset( $_post[ $key ] );
						}
						break;
					case 'pat':
						$value = trim( $value );
						if ( ! $value && empty( $_post['disconnect'] ) ) {
							add_settings_error( __CLASS__, esc_attr( 'Personal Access Token' ), __( 'The Personal Access Token is required', 'cal-embedder-lite' ) );
							unset( $_post[ $key ] );
						} else {
							$args = wpcalel()->build_args_for_pat( $value );
							$user = wpcalel()->get_calendly_user( $args );
							if ( ! $user ) {
								add_settings_error( __CLASS__, esc_attr( 'Personal Access Token' ), __( 'The Personal Access Token used is incorrect or has been revoked.', 'cal-embedder-lite' ) );
								unset( $_post[ $key ] );
							}
						}
						break;
					case 'disconnect':
						unset( $_post['api_key'] );
						unset( $_post['pat'] );
						delete_transient( self::$transient );
						add_settings_error( __CLASS__, esc_attr( 'API Key' ), __( 'Disconnected!', 'cal-embedder-lite' ), 'success' );
						break;
					case 'refresh':
						delete_transient( self::$transient );
						if ( $user ) {
							add_settings_error( __CLASS_, esc_attr( 'Refresh URLs' ), __( 'Refreshed!', 'cal-embedder-lite' ), 'success' );
						}
						break;
					default:
				}
			}

			return $_post;
		}

		/**
		 * Builds the args array for calling Calendly's API.
		 *
		 * @param string $pat       The Personal Access Token.
		 * @return string[][]
		 */
		public function build_args_for_pat( $pat ) {
			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $pat,
					'Content-type: application/json',
				),
			);

			return $args;
		}

		/**
		 * Add 'Settings' to the plugin actions.
		 *
		 * @param array  $plugin_actions  Array of plugin actions.
		 * @param string $plugin_file     The name of the plugin file.
		 * @return array
		 */
		public function add_plugin_link( $plugin_actions, $plugin_file ) {
			if ( $this->environment->plugin_basename === $plugin_file ) {
																/* translators: the URL of the settings page */
				$plugin_actions['PluginClass_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'cal-embedder-lite' ), esc_url( admin_url( 'options-general.php?page=' . __CLASS__ ) ) );
			}

			return $plugin_actions;
		}

	} // End of class.


	/**
	 * Wrapper function for our instance.
	 *
	 * @return Wp_Cal_Embed_Lite
	 */
	function wpcalel() {
		return Wp_Cal_Embed_Lite::instance();
	}

	wpcalel(); // Kick off the class.

endif; // End if class_exists.

/**
 * End file cal-embedder-lite.php
 */
