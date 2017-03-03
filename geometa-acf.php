<?php
/**
 * GeoMeta for ACF provides a Geo metadata field for ACF
 *
 * GeoMeta for ACF uses the WP-GeoMeta library which allows you to 
 * use all the spatial funtions your database supports within WP_Query and 
 * other similar functions.
 *
 * Plugin Name: GeoMeta for ACF
 * Description: Store real spatial data with ACF, using the WP-GeoMeta library.
 * Plugin URI: https://github.com/cimburadotcom/geometa-acf
 * Author: Michael Moore
 * Author URI: http://cimbura.com
 * Version: 0.0.4
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: geometa-acf
 * Domain Path: /lang
 *
 * @package geometa-acf
 **/

// exit if accessed directly
defined( 'ABSPATH' ) or die( __( 'No direct access', 'geometa-acf' ) );

// check if class already exists
if( !class_exists('acf_plugin_geometa') ) {

	class acf_plugin_geometa {

		/*
		 *  __construct
		 *
		 *  This function will setup the class functionality
		 *
		 *  @type	function
		 *  @date	17/02/2016
		 *  @since	1.0.0
		 *
		 *  @param	n/a
		 *  @return	n/a
		 */
		function __construct() {
			// vars
			$this->settings = array(
				'version'	=> '0.0.2',
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ )
			);

			// set text domain
			// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
			load_plugin_textdomain( 'geometa-acf', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 

			// include field
			add_action('acf/include_field_types', 	array($this, 'include_field_types')); // v5
			add_action('acf/register_fields', 		array($this, 'include_field_types')); // v4

			// Include the WP-GeoMeta library
			if ( !file_exists( dirname( __FILE__ ) . '/wp-geometa-lib/wp-geometa-lib-loader.php' ) ) {
				error_log( __( "GeoMeta for ACF could not load wp-geometa-lib. You probably cloned wp-geometa from git and didn't check out submodules!", 'geometa-acf' ) );
				return false;
			}
			require_once( dirname( __FILE__ ) . '/wp-geometa-lib/wp-geometa-lib-loader.php' );

			if ( !file_exists( dirname( __FILE__ ) . '/leaflet-php/leaflet-php-loader.php' ) ) {
				error_log( __( "GeoMeta for ACF could not load leaflet-php. You probably cloned wp-geometa from git and didn't check out submodules!", 'geometa-acf' ) );
				return false;
			}
			require_once( dirname( __FILE__ ) . '/leaflet-php/leaflet-php-loader.php' );
		}


		/*
		 *  include_field_types
		 *
		 *  This function will include the field type class
		 *
		 *  @type	function
		 *  @date	17/02/2016
		 *  @since	1.0.0
		 *
		 *  @param	$version (int) major ACF version. Defaults to false
		 *  @return	n/a
		 */
		function include_field_types( $version = false ) {
			include_once('fields/geometa-acf.php');
		}
	}

	// initialize
	new acf_plugin_geometa();
}

/**
 * On activation make sure that ACF is present. 
 * If an admin disabled ACF later and leaves geometa-acf activated,
 * well, that's their business. It shouldn't actually break anything.
 */
function acf_geometa_activation_hook() {
	if ( !class_exists( 'acf' ) ) {
		wp_die( __( 'This plugin requires Advanced Custom Fields  or Advanced Custom Fields Pro. Please install and activate ACF first, then activate this plugin.', 'geometa-acf' ) );
	}

	if ( !file_exists( dirname( __FILE__ ) . '/wp-geometa-lib/wp-geometa-lib-loader.php' ) ) {
		wp_die( __( "GeoMeta for ACF could not load wp-geometa-lib. You probably cloned wp-geometa from git and didn't check out submodules!", 'geometa-acf' ) );
	}

	if ( !file_exists( dirname( __FILE__ ) . '/leaflet-php/leaflet-php-loader.php' ) ) {
		wp_die( __( "GeoMeta for ACF could not load leaflet-php. You probably cloned wp-geometa from git and didn't check out submodules!", 'geometa-acf' ) );
	}

	$wpgeo = WP_GeoMeta::get_instance();
	$wpgeo->create_geo_tables();
}
register_activation_hook( __FILE__ , 'acf_geometa_activation_hook' );
