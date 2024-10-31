=== Seo Monster ===
Contributors: steepdigital
Tags: Bot Monitoring, 410, XML Sitemap, Omega Indexer, Speedlinks, Scaleserp
Requires at least: 5.x
Tested up to: 5.5.1
Stable tag: trunk
License: GPL v3 - http://www.gnu.org/licenses/gpl-3.0.html

Monitor your pages when it was last modified, last crawled and record the crawl frequency.

== Description ==

Monitor your pages when it was last modified, last crawled and record the crawl frequency. SEO Monster also allows you to assign your pages to 410 and generate XML sitemap. Integrating SEO Monster with Omega Indexer, Speedlinks and ScaleSERP allows you to do their services directly to your wp dashboard.

-Assign pages to 410 Url gone for index removal.
-Directly Submit pages to Omega Indexer.
-Directly Submit pages to Speedlinks.
-Directly check to see if the page is in google using scaleserp.
-Create your own XML Sitemap based on selected pages.

### SEO Monster Core Function ###
SEO Monster is a google bot monitoring software to keep track of google bot activity. SEO Monster also keeps track of how frequent your pages were visited or when your posts/pages was crawled by google and compare it to when the last time your post/page is modified.

### SEO Monster Integration ###

* **Omega Indexer / Speedlinks**
SEO Monster must be integrated with a third party service either Omega Indexer or Speedlinks to be able to submit bulk urls to be indexed by google. See more info at [Omega Indexer](https://www.omegaindexer.com/) [Speed Links](https://speed-links.net/). [Omega Indexer TOS](https://www.omegaindexer.com/termsofservices/) & [Privacy Policy](https://www.omegaindexer.com/privacy-policy/). Read about [Speedlinks TOS](https://speed-links.net/tos.php) & [Privacy Policy](https://speed-links.net/privacy.php)

* **ScaleSERP**
SEO Monster must be integrated with [Scale SERP](https://scaleserp.com/) to be able to check whether or not your page(s) are indexed by google. Read about [Scaleserp TOS](https://scaleserp.com/terms) & [Privacy Policy](https://scaleserp.com/privacy)

If you come across any bugs or have a feature request, please use the plugin support forum.

== Installation ==
1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The plugin settings can be accessed via the 'Seo Monster' menu in the administration area

== Changelog ==

= 3.2.8 =
* Update Function - Update 410 Whitelist function, optimized performance.

= 3.2.7 =
* Add Function - Update 410 Whitelist URLs to handle pages/posts more than a hundred.

= 3.2.6 =
* Add Function - 410 All URLs Except the whitelisted URLs. To confirm that it is working, install a google chrome extension that can change useragent and act as googlebot then visit the unwanted URL. Usefull when you have thousands of hacked URLs indexed by google while you have few legit pages. 

= 3.2.5 =
* Fix: Google frequency and google last visit function
* Update: WP Rest url to optimize page load if pages/posts count is greater than 500

= 2.0.4 =
* Fix: XML Date Format for Google Search Console.
