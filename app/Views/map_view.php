<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Kaldera Map</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEQ4a3ykos1cyUoIejItumNfzZeQsErXE&callback=initMap"
        async defer></script>
    <style>
        /* Make the map fullscreen */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #map {
            height: 100%;
            width: 100%;
            position: relative;
        }

        /* Position the category buttons inside the map, top center */
        #categories {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
            display: flex;
            gap: 10px;
            padding: 5px 10px;
            border-radius: 25px;
        }

        .category-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .category-btn:before {
            content: "";
            display: inline-block;
            width: 18px;
            height: 18px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            margin-right: 5px;
        }

        .category-btn:hover {
            background-color: #ddd;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .category-btn.active {
            background-color: #dddd;
            color: black;
        }

        .category-btn img {
            width: 18px;
            height: 18px;
        }

        .category-btn[data-category="hotel"]:before {
            background-image: url("icons/hotel.png");
        }

        .category-btn[data-category="homestay"]:before {
            background-image: url("icons/homestay.png");
        }

        .category-btn[data-category="restoran"]:before {
            background-image: url("icons/restaurant.png");
        }

        .category-btn[data-category="cafe"]:before {
            background-image: url("icons/cafe.png");
        }

        .category-btn[data-category="daya-tarik-wisata"]:before {
            background-image: url("icons/touristattraction.png");
        }

        .category-btn[data-category="sightseeing"]:before {
            background-image: url("icons/sightseeing.png");
        }

        .category-btn[data-category="hidden-gem"]:before {
            background-image: url("icons/hiddengem.png");
        }

        .category-btn[data-category="rumah-sakit"]:before {
            background-image: url("icons/hospital.png");
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

            const locations = <?= $markers ?>;

            for (let location of locations) {
                if (!markers[location.category]) {
                    markers[location.category] = [];
                }

                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: null,
                    title: location.title,
                    icon: `http://maps.google.com/mapfiles/ms/icons/${getCategoryColor(location.category)}-dot.png`
                });

                marker.addListener("click", () => {
                    const infowindow = new google.maps.InfoWindow({
                        content: `<h3>${location.title}</h3>${location.url ? `<p><a href="${location.url}" target="_blank">Visit Website</a></p>` : ''}`,
                        ariaLabel: location.title,
                    });
                    infowindow.open({
                        anchor: marker,
                        map,
                    });
                });

                markers[location.category].push(marker);
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