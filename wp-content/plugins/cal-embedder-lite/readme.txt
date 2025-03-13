=== UseStrict's Calendly Embedder ===
Contributors: useStrict
Donate link: https://paypal.me/usestrict
Tags: calendar, calendly, embed
Requires at least: 4.5
Tested up to: 6.5.5
Requires PHP: 5.6
Stable tag: 1.1.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simple but powerful embedding for Calendly.

== Description ==

Easily embed Calendly widgets onto your pages, with the ability to prefill the fields from the database or query strings. Prefilling is
done via Javascript and Ajax, so it is **caching-safe**.

Use the configurator in the settings to generate the shortcode with all supported parameters.

Check out the <a href="https://usestrict.net/calendly-embedder-pro/?utm_source=readme&utm_medium=plugin&utm_campaign=promotion" target="_blank" title="UseStrict's Calendly Embedder Pro">Pro version</a> for the following:

* Save your shortcodes in a library for ease of use;
* Embed using a Gutenberg block;
* Embed using an Elementor widget;
* Setting Google Analytics variables (Campaign, Source, Medium, Content and Term)
* Tracking interaction such as
 * Profile page was viewed
 * Event type page was viewed
 * Invitee selected date and time
 * Invitee successfully booked a meeting


== Installation ==

= From the Admin =

1. Go to Admin > Plugins > Add New and search for UseStrict's Calendly Embedder.
1. Click Install.
1. Click Activate.
1. Find the settings under Settings > UseStrict's Calendly Embedder.

= Via FTP =

1. Download and unpack the plugin zip file.
1. Upload the entire folder to your plugins directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Find the settings under Settings > UseStrict's Calendly Embedder.

== Frequently Asked Questions ==

No F.A.Q. yet.

== Screenshots ==

1. The settings screen, before connecting to the Calendly API.
2. By connecting to the Calendly API, we display a drop-down with your Profile screen and individual Event Types.
3. Sample drop-down values.
4. Extra options for displaying the **Pop-up Text Widget**, a.k.a. **Link** widget. Default is inline, as seen in the previous screenshots.
5. Extra options for displaying the **Pop-up Widget**, a.k.a. the **Badge** widget.


== Changelog ==
* Fix missing classes and styles attributes for link-type calendar reported by @aabdullaev.
* Update tested up to.

= 1.1.6.2 =
* Update stable tag.

= 1.1.6.1 =
* Fix stable tag in readme.txt.

= 1.1.6 =
* Decode URI Component for custom answers.
* Remove spurious code block left over from copy/paste.

= 1.1.5.1 =
* Adjusted admin documentation to point out which query-string variables are supported.

= 1.1.5 =
* Break away query-string from prefill options. They now work independently, but prefill still overrides query-string.
* Support prefilling of the email via query-string.

= 1.1.4 =
* Fix problems with query strings and custom answers.

= 1.1.3 =
* Bumping the version to get readme.txt tested up to 6.1.

= 1.1.2 =
* Bumping the version to get the readme.txt stable tag fix.

= 1.1.1 =
* Tweak notices when using bad PATs.

= 1.1 =
* Added deprecation notice for API Key
* Added support for Calendly API v2 via Personal Access Token.

= 1.0.2 =
* Add missed hide* options to calendly URL.

= 1.0.1 =
* Fix the plugin name in the Settings screen.
* Add sections headers in the Shortcode Builder.
* Fix a bug in the JS when prefilling.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
No upgrade required at this time.
