<?php
if ( $field[ 'user_input_type' ] == 'geojson' ) {
	echo '<textarea placeholder="Paste GeoJSON here" name="' . esc_attr($field['name']) . '" >' . esc_attr($field['value']) . '</textarea>';
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
	echo '<label>Latitude </label><br><input type="text" data-name="lat" value="' . $lat . '"><br>';
	echo '<label>Longitude </label><br><input type="text" data-name="lng" value="' . $lng. '"><br>';
	echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
	echo '</div>';
} else if ( $field[ 'user_input_type' ] == 'map' ) {
	$map_options = array();
	echo '<div class="acfgeometa_map_wrap">';
	echo '<div class="acfgeometa_map" data-map="' . htmlentities( json_encode( $map_options ) ) . '">The map is loading...</div>';
	echo '<input type="hidden" data-name="geojson" name="' . esc_attr($field['name']) . '" value="' . esc_attr($field['value']) . '">';
	echo '</div>';
} else {
	echo "Sorry, {$field[ 'user_input_type' ]} input type isn't supported yet!\n";
}
