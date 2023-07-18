<div>
    <style>
        #map-container {
            display: flex;
            background-color: transparent;
        }

        #customer-list-container {
            position: relative;
            width: 200px;
            background-color: transparent;
        }

        #customer-list {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 250px;
            background-color: transparent;
            overflow-y: auto;
        }

        .hidden {
            display: none;
        }


        #customer-counter {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #ccc;
            color: #333;
            padding: 4px 8px;
            font-size: 12px;
        }

        #search-input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }


        #map {
            flex: 1;
            width: 100%;
            height: 800px;
        }
    </style>
    <button id="toggleButton" class="btn btn-primary">Toggle List</button>
    <div id="map-container">
        <div id="customer-list-container" style="display: block;">
            <input id="search-input" type="text" placeholder="Search">
            <ul id="customer-list">
                @foreach ($markersByTitle as $title => $markers)
                    {{-- <li class="customer-title">
                        <strong>{{ $title }}</strong>
                    </li> --}}
                    @foreach ($markers as $marker)
                        <li class="customer-item" data-latitude="{{ $marker['lat'] }}"
                            data-longitude="{{ $marker['lng'] }}" data-userCode="{{ $marker['user_code'] }}"
                            wire:click="plotMarkers('{{ $marker['user_code'] }}')">
                            <strong>{{ $marker['title'] }}</strong>
                        </li>
                    @endforeach
                @endforeach
            </ul>
            <div id="customer-counter">Total: {{ count($markersByTitle) }}</div>
        </div>
        <div wire:ignore id="map" style="width:100%; height:800px"></div>
    </div>
    <script>
        const baseUrl = "{{ url('/') }}";
    </script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
    </script>
    <!-- JavaScript code -->
    <script>
        const toggleButton = document.getElementById('toggleButton');
        const customerListContainer = document.getElementById('customer-list-container');

        const stand = toggleButton.addEventListener('click', function() {
            const displayStyle = customerListContainer.style.display;
            customerListContainer.style.display = (displayStyle === 'none') ? 'block' : 'none';
            console.log(displayStyle);
        });
        let map, activeInfoWindow, markers = [];
        let markerPositions = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: -1.292066,
                    lng: 36.821945
                },
                zoom: 12,
                mapTypeId: '{{ $typeMap }}'
            });

            initMarkers();
        }

        function initMarkers() {
            const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
            const pinImage = new google.maps.MarkerImage("{{ asset('app-assets/images/pin.png') }}", null, null, null,
                new google.maps.Size(40, 52));

            initialMarkers.forEach((markerData, index) => {
                const marker = new google.maps.Marker({
                    position: markerData.position,
                    icon: pinImage,
                    map: map,
                    label: markerData.PlotNumber
                });
                markers.push(marker);

                const infowindow = new google.maps.InfoWindow({
                    content: `
          <div id="content">
            <div id="siteNotice"> </div>
            <img src="{{ asset('app-assets/images/logo2.jpeg') }}" alt="avatar" height="50" />
            <h1 id="firstHeading" class="firstHeading">${markerData.title}</h1>
            <div id="bodyContent">
              <p><b>Location: </b>${markerData.position.lat}, ${markerData.position.lng}</p>
              <p><b>Name: </b>${markerData.title}</p>
              <p><b>Battery Level: </b>${markerData.battery}</p>
              <p><b>Android Version: </b>${markerData.android_version}</p>
              <p><b>IMEI: </b>${markerData.IMEI}</p>
              <p><b>Time: </b>${markerData.description}</p>
              <p><b>More info: </b><a href="{{ URL('/users/${markerData.user_code}/edit') }}">${markerData.title}</a></p>
            </div>
          </div>
        `
                });

                marker.addListener("click", () => {
                    if (activeInfoWindow) {
                        activeInfoWindow.close();
                    }
                    infowindow.open({
                        anchor: marker,
                        shouldFocus: false,
                        map
                    });
                    activeInfoWindow = infowindow;
                });


            });
        }

        //   const userCode = item.getAttribute('data-userCode');
        // JavaScript event listener to handle clicks on user list items
        document.addEventListener('livewire:load', function() {
            // Handle clicks on user list items
            const customerItems = document.querySelectorAll('.customer-item');
            customerItems.forEach(item => {
                item.addEventListener('click', function() {
                    const latitude = parseFloat(item.getAttribute('data-latitude'));
                    const longitude = parseFloat(item.getAttribute('data-longitude'));
                    map.setCenter({
                        lat: -1.292066,
                        lng: 36.821945
                    });
                    map.setZoom(7);

                    // Fetch marker data for the selected userCode
                    const userCode = item.getAttribute('data-userCode');
                    fetch(`${baseUrl}/api/getMarkers/${encodeURIComponent(userCode)}`)
                        .then(response => response.json())
                        .then(data => {
                            // Close any existing active info window
                            if (activeInfoWindow) {
                                activeInfoWindow.close();
                            }

                            // Remove existing markers and routes from the map
                            markers.forEach(marker => marker.setMap(null));
                            markers = [];
                            if (markerPositions.length > 1) {
                                markerPositions.forEach(markerPosition => {
                                    markerPosition.setMap(null);
                                });
                            }

                            // Plot new markers on the map and create info window for the clicked marker
                            data.forEach((markerData, index) => {
                                const marker = new google.maps.Marker({
                                    position: {
                                        lat: markerData.lat,
                                        lng: markerData.lng
                                    },
                                    map: map
                                });
                                markers.push(marker);

                                const infowindow = new google.maps.InfoWindow({
                                    content: `
                                    <div id="content">
                                        <div id="siteNotice"> </div>
                                        <img src="{{ asset('app-assets/images/logo2.jpeg') }}" alt="avatar" height="50" />
                                        <h1 id="firstHeading" class="firstHeading">${markerData.title}</h1>
                                        <div id="bodyContent">
                                            <p><b>Location: </b>${markerData.lat}, ${markerData.lng}</p>
                                            <p><b>Name: </b>${markerData.title}</p>
                                            <p><b>Battery Level: </b>${markerData.battery}</p>
                                            <p><b>Android Version: </b>${markerData.android_version}</p>
                                            <p><b>IMEI: </b>${markerData.IMEI}</p>
                                            <p><b>Time: </b>${markerData.description}</p>
                                            <p><b>More info: </b><a href="{{ URL('/users/${markerData.user_code}/edit') }}">${markerData.title}</a></p>
                                        </div>
                                    </div>
                                `
                                });

                                marker.addListener("click", () => {
                                    if (activeInfoWindow) {
                                        activeInfoWindow.close();
                                    }
                                    infowindow.open(map, marker);
                                    activeInfoWindow = infowindow;

                                    // Pan the map to the clicked marker
                                    map.panTo(marker.position);
                                    map.setZoom(15);
                                });

                                // Store the marker position in the array
                                markerPositions.push(marker.getPosition());
                            });

                            // Draw routes for markers if we have more than one marker
                            if (markerPositions.length > 1) {
                                const directionsService = new google.maps.DirectionsService();
                                const directionsDisplay = new google.maps.DirectionsRenderer({
                                    suppressMarkers: true,
                                    preserveViewport: true,
                                    map: map
                                });

                                const waypoints = markerPositions.slice(1, -1).map(position =>
                                    ({
                                        location: position,
                                        stopover: true
                                    }));

                                const request = {
                                    origin: markerPositions[0],
                                    destination: markerPositions[markerPositions.length -
                                        1],
                                    waypoints: waypoints,
                                    travelMode: google.maps.TravelMode.DRIVING
                                };

                                directionsService.route(request, function(result, status) {
                                    if (status === google.maps.DirectionsStatus.OK) {
                                        directionsDisplay.setDirections(result);
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                });
            });
        });
    </script>
</div>
