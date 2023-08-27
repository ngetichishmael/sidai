<div>
    <style>
            #map-container {
                display: flex;
            }
    
            #customer-list-container {
                position: relative;
                width: 250px;
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
                top: 5;
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
            max-height: 800px; /* Adjust this value to set the maximum height of the customer list */
            max-width: 200px;
            overflow-y: auto;
        }
    
        #toggleButton {
            float: right;
            margin-top: 5px;
        }
    
        #customer-counter {
            float: right;
            margin-top: 4px;
            margin-right: 10px;
            font-size: 14px;
        }
    </style>
<div class="card">
    <h5 class="card-header">Search Filter</h5>
    <div id="map-container">
        <div id="customer-list-container" style="none">
            <div style="position: relative; background-color: transparent;" class="ml-2">
                <div class="form-group" style="padding:8px">
                    <input id="search-input" class="form-control form-control-sm" type="text"
                        placeholder="Search customer" />
                    <!-- <span id="customer-counter"></span> -->
                </div>

                <br>

                <ul id="customer-list">
                </ul>
            </div>

        </div>
        <div id="map"></div>
    </div>


</div>

<script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script>
    let map, activeInfoWindow, markers = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: -1.292066,
                lng: 36.821945,
            },
            zoom: 6,
            mapTypeId: '{{ $typeMap }}'
        });
        google.maps.event.addListener(map, "click", function(event) {
            mapClicked(event);
        });



        initMarkers();
        initCustomerList();
    }

    function initMarkers() {
        const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
        const markerCluster = new MarkerClusterer(map, [], {
            imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
        });

        for (let index = 0; index < initialMarkers.length; index++) {
            var image = new google.maps.MarkerImage(
                "{{ asset('app-assets/images/pin.png') }}",
                null,
                null,
                null,
                new google.maps.Size(40, 52)
            );

            const markerData = initialMarkers[index];
            const marker = new google.maps.Marker({
                position: markerData.position,
                icon: image,
                map: map,
                label: markerData.PlotNumber,
            });
            markers.push(marker);

            const infowindow = new google.maps.InfoWindow({
                content: `
        <div id="content">
            <div id="siteNotice"> </div>

            <img
                src="{{ asset('app-assets/images/small_logo.png') }}"
                alt="avatar"
                height="50"
            />
            <h1 id="firstHeading" class="firstHeading">${markerData.customer_name}</h1>
            <div id="bodyContent">
                <p><b>Location: </b>${markerData.position.lat}, ${markerData.position.lng}</p>
                <p><b>Customer Name: </b>${markerData.customer_name}</p>
                <p><b>Approval: </b>${markerData.approval}</p>
                <p><b>Status: </b>${markerData.status}</p>
                <p><b>More info: </b><a href="{{ URL('/customer/${markerData.id}/edit') }}">${markerData.customer_name}</a></p>
            </div>
        </div>
        `,
            });
            google.maps.event.addListener(marker, "click", function(event) {
                if (activeInfoWindow) {
                    activeInfoWindow.close();
                }
                infowindow.open({
                    anchor: marker,
                    shouldFocus: false,
                    map
                });
                activeInfoWindow = infowindow;
                markerClicked(marker, index);
            });

            google.maps.event.addListener(marker, "dragend", (event) => {
                markerDragEnd(event, index);
            });

            // Add the hover effect to show the info window
            google.maps.event.addListener(marker, "mouseover", () => {
                infowindow.open({
                    anchor: marker,
                    shouldFocus: false,
                    map,
                });
            });

            // Close the info window when mouseout
            google.maps.event.addListener(marker, "mouseout", () => {
                infowindow.close();
            });

            // Add the marker to the marker clusterer
            markerCluster.addMarker(marker);
        }
    }

    function panToMarker(index) {
        const marker = initialMarkers[index];
        if (marker) {
            const position = marker.position;
            const latLng = {
                lat: position.lat,
                lng: position.lng
            };
            console.log(latLng);
            map.panTo(latLng);
            map.setZoom(25);
            google.maps.event.trigger(marker, "click");
        }
    }
</script>
<script>
        function initCustomerList() {
            const customerListContainer = document.getElementById("customer-list-container");
            const customerList = document.getElementById("customer-list");
            const searchInput = document.getElementById("search-input");
            const customerCounter = document.getElementById("customer-counter");
            const toggleButton = document.getElementById("toggle-button");
            const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
            const markers = [];

            searchInput.addEventListener("input", function() {
                const searchText = searchInput.value.toLowerCase();
                customerList.innerHTML = "";
                let count = 0;

                initialMarkers.forEach((markerData, index) => {
                    const customerName = markerData.customer_name.toLowerCase();

                    if (customerName.includes(searchText) && count < 100) {
                        const listItem = document.createElement("li");
                        listItem.classList.add("list-group-item");
                        listItem.textContent = markerData.customer_name;
                        listItem.addEventListener("click", () => {
                            panToMarker(index);
                        });
                        customerList.appendChild(listItem);
                        count++;
                    }
                });
                updateCustomerCounter(count);
            });

            // Initial rendering of the customer list
            searchInput.dispatchEvent(new Event("input"));
            toggleButton.addEventListener("click", function() {
                if (customerListContainer.style.display == "none") {
                    customerListContainer.style.display = "block";
                } else {
                    customerListContainer.style.display = "none";
                }
            });

            function panToMarker(index) {
                const marker = initialMarkers[index];
                if (marker) {
                    const position = marker.position;
                    const latLng = {
                        lat: position.lat,
                        lng: position.lng
                    };
                    console.log(latLng);
                    map.panTo(latLng);
                    map.setZoom(20);
                    google.maps.event.trigger(marker, "click");
                }
            }

            function updateCustomerCounter(count) {
                const remainingCount = initialMarkers.length - count;
                customerCounter.textContent = `+${remainingCount}`;
            }

            searchInput.dispatchEvent(new Event("input"));
        }

        initCustomerList();
    </script>


