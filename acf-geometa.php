<?php
/*
Plugin Name: Advanced Custom Fields: GeoMeta
Plugin URI: https://bitbucket.org/cimburacom/acf-geometa
Description: Store real spatial data with ACF, using the WP-GeoMeta library.
Version: 0.0.1
Author: Cimbura.com
Author URI: http://cimbura.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_plugin_geometa') ) :

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
			'version'	=> '0.0.1',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);
		
		
		// set text domain
		// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
		load_plugin_textdomain( 'acf-geometa', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field_types')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field_types')); // v4
		
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
		
		// support empty $version
		if( !$version ) $version = 4;
		
		
		// include
		include_once('fields/acf-geometa-v' . $version . '.php');
		
	}
	
}


// initialize
new acf_plugin_geometa();


// class_exists check
endif;

require_once( dirname( __FILE__ ) . '/wp-geometa/wp-geometa.php' );

function acf_geometa_activation_hook() {
	$wpgeo = WP_GeoMeta::get_instance();
	$wpgeo->create_geo_tables();
}
register_activation_hook( __FILE__ , 'acf_geometa_activation_hook' );
