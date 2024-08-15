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
                    { lat: 2.3386422982966857, lng: 99.08163374847082, title: "Hotel Labersa Balige" },
                    { lat: 2.352983, lng: 99.121766, title: "Hotel Sere Nauli" },
                    { lat: 2.334691, lng: 99.059573, title: "Hotel Purnama Balige" },
                    { lat: 2.666378, lng: 98.936345, title: "Hotel Atsari Parapat" },
                    { lat: 2.661051, lng: 98.934840, title: "Hotel Niagara" },
                    { lat: 2.6653501961587223, lng: 98.93091046602305, title: "Hotel Khas Parapat" },
                    { lat: 2.667634814140075, lng: 98.85143786469042, title: "Marianna Resort" },
                    { lat: 2.677314, lng: 98.853917, title: "Samosir Cottage" },
                    { lat: 2.670848932227199, lng: 98.8555715329546, title: "Tabo Cottages" },
                    { lat: 2.260851, lng: 98.790322, title: "Coffee Hotel Ayola Doloksanggul (CHADS)" },
                    { lat: 2.318655, lng: 98.835182, title: "Hotel Senior Bakara" },
                    { lat: 2.272731, lng: 98.765697, title: "Martin Anugrah Hotel" },
                    { lat: 2.024261, lng: 98.976042, title: "Hotel Hineni" },
                    { lat: 2.012323, lng: 98.961587, title: "Sopo Nomensen" },
                    { lat: 2.022258, lng: 98.962683, title: "Hotel Safari" },
                    { lat: 2.744957, lng: 98.317575, title: "The Sasta Hotel" },
                    { lat: 2.813900, lng: 98.527762, title: "Hotel Debang Resort Silalahi" },
                    { lat: 2.973200, lng: 98.498870, title: "Cheagia Resort Hotel" },
                    { lat: 2.5558412768003773, lng: 98.32220634265353, title: "Waris Hotel" },
                    { lat: 2.577811, lng: 98.488740, title: "Hotel Impana" }, // ga tau coordinatenya
                    { lat: 2.557059, lng: 98.329139, title: "Hotel LoLona 2" },
                    { lat: 3.201584, lng: 98.525178, title: "Mickey Holiday Resort" },
                    { lat: 3.202429, lng: 98.521775, title: "Grand Mutiara Hotel" },
                    { lat: 2.887783, lng: 98.507302, title: "Taman Simalem Resort" },
                ],

                homestay: [
                    { lat: 2.665740, lng: 98.848667, title: "Rose’s Homestay" },
                    { lat: 2.319503694089728, lng: 99.00032279089083, title: "Home Stay Bagasan" },
                    { lat: 2.322302, lng: 99.003996, title: "Vania Homestay" },
                    { lat: 2.66650364881705, lng: 98.93730985419022, title: "Homestay Kampung Warna Warni Tigarihit" },
                    { lat: 2.7839559248191543, lng: 98.80366036147488, title: "Penginapan Pantai Kenangan" },
                    { lat: 2.620808, lng: 98.692452, title: "Sharon Home Samosir" },
                    { lat: 2.699176, lng: 98.691987, title: "D'FLO Homestay" },
                    { lat: 2.6065898881611815, lng: 98.69808602011331, title: "Topi Tao Homestay" },
                    { lat: 2.3076835969634937, lng: 98.81427404339532, title: "Homestay Simamora" },
                    { lat: 2.3511734303754728, lng: 98.8156885061361, title: "Homestay Bumdes Tipang" },
                    { lat: 2.312847, lng: 98.887469, title: "Homestay Ompu Batuanggil" },
                    { lat: 2.213331990208685, lng: 99.00147578268151, title: "Piltik Homestay" },
                    { lat: 2.0166766778254637, lng: 98.9923735299423, title: "Graha Kartini Homestay" },
                    { lat: 2.0751670343689437, lng: 98.94411147987394, title: "Boli Boli Hot Spring" },
                    { lat: 2.7610522161628808,  lng: 98.3498935005918, title: "Homestay Pertaki" },
                    { lat: 2.830984, lng: 98.342371, title: "Penginapan CU.Persada Perempuan" }, // ga tau
                    { lat: 2.575936, lng: 98.488098, title: "D'Marriat Homestay" }, // ga tau
                    { lat: 3.1056030454169115,  lng: 98.4980939389439, title: "Guest House Simole" },
                    { lat: 3.1935011799235906,  lng: 98.50853645908569, title: "Losmen Mexico" },
                    { lat: 3.2016519101791863,  lng: 98.52724561221915, title: "Penginapan Keluarga Permata Ivana Berastagi" },
                ],
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