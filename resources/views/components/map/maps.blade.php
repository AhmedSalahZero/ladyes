
<input class="form-control" placeholder="{{ __('Search For Location') }}" id="{{ $searchTextField }}" ></input>
<div id="{{ $mapId }}" class="map__canvas rounded-[48px] {{ $mapHeight ?? '' }}" style="height: 350px;margin: 0.6em;"></div>
@push('js')

    @if(App()->getLocale() === 'ar')
        @once
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1pzxgf9AUfrWE2pLVQanO6Ti9a5lZDGo&libraries=places&region=eg&language=ar&sensor=true"></script>
        @endonce
    @else
        @once
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1pzxgf9AUfrWE2pLVQanO6Ti9a5lZDGo&libraries=places&region=eg&language=en&sensor=true"></script>
        @endonce

        @endif

    <!-- Google Map Plugin-->
    {{--    <script type="text/javascript" src="{{asset('frontend/js/gmap3.js')}}"></script>--}}

	
    <script>
  

        var longitude = "{{$longitude}}" ,
            latitude = "{{$latitude}}" ;

        $(function () {
			
            var lat = parseFloat(latitude) ,
                lng =parseFloat(longitude) ,
                latlng = new google.maps.LatLng(lat, lng),
                image = "{{ asset('marker.png') }}";
                //image = 'https://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
            //zoomControl: true,
            //zoomControlOptions: google.maps.ZoomControlStyle.LARGE,
            var mapOptions = {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 13,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    panControl: true,
                    panControlOptions: {
                        position: google.maps.ControlPosition.TOP_RIGHT
                    },
                    zoomControl: true,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.LARGE,
                        position: google.maps.ControlPosition.TOP_left
                    }
                },
                map = new google.maps.Map(document.getElementById('{{ $mapId }}'), mapOptions),
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: image
                });
            var input = document.getElementById('{{ $searchTextField }}');
            var autocomplete = new google.maps.places.Autocomplete(input, {
                types: ["geocode"]
            });
            autocomplete.bindTo('bounds', map);
            var infowindow = new google.maps.InfoWindow();
            // to make it read only [to show only the location]
			
			autocomplete.addListener("place_changed", () => {
    infowindow.close();
    marker.setVisible(false);

    const place = autocomplete.getPlace();

    if (!place.geometry || !place.geometry.location) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      window.alert("No details available for input: '" + place.name + "'");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }

    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
	 const infowindow2 = new google.maps.InfoWindow();
	 const infowindowContent= `<div id="infowindow-content">
      <span id="place-name" class="title">${place.name}</span><br />
      <span id="place-address">${place.formatted_address}</span>
    </div>`;
	infowindow2.setContent(infowindowContent)

    //infowindow2.open(map, marker);
  });
  
            google.maps.event.addListener(map, 'click', function(event) {
				//console.log(event.latLng)
                //setMapOnAll(null);
                //placeMarker(event.latLng);
            });
            // to make it insert
            // google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
            //     infowindow.close();
            //     var place = autocomplete.getPlace();
            //     if (place.geometry.viewport) {
            //         map.fitBounds(place.geometry.viewport);
            //     } else {
            //         map.setCenter(place.geometry.location);
            //         map.setZoom(17);
            //     }
            //     moveMarker(place.name, place.geometry.location);
            //     $('.MapLat').val(place.geometry.location.lat());
            //     $('.MapLon').val(place.geometry.location.lng());
            // });
            // to make it for show only [to show the map only] [which can not be edit by any user ];
			
			//marker.addListener('click', function() {
			//	const link = 'https://maps.app.goo.gl/5tVwZWKJDb9Ni9u99';
			//	window.open(link, '_blank')
            //});
			
            google.maps.event.addListener(map, 'click', function (event) {
                $('.MapLat').val(event.latLng.lat());
                $('.MapLon').val(event.latLng.lng());
				
                infowindow.close();
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    "latLng":event.latLng
                }, function (results, status) {
                    // console.log(results, status);
                    if (status == google.maps.GeocoderStatus.OK) {
                        // console.log(results);
                        var lat = results[0].geometry.location.lat(),
                            lng = results[0].geometry.location.lng(),
                            placeName = results[0].address_components[0].long_name,
                            latlng = new google.maps.LatLng(lat, lng);
                        moveMarker(placeName, latlng);
						var fullAddressName = results[1].address_components[0].long_name +'-'+results[1].address_components[1].long_name ;
						$('.map_name').val(fullAddressName)
                       // $("#{{ $searchTextField }}").val(results[0].formatted_address);
                    }
                });
            });

            function moveMarker(placeName, latlng) {
                marker.setIcon(image);
                marker.setPosition(latlng);
                infowindow.setContent(placeName);
                //infowindow.open(map, marker);
            }
        });
    </script>


@endpush
