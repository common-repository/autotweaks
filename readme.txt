=== AutoTweaks ===
Contributors: Kodam
Tags: optimization, tweaks, settings, wpo, speed, auto
Donate link: https://www.paypal.me/luisceladita
Requires at least: 4.2
Tested up to: 5.8
Stable tag: 1.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Classic Setup: Removes WP version, dashicons, oEmbed, Jquery Migrate, XMLRPC. Set Http security headers, heartbeat to 60s, Post revisions to 1, etc

== Description ==
**This plugin is fully compatible with Autoptimize and any cache plugin (Cache Enabler or Wp Super Cache by example)**

Some of the tweaks that this plugin automatically applies securely are the following:

= SET HTTP SECURITY HEADERS =
* X-Frame-Options: SAMEORIGIN
* X-XSS-Protection: 1;mode=block
* Referrer-Policy: no-referrer-when-downgrade
* X-Content-Type-Options: nosniff
* Strict-Transport-Security: max-age=15552000
* Content-Security-Policy

= REMOVE THIS =
* Remove Really Simple Discovery link from header
* Remove wlwmanifest.xml (Windows Live Writer) from header
* Remove Shortlink URL from header
* Remove WordPress Generator Version from header
* Remove s.w.org DNS Prefetch
* Remove generator name from RSS Feeds
* Remove Capital P Dangit filter
* Remove WordPress and WooCommerce meta generator tags
* Remove Jquery_migrate
* Remove Dashicons in admin bar (only for non logged users)
* Remove Post oEmbed

= AND MORE AUTO SETTINGS =
* Change Control Heartbeat API interval (60 seconds)
* Disable the XML-RPC interface
* Disable PDF thumbnails preview
* Disable Self Pingbacks
* Limit Post Revisions to 1
* Add IDs to posts, pages, and categories

Just activate the plugin and test your site’s speed in your favourite tool (GTMetrix, Pingdom Tools, Securityheaders.com, etc.)

== Installation ==
1. Upload this plugin to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= 1. Why doesn't AutoTweaks remove query strings from static resources? =
Because autoptimize already has that option ("Extra" tab)

= 2. Why doesn't AutoTweaks remove styles and dashes from emojis? =
Because autoptimize already has that option ("Extra" tab)

== Screenshots ==
1. AutoTweaks will help you to be closer to 100/100 in PageSpeed

== Changelog ==
= 1.4 =
* minor changes
= 1.3 =
* minor changes
= 1.2 =
* minor changes
= 1.1 =
* minor changes
= 1.0 =
* Hello universe!