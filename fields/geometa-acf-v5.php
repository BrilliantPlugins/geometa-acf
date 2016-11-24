<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_geometa') ) :


	class acf_field_geometa extends acf_field {


		/*
		 *  __construct
		 *
		 *  This function will setup the field type data
		 *
		 *  @type	function
		 *  @date	5/03/2014
		 *  @since	5.0.0
		 *
		 *  @param	n/a
		 *  @return	n/a
		 */

		function __construct( $settings ) {

			/*
			 *  name (string) Single word, no spaces. Underscores allowed
			 */

			$this->name = 'geometa';


			/*
			 *  label (string) Multiple words, can include spaces, visible when selecting a field type
			 */

			$this->label = __('GeoMeta', 'acf-geometa');


			/*
			 *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */

			$this->category = 'basic';


			/*
			 *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */

			$this->defaults = array(
				'font_size'	=> 14,
			);


			/*
			 *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
			 *  var message = acf._e('geometa', 'error');
			 */

			$this->l10n = array(
				'error'	=> __('Error! Please enter a higher value', 'acf-geometa'),
			);


			/*
			 *  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
			 */

			$this->settings = $settings;


			// do not delete!
			parent::__construct();

		}


		/*
		 *  render_field_settings()
		 *
		 *  Create extra settings for your field. These are visible when editing a field
		 *
		 *  @type	action
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$field (array) the $field being edited
		 *  @return	n/a
		 */

		function render_field_settings( $field ) {

			/*
			 *  acf_render_field_setting
			 *
			 *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
			 *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
			 *
			 *  More than one setting can be added by copy/paste the above code.
			 *  Please note that you must also have a matching $defaults value for the field name (font_size)
			 */

			// layout
			$a = 1;
			acf_render_field_setting( $field, array(
				'label'            => __('Data Input Format','acf'),
				'instructions'    => __('How should the user input location data?','acf-geometa'),
				'type'            => 'radio',
				'name'            => 'user_input_type',
				'layout'        => 'vertical',
				'choices'        => array(
					'latlng'            => __('Latitude and Longitude','acf-geometa'),
					'map'            => __('A map with drawing tools','acf-geometa'),
					'geojson'            => __('GeoJSON text input','acf-geometa')
				)
			));

		}



		/*
		 *  render_field()
		 *
		 *  Create the HTML interface for your field
		 *
		 *  @param	$field (array) the $field being rendered
		 *
		 *  @type	action
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$field (array) the $field being edited
		 *  @return	n/a
		 */

		function render_field( $field ) {
			include( dirname( __FILE__ ) . '/geometa-acf-common.php' );
		}


		/*
		 *  input_admin_enqueue_scripts()
		 *
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 *  Use this action to add CSS + JavaScript to assist your render_field() action.
		 *
		 *  @type	action (admin_enqueue_scripts)
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	n/a
		 *  @return	n/a
		 */


		function input_admin_enqueue_scripts() {

			// vars
			$url = $this->settings['url'];
			$version = $this->settings['version'];


			// register & include JS
			wp_register_script( 'acf-input-geometa-leaflet1-js', "{$url}assets/js/leaflet.js", array(), $version );
			wp_register_script( 'acf-input-geometa-leaflet-draw-js', "{$url}assets/Leaflet.draw/leaflet.draw.js", array('acf-input-geometa-leaflet1-js'), $version );
			wp_register_script( 'acf-input-geometa-leaflet-locate-control-js', "{$url}assets/js/L.Control.Locate.min.js", array('acf-input-geometa-leaflet1-js'), $version );
			wp_register_script( 'acf-input-geometa', "{$url}assets/js/input.js", array('acf-input','acf-input-geometa-leaflet1-js', 'acf-input-geometa-leaflet-locate-control-js','acf-input-geometa-leaflet-draw-js'), $version );

			wp_enqueue_script('acf-input-geometa');


			// register & include CSS
			wp_register_style( 'acf-input-geometa-leaflet1-css', "{$url}assets/css/leaflet.css", array(), $version );
			wp_register_style( 'acf-input-geometa-leaflet-locate-control-css', "{$url}assets/css/L.Control.Locate.min.css", array('acf-input-geometa-leaflet1-css'), $version );
			wp_register_style( 'acf-input-geometa-leaflet-draw-css', "{$url}assets/Leaflet.draw/leaflet.draw.css", array('acf-input-geometa-leaflet1-css'), $version );
			wp_register_style( 'acf-input-geometa', "{$url}assets/css/input.css", array('acf-input','acf-input-geometa-leaflet-draw-css'), $version );

			wp_enqueue_style('acf-input-geometa');
		}

	}


// initialize
new acf_field_geometa( $this->settings );


// class_exists check
endif;
