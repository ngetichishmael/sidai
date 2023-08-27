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
    <style>
        /* Style for the customer list */
        #customer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Style for each customer item */
        .customer-item {
            padding: 5px;
            padding-left: 10px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .customer-item:hover {
            background-color: #f2f2f2;
        }

        /* Style for the customer list container */
        #customer-list-container {
            max-height: 800px;
            /* Adjust this value to set the maximum height of the customer list */
            max-width: 200px;
            overflow-y: auto;
        }

        #toggleButton {
            float: right;
            margin-top: 5px;
        }

        #customer-counter {
            float: right;
            margin-top: 12px;
            margin-right: 10px;
            font-size: 14px;
        }
    </style>

    <button id="toggleButton" class="btn btn-primary" hidden>Toggle List</button>
    <div class="card">
        <h5 class="card-header">Search Filter</h5>
        <div id="map-container">
            <div id="customer-list-container" style="none">
                <div style="position: relative; background-color: transparent;" class="ml-2">
                    <div class="form-group" style="padding:8px">
                        <input id="search-input" class="form-control form-control-sm" type="text"
                            placeholder="Search Agents" />

                        <!-- <span id="customer-counter"></span> -->
                    </div>

                    <br>

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

                </div>
                <div id="customer-counter">Total: {{ count($markersByTitle) }}</div>
            </div>
            <div wire:ignore id="map" style="width: 100%; height: 800px;"></div>
        </div>


        <script>
            const baseUrl = "{{ url('/') }}";
        </script>
        <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
        </script>
        <!-- JavaScript code -->
        <script>
            // Get references to the search input and customer list
            const searchInput = document.getElementById('search-input');
            const customerList = document.getElementById('customer-list');

            // Add event listener to the search input
            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase(); // Convert search term to lowercase

                // Loop through each customer item
                const customerItems = customerList.getElementsByClassName('customer-item');
                for (const item of customerItems) {
                    const title = item.querySelector('strong').textContent.toLowerCase(); // Get the title content

                    // Show/hide the item based on whether it matches the search term
                    if (title.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
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
            <img src="{{ asset('app-assets/images/small_logo.png') }}" alt="avatar" height="50" />
            <h1 id="firstHeading" class="firstHeading">${markerData.title}</h1>
            <div id="bodyContent">
                <p><b>Location: </b>${markerData.position.lat}, ${markerData.position.lng}</p>
                <p><b>Name: </b>${markerData.title}</p>
                <p><b>Battery Level: </b>${markerData.battery}</p>
                <p><b>Android Version: </b>${markerData.android_version}</p>
                <p><b>IMEI: </b>${markerData.IMEI}</p>
                <p><b>Time: </b>${markerData.description}</p>
                <p><b>More info: </b><a href="{{ URL('/users/${markerData.user_code}/edit') }}">${markerData.title}</a>
                </p>
            </div>
        </div> `
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

            // const userCode = item.getAttribute('data-userCode');
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
                                        //  markerPosition.setMap(null);
                                        // Clear previous markers
                                        markers.forEach(marker => marker.setMap(null));

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
                                                   <img src="{{ asset('app-assets/images/small_logo.png') }}" alt="avatar" height="50" />
                                                   <h1 id="firstHeading" class="firstHeading">${markerData.title}</h1>
                                                   <div id="bodyContent">
                                                      <p><b>Location: </b>${markerData.lat}, ${markerData.lng}</p>
                                                      <p><b>Name: </b>${markerData.title}</p>
                                                      <p><b>Battery Level: </b>${markerData.battery}</p>
                                                      <p><b>Android Version: </b>${markerData.android_version}</p>
                                                      <p><b>IMEI: </b>${markerData.IMEI}</p>
                                                      <p><b>Time: </b>${markerData.description}</p>
                                                      <p><b>More info: </b><a href="{{ URL('/users/${markerData.user_code}/edit') }}">${markerData.title}</a>
                                                      </p>
                                                   </div>
                                             </div>`
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
                                    markerPositions.push(marker.position);
                                });

                                // Draw routes for markers if we have more than one marker
                                if (markerPositions.length > 1) {
                                    const directionsService = new google.maps.DirectionsService();
                                    const directionsDisplay = new google.maps.DirectionsRenderer({
                                        suppressMarkers: true,
                                        preserveViewport: true,
                                        map: map
                                    });

                                    const maxWaypoints = 25;
                                    const numSegments = Math.ceil((markerPositions.length - 2) /
                                        maxWaypoints);

                                    for (let i = 0; i < numSegments; i++) {
                                        const start = i * maxWaypoints;
                                        const end = start + maxWaypoints;

                                        // Determine the origin, destination, and waypoints for this segment
                                        const origin = (i === 0) ? markerPositions[0] :
                                            markerPositions[start];
                                        const destination = (i === numSegments - 1) ?
                                            markerPositions[markerPositions.length - 1] :
                                            markerPositions[end];
                                        const waypoints = markerPositions.slice(start + 1, end).map(
                                            position => ({
                                                location: position,
                                                stopover: true
                                            }));

                                        const request = {
                                            origin: origin,
                                            destination: destination,
                                            waypoints: waypoints,
                                            travelMode: google.maps.TravelMode.DRIVING
                                        };

                                        directionsService.route(request, function(result, status) {
                                            if (status === google.maps.DirectionsStatus
                                                .OK) {
                                                directionsDisplay.setDirections(result);
                                            }
                                        });
                                    }
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
    <br>
