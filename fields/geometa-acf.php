<?php
/**
 * This class should be compatible with both ACF v4 and v5.
 * @package geometa-acf
 */

// exit if accessed directly
defined( 'ABSPATH' ) or die( __( 'No direct access', 'geometa-acf' ) );

// check if class already exists
if( !class_exists('acf_field_geometa') ) {

	class acf_field_geometa extends acf_field {

		// vars
		var $settings, // will hold info such as dir / path
			$defaults; // will hold default field options

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
			$this->label = 'GeoMeta';

			/*
			 *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */
			$this->category = __('basic','acf');


			$this->defaults = array(
				'user_input_type' => 'map'
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
			acf_render_field_setting( $field, array(
				'label'            => __('Data Input Format','acf'),
				'instructions'    => __('How should the user input location data?','geometa-acf'),
				'type'            => 'radio',
				'name'            => 'user_input_type',
				'layout'        => 'vertical',
				'choices'        => array(
					'latlng'            => __('Latitude and Longitude','geometa-acf'),
					'map'            => __('A map with drawing tools','geometa-acf'),
					'geojson'            => __('GeoJSON text input','geometa-acf'),
					'byo-geocoder'  => __('Bring Your Own Geocoder','geometa-acf'),
				)
			));
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
		function create_field( $field ) {
			return $this->render_field( $field );
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
			$a = 1;
			if ( $field[ 'user_input_type' ] == 'geojson' ) {
				echo '<textarea placeholder="' . esc_attr__( 'Paste GeoJSON here', 'geometa-acf' ) . '" name="' . esc_attr($field['name']) . '" >' . esc_attr($field['value']) . '</textarea>';
			} else if ( $field[ 'user_input_type' ] == 'latlng' ) {
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
						$lat = $json['geometry']['coordinates'][1];
						$lng = $json['geometry']['coordinates'][0];
					}
				}

				echo '<div class="acfgeometa_ll_wrap">';
				echo '<label>' . esc_html__('Latitude','geometa-acf') . ' </label><br><input type="text" data-name="lat" value="' . $lat . '"><br>';
				echo '<label>' . esc_html__('Longitude','geometa-acf') . ' </label><br><input type="text" data-name="lng" value="' . $lng. '"><br>';
				echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
				echo '</div>';
			} else if ( $field[ 'user_input_type' ] == 'map' ) {
				$map_options = array();
				echo '<div class="acfgeometa_map_wrap">';
				echo '<div class="acfgeometa_map" data-map="' . htmlentities( json_encode( $map_options ) ) . '">The map is loading...</div>';
				echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
				echo '</div>';
			} else if ( $field[ 'user_input_type' ] = 'byo-geocoder' ) {
					echo '<div class="acfeometa_geocode_wrap">';

						$class = '';
						if ( WP_GeoUtil::is_geojson( $field['value'] ) ) {
							$class = ' has_geojson';
						}

						echo '<button class="acfgeometa_geocode_button' . $class . '">Geocode</button>';
						echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
					echo '</div>';
			} else {
				echo sprintf( esc_html__( 'Sorry, %1$s input type isn\'t supported yet!', 'geometa-acf' ), $field[ 'user_input_type' ] )  . "\n";
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
		function input_admin_enqueue_scripts() {

			// vars
			$url = $this->settings['url'];
			$version = $this->settings['version'];


			// register & include JS
			wp_register_script( 'acf-input-geometa-leaflet1-js', "{$url}assets/js/leaflet.js", array(), $version );
			wp_register_script( 'acf-input-geometa-leaflet-draw-js', "{$url}assets/Leaflet.draw/leaflet.draw.js", array('acf-input-geometa-leaflet1-js'), $version );
			wp_register_script( 'acf-input-geometa-leaflet-locate-control-js', "{$url}assets/js/L.Control.Locate.min.js", array('acf-input-geometa-leaflet1-js'), $version );
			wp_register_script( 'acf-input-geometa', "{$url}assets/js/geometa-acf.js", array('acf-input','acf-input-geometa-leaflet1-js', 'acf-input-geometa-leaflet-locate-control-js','acf-input-geometa-leaflet-draw-js'), $version );

			wp_enqueue_script('acf-input-geometa');

			// register & include CSS
			wp_register_style( 'acf-input-geometa-leaflet1-css', "{$url}assets/css/leaflet.css", array(), $version );
			wp_register_style( 'acf-input-geometa-leaflet-locate-control-css', "{$url}assets/css/L.Control.Locate.min.css", array('acf-input-geometa-leaflet1-css'), $version );
			wp_register_style( 'acf-input-geometa-leaflet-draw-css', "{$url}assets/Leaflet.draw/leaflet.draw.css", array('acf-input-geometa-leaflet1-css'), $version );
			wp_register_style( 'acf-input-geometa', "{$url}assets/css/geometa-acf.css", array('acf-input','acf-input-geometa-leaflet-draw-css'), $version );

			wp_enqueue_style('acf-input-geometa');
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
		function create_options( $field ) {
			// key is needed in the field names to correctly save the data
			$key = $field['key'];

			// Create Field Options HTML
			print '<tr class="field_option field_option_' . $this->name . '">';
			print '<td class="label"><label>' . __("Data Input Format",'acf') . '</label>';
			print '<p class="description">' . __("How should the user input location data?",'geometa-acf') . '</p>';
			print '</td><td>';

			do_action('acf/create_field', array(
				'type'		=>	'radio',
				'name'		=>	$field['name'],
				'value'		=>	$field['user_input_type'],
				'layout'	=>	'vertical',
				'choices'	=>	array(
					'latlng'   => __('Latitude and Longitude','geometa-acf'),
					'map'      => __('A map with drawing tools','geometa-acf'),
					'geojson'  => __('GeoJSON text input','geometa-acf'),
					'byo-geocoder'  => __('Bring Your Own Geocoder','geometa-acf'),
				)
			));

			print '</td></tr>';
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
		/*
		function update_field( $field )
		{
			if ( !empty( $_POST[ $field[ 'key' ]  ] ) ) {
				$field['user_input_type'] = $_POST[ $field['key'] ];
			}
			return $field;
		}
*/
	}

	// initialize
	new acf_field_geometa( $this->settings );
}
