<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Kaldera Map</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEQ4a3ykos1cyUoIejItumNfzZeQsErXE&callback=initMap"
        async defer></script>
    <style>
        #map {
            height: 600px;
            width: 100%;
        }

        .marker-label {
            background-color: transparent;
            border: none;
            padding: 2px 5px;
            position: absolute;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
            color: black;
            /* You might want to adjust this color for better visibility */
        }

        #categories {
            margin-bottom: 10px;
        }

        .category-btn {
            margin-right: 5px;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
        }

        .category-btn.active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <div id="categories">
        <button class="category-btn" data-category="hotel">Hotel</button>
        <button class="category-btn" data-category="homestay">Homestay</button>
        <button class="category-btn" data-category="restoran">Restoran</button>
        <button class="category-btn" data-category="cafe">Cafe</button>
        <button class="category-btn" data-category="daya-tarik-wisata">Daya Tarik Wisata</button>
        <button class="category-btn" data-category="sightseeing">Sightseeing</button>
        <button class="category-btn" data-category="hidden-gem">Hidden Gem</button>
        <button class="category-btn" data-category="rumah-sakit">Rumah Sakit</button>
    </div>
    <div id="map"></div>

    <script>
        let map;
        let markers = {};
        let activeCategories = new Set();

        function initMap() {
            const mapStyles = [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                },
                {
                    featureType: "transit",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                },
                {
                    featureType: "road",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ];

            const kalderaLocation = { lat: 2.6077242, lng: 98.9464951 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: kalderaLocation,
                zoom: 14,
                styles: mapStyles
            });

            const locations = {
                sightseeing: [{ lat: 2.6077242, lng: 98.9464951, title: "The Kaldera" }],
                hotel: [
                    { lat: 2.33886189149105, lng: 99.0821692769445, title: "Hotel Labersa Balige" },
                    { lat: 2.352938140768292, lng: 99.12181659374778, title: "Hotel Sere Nauli" }
                ],
                homestay: [{ lat: 2.61, lng: 98.95, title: "Sample Homestay" }],
                restoran: [{ lat: 2.605, lng: 98.94, title: "Sample Restaurant" }],
                cafe: [{ lat: 2.615, lng: 98.945, title: "Sample Cafe" }],
                "daya-tarik-wisata": [{ lat: 2.62, lng: 98.95, title: "Tourist Attraction" }],
                "hidden-gem": [{ lat: 2.605, lng: 98.955, title: "Hidden Gem" }],
                "rumah-sakit": [{ lat: 2.6, lng: 98.95, title: "Hospital" }]
            };

            for (let category in locations) {
                markers[category] = locations[category].map(location => {
                    const marker = new google.maps.Marker({
                        position: { lat: location.lat, lng: location.lng },
                        map: null,
                        title: location.title,
                        icon: `http://maps.google.com/mapfiles/ms/icons/${getCategoryColor(category)}-dot.png`
                    });

                    marker.addListener("click", () => {
                        const infowindow = new google.maps.InfoWindow({
                            content: `<h3>${location.title}</h3>`,
                            ariaLabel: location.title,
                        });
                        infowindow.open({
                            anchor: marker,
                            map,
                        });
                    });

                    return marker;
                });
            }

            document.querySelectorAll('.category-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const category = button.getAttribute('data-category');
                    toggleCategory(category);
                });
            });

            const showAllButton = document.createElement('button');
            showAllButton.textContent = 'Show All';
            showAllButton.className = 'category-btn';
            showAllButton.id = 'show-all-btn';
            document.getElementById('categories').appendChild(showAllButton);

            showAllButton.addEventListener('click', showAllCategories);

            showAllCategories();
        }

        function getCategoryColor(category) {
            const colors = {
                hotel: 'blue',
                homestay: 'green',
                restoran: 'yellow',
                cafe: 'purple',
                "daya-tarik-wisata": 'red',
                sightseeing: 'orange',
                "hidden-gem": 'pink',
                "rumah-sakit": 'ltblue'
            };
            return colors[category] || 'red';
        }

        function toggleCategory(category) {
            const button = document.querySelector(`[data-category="${category}"]`);

            document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));

            activeCategories.clear();

            if (button.classList.contains('active')) {
                showAllCategories();
            } else {
                button.classList.add('active');
                activeCategories.add(category);
                updateMarkerVisibility();
            }

            document.getElementById('show-all-btn').classList.remove('active');
        }

        function showAllCategories() {
            document.querySelectorAll('.category-btn').forEach(button => {
                button.classList.add('active');
                const category = button.getAttribute('data-category');
                if (category) {
                    activeCategories.add(category);
                }
            });

            updateMarkerVisibility();

            document.getElementById('show-all-btn').classList.add('active');
        }

        function updateMarkerVisibility() {
            for (let category in markers) {
                const isActive = activeCategories.has(category);
                markers[category].forEach(marker => {
                    marker.setMap(isActive ? map : null);
                });
            }
        }
    </script>

</body>

</html>