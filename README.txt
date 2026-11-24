=== Plugin Name ===
Contributors: sevenio
Tags: sms, text2speech
Requires at least: 3.1.0
Requires PHP: 7.2
Tested up to: 5.4.3
Stable tag: 1.0.2
License: MIT
License URI: LICENSE.txt

Send SMS through WordPress via the seven.io gateway.

== Description ==

Send SMS and text-to-speech messages through WordPress via the seven.io gateway.

== Installation ==

1. Extract the release archive contents to `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Head to 'Settings->seven API' and set your API key

== Screenshots ==

1. Send SMS
2. Plugin Settings

== Changelog ==

= 1.0.2 =
* Security: escape all output on the plugin pages (fixes reflected and stored XSS).
* Security: bulk actions (resend, lookup again, delete) always require a valid nonce; row IDs and sorting are no longer passed into SQL unchecked.
* Security: the API key is sent in the X-Api-Key header instead of the URL; SMS and voice are sent via POST.
* No more third-party assets: the SMS counter is bundled, the jQuery UI datepicker and its Google-hosted stylesheet are replaced by native date inputs.
* Fix: quotes and backslashes in messages are no longer sent with an extra backslash.
* Fix: WooCommerce bulk SMS errors are shown again; the date filter now uses YYYY-MM-DD.

= 1.0.1 =
* Security: require the manage_options capability and a valid nonce for sending SMS, voice calls, WooCommerce bulk SMS and number lookups.

= 1.0 =
* Initial release.
