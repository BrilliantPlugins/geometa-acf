(function($){
	function initialize_field( $el ) {

		// jQuery('.acfgeometa_map_wrap').each( make_maps );
		jQuery('.acfgeometa_ll_wrap').on( 'keyup change', make_ll_to_geojson );
	}

	function make_maps(i,wrapperdiv) {
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

jQuery(document).ready(function(){
	jQuery('.acfgeometa_geocode_button').on('click',function(e){
		e.preventDefault();
		e.stopPropagation();

		e.target.disabled = true;

		var origE = e;
		var callback = function(success){

			success = success || '';

			if ( typeof success === 'object' && success.hasOwnProperty('geometry') && success.hasOwnProperty('type') && success.type == 'Feature' ) {
				// We've got GeoJSON, call it a success	
				jQuery(origE.target).addClass('has_geojson');
				success = JSON.stringify( success );
			} else {
				// No GeoJSON, call it failure
				jQuery(origE.target).removeClass('has_geojson');
				success = '';
			}

			jQuery(origE.target.parentElement).find('input[data-name="geojson"]').val( success );

			origE.target.disabled = false;
		};

		jQuery(document).trigger('geometa-acf/byo-geocode', [
			e, callback
		]);

		return false;
	});
});
