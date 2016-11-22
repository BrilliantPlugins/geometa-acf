=== Advanced Custom Fields: GeoMeta Field ===
Contributors: stuporglue, cimburadotcom
Tags: GIS, Spatial, ACF, WP-GeoMeta
Requires at least: 4.4.0
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Store real spatial data with ACF, using the WP-GeoMeta library.

== Description ==

GeoMeta for ACF Stores location information using
[ACF](https://www.advancedcustomfields.com/) and the [WP-GeoMeta
library](http://wherepress.com/projects-and-plugins/wp-geometa/).

GeoMeta for ACF is an easy way to store spatial information about posts, pages,
users or other content types that ACF supports.

ACF, Advanced Custom Fields, is an easy and convenient way to add custom meta
fields to posts, pages, users and other content types types. It offers an
easy to use UI to manage which custom meta types correspond with which post 
types and even gives you conditional controls to determine when those custom 
meta types show up. 

WP-GeoMeta is a library (built in to ACF GeoMeta) that detects when GeoJSON is
being stored as metadata and stores it a spatially-enabled MySQL metadata 
tables. 

This lets you run spatial queries using WP_Query and other functions that use
WP_Meta_Query under the hood.


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


== Changelog ==

= 0.0.1 =
* Initial Release.
