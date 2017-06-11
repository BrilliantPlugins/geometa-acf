=== GeoMeta For ACF ===
Contributors: stuporglue, luminfire
Tags: Advanced Custom Fields, GIS, geo, Spatial, ACF, WP-GeoMeta, GeoJSON
Requires at least: 4.4.0
Tested up to: 4.8
Stable tag: 0.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Store real spatial data with Advanced Custom Fields, using the WP-GeoMeta library.

== Description ==

GeoMeta for ACF is an easy way to store location information about posts, pages, users or other content types that ACF supports. The default input is a map with drawing tools to let the user draw markers, lines and polygons.  Alternatively you can accept latitude and longitude values, or raw GeoJSON text.

GeoMeta for ACF supports for both ACF version 4  and 5.

Why use GeoMeta for ACF instead of one of the other map inputs for ACF? 

GeoMeta for ACF uses WP-GeoMeta under the hood, which means that you're actually storing spatial meta data, not just text. With the WP-GeoMeta library you have access to all of the spatial functions MySQL supports to search and filter your WordPress posts and users. 


= Compatibility =

This ACF field type is compatible with:

* ACF 5 / ACF Pro
* ACF 4

*Note*

Metavalues for terms (Categories/Tags) were stored in wp_options up until ACF Pro 5.5.0. GeoMeta for ACF will still display a map or other input field for terms in previous versions of ACF, but since the values aren't stored in the wp_termmeta table, they won't be picked up for inclusion in the spatial table and spatial searches on them will fail. 

== Installation ==

Be sure that Advanced Custom Fields is installed. You can use Advanced Custom Fields (v4) or Advanced Custom Fields PRO (v5). Version 4 is [in the plugin repository](https://wordpress.org/plugins/advanced-custom-fields/). ACF PRO is [available from https://www.advancedcustomfields.com/pro/](https://www.advancedcustomfields.com/pro/).

With ACF installed, you can install this plugin in the usual WordPress way.

1. Upload the plugin files to the `/wp-content/plugins/geometa-acf` directory, or install the plugin through the WordPress plugin screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.


Create a new field in ACF and select the GeoMeta field type.


== Frequently Asked Questions ==

No one has actually asked any questions yet!

= How can I run spatial queries? =

GeoMeta for ACF uses WP-GeoMeta internally.  For sample queries, please see the [WP-GeoMeta documentation](https://github.com/BrilliantPlugins/wp-geometa#querying).

= Where can I get help with GIS and WordPress? = 

For community support try [WherePress.com](http://WherePress.com/)

For commercial support you can contact the plugin developer at [Cimbura.com](https://cimbura.com/contact-us/project-request-form/)

For fast and short questions you can [contact me](https://twitter.com/stuporglue) on twitter.

== Screenshots ==

1. You can select the GeoMeta field type within the ACF field group like you would expect. You can choose one of three options for how users will enter spatial data.
2. The most likely option is to display a map with drawing tools. Editors can add, edit and delete shapes on the map.
3. If your users expect to be able to enter latitude and longitude, you can opt to capture those instead. The value is saved to the postmeta table as GeoJSON.
4. You can also allow editors to paste in raw GeoJSON text if that better meets your needs. 

== Changelog ==

= 0.0.5 = 
* Updated wp-geometa-lib
* Updated readme.txt
* Tested with WP 4.8

= 0.0.4 = 
* Updated wp-geometa-lib
* Geometa-ACF is now using Leaflet-PHP to generate Leaflet code. Will allow for easier Leaflet maintenance going forward.
* Alpha feature *Bring Your Own Geocoder*. Define GEOMETA_ACF_BYOGC=true to enable and see media/js/geometa-acf.js for callbacks. More documentation coming when it comes out of alpha. 

= 0.0.3 = 
* Moved from including the WP-GeoMeta plugin to including wp-geometa-lib
* Fixed Contributor name for cimburacom

= 0.0.2 =
* Set up I18N support and Portuguese translation
* Code cleanup
* A start on code documentation

= 0.0.1 =
* Initial Release.
* Support for Map input, lat/lng fields and raw GeoJSON input
* Support for ACF v4 and ACF Pro/v5

== Upgrade Notice ==
= 0.0.3 = 
* You don't have GeoMeta for ACF yet, so there's no need to read this upgrade notice!
