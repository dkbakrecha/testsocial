  <style>
		#map {
        	height: 300px;
      	}

  </style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
  <div id="map"></div>

<?php
	echo $this->Form->create('Test');

	echo $this->Form->input('city');

	echo $this->Form->end();	
?>
  


<script>

	//GOOGLE AUTO COMPLETE FIELD
	if(google)
	google.maps.event.addDomListener(window, 'load', initialize);
		
	var placeSearch, autocomplete;
	
	function initialize() {
		/* MAP Concept */
		var map = new google.maps.Map(document.getElementById('map'), {
		    center: {lat: -33.8688, lng: 151.2195},
		    zoom: 13
		  });

		

		// Create the autocomplete object, restricting the search
		// to geographical location types.
		autocomplete = new google.maps.places.Autocomplete(
				/** @type {HTMLInputElement} */(document.getElementById('TestCity')),
				{ types: ['geocode'] });
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			infowindow.close();
    		marker.setVisible(false);
    		var place = autocomplete.getPlace();
    		console.log(place);
    		if (!place.geometry) {
      			window.alert("Autocomplete's returned place contains no geometry");
      			return;
    		}

    		// If the place has a geometry, then present it on a map.
    		if (place.geometry.viewport) {
      			map.fitBounds(place.geometry.viewport);
    		} else {
      			map.setCenter(place.geometry.location);
      			map.setZoom(17);  // Why 17? Because it looks good.
    		}

    		marker.setIcon(/** @type {google.maps.Icon} */({
      			url: place.icon,
      			size: new google.maps.Size(71, 71),
      			origin: new google.maps.Point(0, 0),
      			anchor: new google.maps.Point(17, 34),
      			scaledSize: new google.maps.Size(35, 35)
    		}));

    		marker.setPosition(place.geometry.location);
    		marker.setVisible(true);

    		var address = '';
    		if (place.address_components) {
      			address = [
        			(place.address_components[0] && place.address_components[0].short_name || ''),
        			(place.address_components[1] && place.address_components[1].short_name || ''),
        			(place.address_components[2] && place.address_components[2].short_name || '')
      			].join(' ');
    		}

    		infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    		infowindow.open(map, marker);
  


			/*fillInAddress();
			var place = autocomplete.getPlace();
			document.getElementById('UserCity').value = place.name;*/
		});



		autocomplete.bindTo('bounds', map);

		var infowindow = new google.maps.InfoWindow();
		  var marker = new google.maps.Marker({
		    map: map,
		    anchorPoint: new google.maps.Point(0, -29)
		});



		
	}
</script>