<?php
/**
 * Cal Embedder Lite Settigs screen.
 *
 * @package Wp_Cal_Embed_Lite
 * @version 1.0
 */

$wpcalel_button_text = __( 'Refresh URLs', 'cal-embedder-lite' );

?>

<script id="wpcalel-shortcode-tmpl" type="text/html">[wpcalel type="calendly" {{params}}]</script>

<form method="POST" action="options.php" class="" id="Wp_Cal_Embed_Lite">
<?php settings_fields( $this->settings_name ); ?>

	<div class="<?php echo esc_attr( $this->settings_name ); ?>">
		<div id="poststuff" class="metabox-holder">
			<div id="post-body-content">

				<?php /* translators: the plugin version */ ?>
				<h1><?php printf( esc_html__( 'UseStrict\'s Calendly Embedder (v%s)', 'cal-embedder-lite' ), esc_html( self::VERSION ) ); ?></h1>

				<div class="postbox">
					<h2 class="hndle"><?php esc_html_e( 'Integration', 'cal-embedder-lite' ); ?></h2>
					<div class="inside">
						<?php if ( isset( $this->settings->api_key ) ) : ?>
							<div class="notice notice-error inline"><p>
							<?php
							_e(  // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction
								'Using an API Key to connect to Calendly is deprecated and <strong>will stop working in December, 2022</strong>.
Please create a <a href="https://calendly.com/integrations/api_webhooks" target="_new">Personal Access Token</a>, click disconnect, and reconnect using the new token.',
								'cal-embedder-lite'
							);
							?>
																		</p></div>
						<?php endif; ?>
					</div>
					<?php if ( isset( $this->settings->api_key ) ) : ?>			
					<h3><?php esc_html_e( 'API Key', 'cal-embedder-lite' ); ?></h3>
					<?php else : ?>
					<h3><?php esc_html_e( 'Personal Access Token', 'cal-embedder-lite' ); ?></h3>
					<?php endif; ?>
					<div class="inside">
						<div class="api-key-settings">
						<?php $wpcalel_key = 'pat'; ?>
							<?php if ( ! isset( $this->settings->api_key ) && ! isset( $this->settings->pat ) ) : ?>

								<p><?php esc_html_e( 'Please enter your Calendly Personal Access Token below and click "Connect". This will help us get your Event Types from Calendly and display them in the shortcode builder.', 'cal-embedder-lite' ); ?></p>

								<?php
									$wpcalel_button_text = __( 'Connect', 'cal-embedder-lite' );
									$wpcalel_value       = '';
								?>

							<?php elseif ( isset( $this->settings->api_key ) ) : ?>

								<p><?php esc_html_e( 'Your Calendly API Key.', 'cal-embedder-lite' ); ?></p>

								<?php
									$wpcalel_key   = 'api_key';
									$wpcalel_value = isset( $this->settings->api_key ) ? $this->settings->api_key : '';
								?>

							<?php else : ?>

								<p><?php esc_html_e( 'Your Calendly Personal Access Token.', 'cal-embedder-lite' ); ?></p>
								<?php
									$wpcalel_value = isset( $this->settings->pat ) ? $this->settings->pat : '';
								?>

							<?php endif; ?>

								<p>
									<input type="text" class="wide" name="<?php echo esc_html( $this->settings_name ); ?>[<?php echo esc_attr( $wpcalel_key ); ?>]" value="<?php echo esc_attr( $wpcalel_value ); ?>" />
								</p>							

							<?php if ( ! isset( $this->settings->api_key ) && ! isset( $this->settings->pat ) ) : ?>
							<p class="description">
								<?php
								/* translators: link to calendly integrations */
									printf( __( 'You can find it <a href="%s" target="_blank">here</a>.', 'cal-embedder-lite' ), esc_url( 'https://calendly.com/integrations/api_webhooks' ) ); // phpcs:ignore WordPress.Security.EscapeOutput 
								?>
							</p>
							<?php endif; ?>
						</div>
					</div>
				</div>


				<div>
					<p>
						<?php
						submit_button(
							$wpcalel_button_text,
							'primary',
							'Connect' === $wpcalel_button_text ? 'submit' : esc_html( $this->settings_name ) . '[refresh]',
							false // wrap.
						);
						?>
						<?php if ( isset( $this->settings->api_key ) || isset( $this->settings->pat ) ) : ?>
							<?php
							submit_button(
								esc_html__( 'Disconnect', 'cal-embedder-lite' ),
								'wpcalel-disconnect',
								esc_html( $this->settings_name ) . '[disconnect]',
								false, // wrap.
								array(
									'style' => 'margin-left:30px;',
									'id'    => esc_attr( $this->settings_name . '-disconnect' ),
								)
							);
							?>
						<?php endif; ?>
					</p>
				</div>




				<!--  Now the Builder -->
				<div class="wpcalel-builder">
					<div class="postbox">
						<h2 class="hndle"><?php esc_html_e( 'Shortcode Builder', 'cal-embedder-lite' ); ?></h2>
						<div class="inside">
							<div class="column left">
								<table>
									<tr class="settings-section">
										<th colspan="2">
											<?php esc_html_e( 'Required Fields', 'cal-embedder-lite' ); ?>
										</th>
									</tr>
									<tr>
										<th><?php esc_html_e( 'URL', 'cal-embedder-lite' ); ?></th>
										<td>
											<?php if ( ! empty( $wpcalel_url_info ) ) : ?>
											<select id="url">
												<?php $wpcalel_is_profile = true; ?>
												<?php foreach ( $wpcalel_url_info as $wpcalel_url => $wpcalel_name ) : ?>
													<option value="<?php echo esc_url( $wpcalel_url ); ?>"

														<?php
														if ( true === $wpcalel_is_profile ) :
															?>
															data-is-profile="true" 
															<?php
															$wpcalel_is_profile = false;
														endif;
														?>

													><?php echo esc_html( $wpcalel_name ); ?></option>
												<?php endforeach; ?>
											</select>
											<?php else : ?>
												<input type="text" id="url" /> 
											<?php endif; ?>
										</td>
									</tr>

									<!-- Embed type settings -->
									<tr>
										<th><?php esc_html_e( 'Embed Type', 'cal-embedder-lite' ); ?></th>
										<td>
											<select id="widget">
												<option value="inline"><?php esc_html_e( 'Inline', 'cal-embedder-lite' ); ?></option>
												<option value="link"><?php esc_html_e( 'Pop-up Text', 'cal-embedder-lite' ); ?></option>
												<option value="popup"><?php esc_html_e( 'Pop-up Widget', 'cal-embedder-lite' ); ?></option>
											</select>
										</td>
									</tr>

									<tr class="settings-section widget-settings">
										<th colspan="2">
											<?php esc_html_e( 'Widget Settings', 'cal-embedder-lite' ); ?>
										</th>
									</tr>

									<tr class="link-widget popup-widget">
										<th><label for="text"><?php esc_html_e( 'Text', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input type="text" id="text" value="<?php esc_attr_e( 'Schedule time with me', 'cal-embedder-lite' ); ?>" />
										</td>
									</tr>

									<tr class="popup-widget">
										<th><label for="color"><?php esc_html_e( 'Color', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input class="color-field" type="text" id="color" value="#006bff" />
										</td>
									</tr>

									<tr class="popup-widget">
										<th><label for="textColor"><?php esc_html_e( 'Text Color', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input class="color-field" type="text" id="textColor" value="#ffffff" />
										</td>
									</tr>

									<tr class="popup-widget">
										<th><label for="branding"><?php esc_html_e( 'Include Branding', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input type="checkbox" id="branding"  />
										</td>
									</tr>


									<!-- Booking Settings -->
									<tr class="settings-section">
										<th colspan="2">
											<?php esc_html_e( 'Booking Page Settings', 'cal-embedder-lite' ); ?>
										</th>
									</tr>

									<tr class="booking-settings">
										<th><label for="prefill"><?php esc_html_e( 'Prefill User Info', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="checkbox" id="prefill" /></td>
									</tr>

									<tr class="booking-settings">
										<th><label for="query_str"><?php esc_html_e( 'Allow Query Strings', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="checkbox" id="query_str" /></td>
									</tr>

									<tr class="hideable inline-widget">
										<th><label for="min-width"><?php esc_html_e( 'Min. Width', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="text" id="min-width" value="100%" /></td>
									</tr>

									<tr class="hideable inline-widget">
										<th><label for="height"><?php esc_html_e( 'Height', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="text" id="height" value="650px" /></td>
									</tr>

									<tr class="hideable inline-widget link-widget">
										<th><label for="classes"><?php esc_html_e( 'Extra Classes', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="text" id="classes" value="" /></td>
									</tr>

									<tr class="hideable inline-widget link-widget">
										<th><label for="styles"><?php esc_html_e( 'Extra Styles', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="text" id="styles" value="overflow-x:hidden;overflow-y:hidden" /></td>
									</tr>

									<?php foreach ( range( 1, 10 ) as $wpcalel_i ) : ?>

									<tr class="booking-settings">
										<?php /* translators: %d is the number of the custom answer */ ?>
										<th><label for="<?php echo esc_attr( 'a' . $wpcalel_i ); ?>"><?php printf( esc_html__( 'Custom Answer %d', 'cal-embedder-lite' ), $wpcalel_i ); //phpcs:ignore WordPress.Security.EscapeOutput ?></label></th>
										<td><input type="text" id="<?php echo esc_attr( 'a' . $wpcalel_i ); ?>" value="" /></td>
									</tr>

									<?php endforeach; ?>

									<tr class="booking-settings">
										<th><label for="hide_gdpr_banner"><?php esc_html_e( 'Hide GDPR Banner', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="checkbox" id="hide_gdpr_banner" /></td>
									</tr>

									<tr class="landing_page_details">
										<th><label for="hide_landing_page_details"><?php esc_html_e( 'Hide Landing Page Details', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="checkbox" id="hide_landing_page_details" /></td>
									</tr>

									<tr class="booking-settings">
										<th><label for="hide_event_type_details"><?php esc_html_e( 'Hide Event Type Details', 'cal-embedder-lite' ); ?></label></th>
										<td><input type="checkbox" id="hide_event_type_details" /></td>
									</tr>

									<tr class="booking-settings">
										<th><label for="background_color"><?php esc_html_e( 'Background Color', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input class="color-field" type="text" id="background_color" value="" />
										</td>
									</tr>

									<tr class="booking-settings">
										<th><label for="text_color"><?php esc_html_e( 'Text Color', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input class="color-field" type="text" id="text_color" value="" />
										</td>
									</tr>

									<tr class="booking-settings">
										<th><label for="primary_color"><?php esc_html_e( 'Primary Color', 'cal-embedder-lite' ); ?></label></th>
										<td>
											<input class="color-field" type="text" id="primary_color" value="" />
										</td>
									</tr>

								</table>
							</div>

							<div class="column right">
								<textarea id="wpcalel-shortcode" readonly="readonly"><?php esc_html_e( 'Loading...', 'cal-embedder-lite' ); ?></textarea>
								<p style="text-align:right"><span class="dashicons dashicons-admin-page"></span> <a href="#" id="wpcalel-click-to-copy"
								data-click-to="<?php esc_attr_e( 'Click to Copy.', 'cal-embedder-lite' ); ?>"
								data-copied="<?php esc_attr_e( 'Copied!', 'cal-embedder-lite' ); ?>"
								><?php esc_html_e( 'Click to copy.', 'cal-embedder-lite' ); ?></a></p>

								<div class="help">
									<h3><?php esc_html_e( 'Instructions', 'cal-embedder-lite' ); ?></h3>
									<p><?php esc_html_e( 'Select the options and then click the "copy" link to copy the shortcode into the clipboard. Then paste it in the post/page where you want the Calendly widget to appear.', 'cal-embedder-lite' ); ?></p>
									<h4><?php esc_html_e( 'Required Fields:', 'cal-embedder-lite' ); ?></h4>
									<ul>
										<li>
											<strong><?php esc_html_e( 'URL:', 'cal-embedder-lite' ); ?></strong>
											<?php esc_html_e( 'This is the URL that is passed to the Calendly widget. Choose the "Profile" URL to display all of your Event Types.', 'cal-embedder-lite' ); ?>
										</li>
										<li>
											<strong><?php esc_html_e( 'Embed Type:', 'cal-embedder-lite' ); ?></strong> 
											<?php
												/* translators: link to Calendly help */
											printf( __( 'See <a href="%s" target="_blank">this</a> for information on each type.', 'cal-embedder-lite' ), esc_url( 'https://help.calendly.com/hc/en-us/articles/223147027-Embed-options-overview' ) ); //phpcs:ignore WordPress.Security.EscapeOutput
											?>
										</li>
									</ul>

									<h4><?php esc_html_e( 'Booking Page Fields', 'cal-embedder-lite' ); ?></h4>
									<ul>
										<li>
											<strong><?php esc_html_e( 'Prefill User Info:', 'cal-embedder-lite' ); ?></strong>
											<?php esc_html_e( 'Turn this on if you want to pre-populate the booking form with the logged-in user\'s Name, First and Last Name, and Email, depending on your form fields.', 'cal-embedder-lite' ); ?>
										</li>
										<li>
											<strong><?php esc_html_e( 'Allow Query Strings:', 'cal-embedder-lite' ); ?></strong>
											<?php _e( 'Whether to allow Calendly to fetch information from the page URL. Supported strings are <code>name</code>, <code>firstName</code>, <code>lastName</code> and <code>email</code>. If the user is logged in and Prefill is enabled, the Prefill information will take precedence.', 'cal-embedder-lite' ); //phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction ?>
										</li>
										<li>
											<strong><?php esc_html_e( 'Min. Width and Height:', 'cal-embedder-lite' ); ?></strong>
											<?php esc_html_e( 'Adjust dimensions of the iframe that Calendly creates.', 'cal-embedder-lite' ); ?>
										</li>
										<li>
											<strong><?php esc_html_e( 'Custom Answers 1 to 10:', 'cal-embedder-lite' ); ?></strong>
											<?php
											/* translators: Calendly help URL */
											printf( __( 'Calendly allows for up to 10 extra fields of various types. They explain the values you can enter <a href="%s" target="_blank">here</a>.', 'cal-embedder-lite' ), esc_html( 'https://help.calendly.com/hc/en-us/articles/226766767-Pre-populate-invitee-information-on-the-scheduling-page' ) ); //phpcs:ignore WordPress.Security.EscapeOutput
											?>
										</li>
									</ul>

									<div class="promo">
										<p>
										<?php
										/* translators: Link to plugin author site */
										printf( __( 'For more powerful control of your widgets, check out our Calendly Embedder Pro plugin over at <a href="%s">UseStrict Consulting</a>.', 'cal-embedder-lite' ), esc_url( 'https://usestrict.net/calendly-embedder-pro/?utm_source=settings_screen&utm_medium=plugin&utm_campaign=promotion' ) ); //phpcs:ignore WordPress.Security.EscapeOutput
										?>
										</p>
										<p><?php esc_html_e( 'Features include:', 'cal-embedder-lite' ); ?></p>
										<ul>
											<li><?php esc_html_e( 'Save your shortcodes in the shortcode library for ease of use.', 'cal-embedder-lite' ); ?></li>
											<li><?php esc_html_e( 'Embed using a Gutenberg block.', 'cal-embedder-lite' ); ?></li>
											<li><?php esc_html_e( 'Embed using an Elementor widget.', 'cal-embedder-lite' ); ?></li>
											<li><?php esc_html_e( 'Setting Google Analytics variables (Campaign, Source, Medium, Content and Term)', 'cal-embedder-lite' ); ?></li>
											<li><?php esc_html_e( 'Track interaction such as:', 'cal-embedder-lite' ); ?>
												<ul>
													<li><?php esc_html_e( 'Profile page was viewed', 'cal-embedder-lite' ); ?></li>
													<li><?php esc_html_e( 'Event type page was viewed', 'cal-embedder-lite' ); ?></li>
													<li><?php esc_html_e( 'Invitee selected date and time', 'cal-embedder-lite' ); ?></li>
													<li><?php esc_html_e( 'Invitee successfully booked a meeting', 'cal-embedder-lite' ); ?></li>
												</ul>
											</li>
										</ul>	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
