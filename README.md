# ACF GeoMeta Field

Store real spatial data with ACF, using the WP-GeoMeta library.

---------------------------------------------------------------------------

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

### Compatibility

This ACF field type is compatible with:
* ACF 5
* ACF 4

### Installation

Install this plugin in the usual WordPress way, then go to your WordPress
dashboard to Tools::WP GeoMeta to see the status of your spatial data and
to use the included tools.

1. Upload the plugin files to the `/wp-content/plugins/geometa-acf` directory,
	or install the plugin through the WordPress plugin screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Create a new field via ACF and select the GeoMeta type.

### Usage

At its most basic, you can use this like you would any other ACF field. Add it to a post type, edit a post and add values to the GeoMeta for ACF field, etc.

To unlock this plugin's full potential you can query and filter your data using MySQL's spatial functions. This plugin uses WP-GeoMeta, which means that you can make these spatial right within WP_Query, wp_get_posts, and anything els that use [WP_Meta_Query internally](https://codex.wordpress.org/Class_Reference/WP_Meta_Query). 

For sample queries and more information, please see the [WP-GeoMeta
documentation](https://github.com/cimburadotcom/wp-geometa);
