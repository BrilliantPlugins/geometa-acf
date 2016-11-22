<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_geometa') ) :


	class acf_field_geometa extends acf_field {

		// vars
		var $settings, // will hold info such as dir / path
			$defaults; // will hold default field options


		/*
		 *  __construct
		 *
		 *  Set name / label needed for actions / filters
		 *
		 *  @since	3.6
		 *  @date	23/01/13
		 */

		function __construct( $settings )
		{
			// vars
			$this->name = 'geometa';
			$this->label = __('GeoMeta');
			$this->category = __("Basic",'acf'); // Basic, Content, Choice, etc
			$this->defaults = array(
				'user_input_type' => 'map'
			);


			// do not delete!
			parent::__construct();


			// settings
			$this->settings = $settings;

		}


		/*
		 *  create_options()
		 *
		 *  Create extra options for your field. This is rendered when editing a field.
		 *  The value of $field['name'] can be used (like below) to save extra data to the $field
		 *
		 *  @type	action
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$field	- an array holding all the field's data
		 */

		function create_options( $field )
		{
			// key is needed in the field names to correctly save the data
			$key = $field['name'];

			// Create Field Options HTML
			print '<tr class="field_option field_option_' . $this->name . '">';
			print '<td class="label"><label>' . __("Data Input Format",'acf') . '</label>';
			print '<p class="description">' . __("How should the user input location data?",'acf') . '</p>';
			print '</td><td>';

			do_action('acf/create_field', array(
				'type'		=>	'radio',
				'name'		=>	'user_input_type',
				'value'		=>	$field['user_input_type'],
				'layout'	=>	'vertical',
				'choices'	=>	array(
					'latlng'   => __('Latitude and Longitude','acf-geometa'),
					'map'      => __('A map with drawing tools','acf-geometa'),
					'geojson'  => __('GeoJSON text input','acf-geometa')
				)
			));

			print '</td></tr>';
		}


		/*
		 *  create_field()
		 *
		 *  Create the HTML interface for your field
		 *
		 *  @param	$field - an array holding all the field's data
		 *
		 *  @type	action
		 *  @since	3.6
		 *  @date	23/01/13
		 */

		function create_field( $field )
		{
			if ( $field[ 'user_input_type' ] == 'geojson' ) {
				echo '<textarea placeholder="Paste GeoJSON here" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '"></textarea>';
			} else if ( $field[ 'user_input_type' ] == 'latlng' ){

				$lat = '';
				$lng = '';
				if( !empty( $field['value'] ) ) {
					$json = json_decode( $field['value'], true );

					// Better safe than sorry?
					if ( 
						!empty( $json ) && 
						array_key_exists( 'type', $json ) && 
						$json['type'] === 'Feature' &&
						array_key_exists( 'geometry', $json ) && 
						is_array( $json['geometry'] ) &&
						array_key_exists( 'type', $json['geometry'] ) &&
						$json['geometry']['type'] === 'Point' &&
						array_key_exists('coordinates', $json['geometry']) &&
						is_array( $json['geometry']['coordinates'] ) 
					) {
						$lat = $json['geometry']['coordinates'][0];
						$lng = $json['geometry']['coordinates'][1];
					}
				}

				echo '<div class="acfgeometa_ll_wrap">';
				echo '<label>Latitude</label><input type="text" data-name="lat" value="' . $lat . '">';
				echo '<label>Longitude</label><input type="text" data-name="lng" value="' . $lng. '">';
				echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
				echo '</div>';
			} else if ( $field[ 'user_input_type' ] == 'map' ){
				$map_options = array();
				echo '<div class="acfgeometa_map_wrap">';
				echo '<div class="acfgeometa_map" data-map="' . htmlentities( json_encode( $map_options ) ) . '">The map is loading...</div>';
				echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
				echo '</div>';
			} else {
				echo "Sorry, {$field[ 'user_input_type' ]} input type isn't supported yet!\n";
			}
		}


		/*
		 *  input_admin_enqueue_scripts()
		 *
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 *  Use this action to add CSS + JavaScript to assist your create_field() action.
		 *
		 *  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 *  @type	action
		 *  @since	3.6
		 *  @date	23/01/13
		 */

		function input_admin_enqueue_scripts()
		{

			// Note: This function can be removed if not used

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


		/*
		 *  load_value()
		 *
		 *  This filter is applied to the $value after it is loaded from the db
		 *
		 *  @type	filter
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$value - the value found in the database
		 *  @param	$post_id - the $post_id from which the value was loaded
		 *  @param	$field - the field array holding all the field options
		 *
		 *  @return	$value - the value to be saved in the database
		 */
		function load_value( $value, $post_id, $field )
		{
			// Note: This function can be removed if not used
			return $value;
		}


		/*
		 *  format_value()
		 *
		 *  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
		 *
		 *  @type	filter
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$value	- the value which was loaded from the database
		 *  @param	$post_id - the $post_id from which the value was loaded
		 *  @param	$field	- the field array holding all the field options
		 *
		 *  @return	$value	- the modified value
		 */
		function format_value( $value, $post_id, $field )
		{
			// Note: This function can be removed if not used
			return $value;
		}


		/*
		 *  format_value_for_api()
		 *
		 *  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
		 *
		 *  @type	filter
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$value	- the value which was loaded from the database
		 *  @param	$post_id - the $post_id from which the value was loaded
		 *  @param	$field	- the field array holding all the field options
		 *
		 *  @return	$value	- the modified value
		 */

		function format_value_for_api( $value, $post_id, $field )
		{
			// Note: This function can be removed if not used
			return $value;
		}


		/*
		 *  load_field()
		 *
		 *  This filter is applied to the $field after it is loaded from the database
		 *
		 *  @type	filter
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$field - the field array holding all the field options
		 *
		 *  @return	$field - the field array holding all the field options
		 */

		function load_field( $field )
		{
			// Note: This function can be removed if not used
			return $field;
		}


		/*
		 *  update_field()
		 *
		 *  This filter is applied to the $field before it is saved to the database
		 *
		 *  @type	filter
		 *  @since	3.6
		 *  @date	23/01/13
		 *
		 *  @param	$field - the field array holding all the field options
		 *  @param	$post_id - the field group ID (post_type = acf)
		 *
		 *  @return	$field - the modified field
		 */

		function update_field( $field, $post_id )
		{
			$a = 1;
			$field['user_input_type'] = $_POST['user_input_type'];
			// Note: This function can be removed if not used
			return $field;
		}

	}


// initialize
new acf_field_geometa( $this->settings );


// class_exists check
endif;
