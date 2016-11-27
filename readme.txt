=== Advanced Custom Fields: GeoMeta Field ===
Contributors: stuporglue, cimburadotcom
Tags: GIS, Spatial, ACF, WP-GeoMeta, GeoJSON
Requires at least: 4.4.0
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Store real spatial data with ACF, using the WP-GeoMeta library.

== Description ==

GeoMeta for ACF is an easy way to store location information about posts, pages,
users or other content types that ACF supports. The default input is a map
with drawing tools to let the user draw markers, lines and polygons.
Alternatively you can accept latitude and longitude values, or raw GeoJSON
text.

GeoMeta for ACF supports for both ACF version 4  and 5.

Why use GeoMeta for ACF instead of one of the other map inputs for ACF? 

GeoMeta for ACF uses WP-GeoMeta under the hood, which means that you're
actually storing spatial meta data, not just text. With the WP-GeoMeta library
you have access to all of the spatial functions MySQL supports to search and
filter your WordPress posts and users. 


= Compatibility =

This ACF field type is compatible with:
* ACF 5 / ACF Pro
* ACF 4

== Installation ==

Install this plugin in the usual WordPress way, then go to your WordPress
dashboard to Tools::WP GeoMeta to see the status of your spatial data and
to use the included tools.

1. Upload the plugin files to the `/wp-content/plugins/geometa-acf` directory,
	or install the plugin through the WordPress plugin screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Create a new field via ACF and select the GeoMeta type.

== Frequently Asked Questions ==

No one has actually asked any questions yet!

= How can I run spatial queries? =

For sample queries, please see the [WP-GeoMeta
documentation](https://github.com/cimburadotcom/wp-geometa);

= Where can I get help with GIS and WordPress? = 

For community support try [WherePress.com](http://WherePress.com/)

For commercial support you can contact the plugin developer at
[Cimbura.com](https://cimbura.com/contact-us/project-request-form/)

For fast and short questions you can [contact
me](https://twitter.com/stuporglue) on twitter.

== Screenshots ==

Coming soon!

== Changelog ==

= 0.0.2 =
* Set up I18N support and Portuguese translation
* Code cleanup
* A start on code documentation

= 0.0.1 =
* Initial Release.
* Support for Map input, lat/lng fields and raw GeoJSON input
* Support for ACF v4 and ACF Pro/v5
