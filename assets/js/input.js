(function($){
	function initialize_field( $el ) {

		jQuery('.acfgeometa_map_wrap').each( make_maps );

		jQuery('.acfgeometa_ll_wrap').on( 'keyup change', make_ll_to_geojson );
	}

	function make_maps(i,wrapperdiv) {
		var div = jQuery(wrapperdiv).find('.acfgeometa_map');
		if ( div.data( 'map_loaded' ) === true ) {
			return;
		}
		div = div[0];
		div.innerHTML = '';
		var map = L.map(div).setView([0,0],1);

		// Basemap
		L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map);

		// Location control
		L.control.locate({ 
			icon: 'pointer_marker',
			iconLoading: 'pointer_marker_loading'
		}).addTo(map);

		// Draw control layer
		var curgeojson = '';
		var drawnItems = new L.GeoJSON();
		try {
			curgeojson = JSON.parse(jQuery(this).find('input[data-name="geojson"]').val());
			drawnItems.addData(curgeojson);
		} catch(e) {}
		map.addLayer( drawnItems );

		// Draw control
		var drawControl = new L.Control.Draw({
			draw: {
				circle: false
			},
			edit: {
				featureGroup: drawnItems
			}
		});

		map.addControl( drawControl );

		// Make a function that will have access to drawnItems.
		var savevalfunc = (function(thegeojson){
			return function(){
				thegeojson.val( JSON.stringify( drawnItems.toGeoJSON() ) );
			};
		})(jQuery(this).find('input[data-name="geojson"]'));

		map.on('draw:created', function (e) {
			drawnItems.addLayer(e.layer);
			savevalfunc(e);
		});

		map.on( 'draw:edited', savevalfunc );
		map.on( 'draw:deleted', savevalfunc );

		// If we have existing geojson, fit bounds
		if ( drawnItems.getLayers().length > 0 ) {
			map.fitBounds(drawnItems.getBounds());
		}

		jQuery( div ).data( 'map_loaded', true );
	}

	function make_ll_to_geojson(e){
		var lat = jQuery(this).find('input[data-name="lat"]').val();
		var lng = jQuery(this).find('input[data-name="lng"]').val();

		if ( (parseFloat( lat ) + "") !== lat || (parseFloat( lng ) + "") !== lng ) {
			// Something's not numeric!
			return;
		}

		if ( lng < -180 || lng > 180 || lat > 90 || lat < -90 ) {
			// Out of range!
			return;
		}

		var geojson = {
			"type" : "Feature",
			"geometry" : { 
				"type" : "Point",
				"coordinates" : [ parseFloat(lng), parseFloat(lat) ]
			},
			"properties" : {}
		};

		jQuery(this).find('input[data-name="geojson"]').val(JSON.stringify(geojson));
	}

	if( typeof acf.add_action !== 'undefined' ) {
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/

		acf.add_action('ready append', function( $el ){

			// search $el for fields of type 'geometa'
			acf.get_fields({ type : 'geometa'}, $el).each(function(){

				initialize_field( $(this) );

			});

		});
	} else {
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){
			$(postbox).find('.field[data-field_type="geometa"]').each(function(){
				initialize_field( $(this) );
			});
		});
	}
})(jQuery);
