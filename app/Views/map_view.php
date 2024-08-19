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
                    //{ lat: 2.577811, lng: 98.488740, title: "Hotel Impana" }, // ga tau coordinatenya
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
                    { lat: 2.7610522161628808, lng: 98.3498935005918, title: "Homestay Pertaki" },
                    //{ lat: 2.830984, lng: 98.342371, title: "Penginapan CU.Persada Perempuan" }, // ga tau
                    //{ lat: 2.575936, lng: 98.488098, title: "D'Marriat Homestay" }, // ga tau
                    { lat: 3.1056030454169115, lng: 98.4980939389439, title: "Guest House Simole" },
                    { lat: 3.1935011799235906, lng: 98.50853645908569, title: "Losmen Mexico" },
                    { lat: 3.2016519101791863, lng: 98.52724561221915, title: "Penginapan Keluarga Permata Ivana Berastagi" },
                ],
                restoran: [
                    { lat: 2.3360004292461887, lng: 99.0733953788413, title: "RM. Sinar Minang" },
                    { lat: 2.4441048995918337, lng: 99.15758161424057, title: "Warung Zamzam" },
                    { lat: 2.358004131351681, lng: 99.1303160842674, title: "RM. Fly Over Laguboti" },
                    { lat: 2.6668630497857944, lng: 98.93996379502539, title: "Rumah Makan Istana Minang Parapat" },
                    { lat: 2.663772584798975, lng: 98.93106324183346, title: "Rumah Makan Marina Parapat" },
                    { lat: 2.666429929382137, lng: 98.94317971197866, title: "Rumah Makan Khas Batak Tiurma Siboro" },
                    { lat: 2.668968471060939, lng: 98.85847014368989, title: "Sekapur Sirih Restaurant" },
                    //{ lat: 2.663781, lng: 98.854180, title: "RM Khas Minang Ridho" }, // ga tau
                    { lat: 2.5298860266263343, lng: 98.73467395005412, title: "RM. Nasional Sampean Uli" },
                    { lat: 2.2659130790228743, lng: 98.74634502674972, title: "RM PUTRA PRIBUMI (DAGING KUDA)" },
                    { lat: 2.3519251997345947, lng: 98.82075022671499, title: "Restoran Terapung Tipang Mas" },
                    { lat: 2.26005126367354, lng: 98.75440510523292, title: "Bakmie Panjang" },
                    { lat: 2.017107553411578, lng: 98.96378959409616, title: "RM Citra Minang AYU" },
                    { lat: 2.0213128754024727, lng: 98.962577590691, title: "Ayam Kejar" },
                    { lat: 2.0231991081618146, lng: 98.97704168365512, title: "RM laposona" },
                    { lat: 2.7479977765064727, lng: 98.31448982015525, title: "RM. Rico Khas Minang" },
                    { lat: 2.7440056018914545, lng: 98.31879558334283, title: "RM. Padang Raya" },
                    { lat: 2.7556537208544682, lng: 98.30770034620319, title: "RM. Elekta" },
                    //{ lat: 2.572615, lng: 98.977718, title: "Rumah Makan Garuda" }, // ga tau
                    { lat: 2.5518231026340072, lng: 98.32120323916298, title: "Rumah Makan Muslim Madu Ntedoh" },
                    { lat: 3.21313622576329, lng: 98.50867790404703, title: "Gundaling Farmstead" },
                    { lat: 3.1962676471885048, lng: 98.51572754295809, title: "Wajik Peceren" },
                    { lat: 3.1000335526979392, lng: 98.48958070280874, title: "Mari Ras BPK" },
                ],

                cafe: [
                    { lat: 2.340445199218544, lng: 99.05058495571461, title: "Damar Toba ~ Lakeside Eatery & Stay" },
                    { lat: 2.334118140926936, lng: 99.05645049141272, title: "Tepi Danau Bistro Balige" },
                    { lat: 2.5902809251356036, lng: 99.0362743830995, title: "The Mario's Eatery & Coffee" },
                    { lat: 2.667708935342897, lng: 98.93865528763928, title: "Pak Pos Cafe" },
                    { lat: 2.6975144155346498, lng: 98.92263594281395, title: "La Pinus Damasus Cafe" },
                    { lat: 2.9311582813139365, lng: 98.62186705036912, title: "Sopou Cafe & Resto" },
                    { lat: 2.6070294770020457, lng: 98.69685801431899, title: "Tepi Coffee" },
                    { lat: 2.6046905480579023, lng: 98.70136991178812, title: "Warung Kopi Synergy" },
                    { lat: 2.6789908039428654, lng: 98.84192953307506, title: "Janji Maria Coffee" },
                    { lat: 2.275609917032527, lng: 98.74509898118073, title: "Sitalbak Doloksanggul" },
                    { lat: 2.2564262930780017, lng: 98.74864263169513, title: "Tondongta Cafe & Resto" },
                    { lat: 2.2597761698087884, lng: 98.7492379505784, title: "Jelova Coffee and Resto" },
                    { lat: 2.2110494386674477, lng: 98.9709211926449, title: "Angkasa Cafe" },
                    { lat: 2.2110507787734766, lng: 98.97091985154047, title: "Binneka Cafe & Resto" },
                    { lat: 2.2133312991674954, lng: 99.00147731558877, title: "Piltik Coffee Siborongborong" },
                    { lat: 2.740198732752258, lng: 98.32390966987272, title: "MR Cafe & Resto" },
                    { lat: 2.7451532635258142, lng: 98.31797747225298, title: "Kedai Coffee Hot Plate Uwo 77" },
                    { lat: 2.7503168805171723, lng: 98.31126080075602, title: "PODA Cafe And Chocolate" },
                    //{ lat: 2.417136, lng: 98.930161, title: "Kedai Coffee Jack Kecupak" }, // ga tau
                    { lat: 2.556752870437934, lng: 98.32302029174784, title: "Roh Ke Coffee" },
                    { lat: 3.198848142076196, lng: 98.5071581515519, title: "Cafe Jabu" },
                    { lat: 2.907987708975843, lng: 98.51803853269224, title: "Loken Baren Cafe" },
                    { lat: 3.037127128007965, lng: 98.53379426224971, title: "Takar Coffee and Resto" },
                ],

                "daya-tarik-wisata": [
                    { lat: 2.6077242, lng: 98.9464951, title: "The Kaldera, Toba Caldera Resort" },
                    { lat: 2.3332013519485844, lng: 99.04841622532857, title: "Museum TB. Silalahi Center" },
                    { lat: 2.5910997589638294, lng: 99.03889019467839, title: "Taman Eden 100" },
                    { lat: 2.6931602350237287, lng: 98.92698507842776, title: "Monkey Forest Sibaganding" },
                    { lat: 2.6655507738724884, lng: 98.9264186474579, title: "Pesanggrahan Soekarno" },
                    { lat: 2.717820921872263, lng: 98.94030182187414, title: "Aek Nauli Conservasi Gajah" },
                    { lat: 2.550704358724131, lng: 98.67238745607985, title: "Patung Yesus Bukit Sibea-bea" },
                    { lat: 2.601145630421308, lng: 98.70205334070579, title: "Waterfront City Pangururan" },
                    { lat: 2.67904071450911, lng: 98.8362644767855, title: "Huta Siallagan" },
                    { lat: 2.2480643629699166, lng: 98.67326490415554, title: "Tuan Nagani Paradise" },
                    { lat: 2.3057998485126023, lng: 98.812622640612, title: "Istana Raja Sisingamangaraja" },
                    { lat: 2.2010609028251107, lng: 98.54415206702987, title: "Wisata 1000 Goa Banuarea" },
                    { lat: 2.0177436423686097, lng: 99.00142673156427, title: "Salib Kasih Tarutung" },
                    { lat: 2.0751664293551175, lng: 98.94411257747569, title: "Hotspring Sipoholon" },
                    { lat: 2.0005920612752077, lng: 98.96591714188817, title: "Soda pring Tarutung" },
                    { lat: 2.79471815844205, lng: 98.5278717378683, title: "Pantai Danau Toba/Tao Silalahi" },
                    { lat: 2.7727081567504963, lng: 98.55279877361802, title: "Air Terjun Siringo" },
                    { lat: 2.7320000186118456, lng: 98.38290623929262, title: "Taman Wisata Iman" },
                    //{ lat: 2.427222, lng: 98.865278, title: "Desa Wisata Kuta Jungak" }, // ga tau
                    { lat: 2.6108756455712694, lng: 98.34911785142768, title: "Air Terjun Lae Mbilulu" },
                    { lat: 1.686051924984197, lng: 98.94605013437392, title: "Air Terjun Sipitu Lae Une" },
                    { lat: 3.1999993304933203, lng: 98.51666700000001, title: "Gunung Sibayak" },
                    { lat: 3.2238129103494293, lng: 98.51408464845733, title: "Pemandian Air Panas Sidebuk-debuk" },
                    { lat: 3.1987195550583327, lng: 98.38038897703507, title: "Danau Lau Kawar" }
                ],
                sightseeing: [
                    { lat: 2.537427411263543, lng: 99.00797928529832, title: "Air Terjun Situmurun" },
                    { lat: 2.3396197670713077, lng: 99.01346345114456, title: "Pantai Pakkodian" },
                    { lat: 2.5910997589638294, lng: 99.03889019467839, title: "Taman Eden 100" },
                    { lat: 2.832920633850779, lng: 98.76864372330562, title: "Kawasan Simajarunjung" },
                    { lat: 2.8772948675246717, lng: 98.67826807690983, title: "Kawasan Haranggaol" },
                    { lat: 2.7348780671693675, lng: 98.85930181546914, title: "Bukit Sipolha" },
                    { lat: 2.5520614983608976, lng: 98.63981413926149, title: "Menara Pandang Tele" },
                    { lat: 2.5299791424223264, lng: 98.69718627175409, title: "Bukit Holbung" },
                    { lat: 2.6014839012523963, lng: 98.74476541359292, title: "Sidihoni" },
                    { lat: 2.3598876891207365, lng: 98.81579441223748, title: "Terasering Sibara-bara" },
                    { lat: 2.2381938777247807, lng: 98.45570557328402, title: "Panorama Dolok Pottas" },
                    { lat: 2.3372380477601395, lng: 98.81085043009269, title: "Panorama Indah Sileme Leme" },
                    { lat: 2.5591161845419372, lng: 98.75855608868497, title: "Hutaginjang" },
                    { lat: 2.6100740733710994, lng: 98.86417224316685, title: "Air Terjun Sampuran" },
                    //{ lat: 2.513611, lng: 98.842500, title: "Hotspring Panabungan" }, // ga tau
                    { lat: 2.759857202941844, lng: 98.56810274086989, title: "Silalahi Ujung" },
                    { lat: 2.3395376035480444, lng: 98.92627497616378, title: "Taman Tugu TB Simatupang dan Liberty Manik" },
                    { lat: 2.807418318324006, lng: 98.18208010452322, title: "Parhonasan Sempung Polling" },
                    { lat: 2.537572081075328, lng: 98.32090099938902, title: "Puncak Sindeka" },
                    { lat: 2.4620357608170815, lng: 98.38788397481052, title: "Delleng Simpon" },
                    //{ lat: 3.084167, lng: 98.494167, title: "Sarang Orang Utan" }, //gatau
                    { lat: 2.9164852379620188, lng: 98.51950420551815, title: "Air Terjun Sipiso Piso" },
                    { lat: 2.911649801029354, lng: 98.51826561691391, title: "Kebun Bunga Sapo Juma" },
                    { lat: 3.0020082605815523, lng: 98.45743073448062, title: "Siosar Puncak 2000" }
                ],

                "hidden-gem": [
                    { lat: 2.404207550411333,  lng: 99.047434412187, title: "Siregar Aek Na Las/Hotspring" },
                    { lat: 2.6357706598556523,  lng: 98.92106284539726, title: "Bukit Senyum Motung" },
                    { lat: 2.5638667572508087,  lng: 99.3353288882297, title: "Arung Jeram Parhitean" },
                    { lat: 3.1483342299873063,  lng: 98.78083312603822, title: "Hotspring Tinggi Raja" },
                    { lat: 2.9064570699416015,  lng: 98.56547402849553, title: "Wisata Indah Sippan" },
                    { lat: 2.819513426935471,  lng: 99.0379529447052, title: "Aek Manigom Nauli - Tiga Dolok" },
                    { lat: 2.623614019672029, lng: 98.62558989212035, title: "Batu Anduhur" },
                    { lat: 2.5572040075596885, lng: 98.91317991282699, title: "Ecovillage Silimalombu" },
                    { lat: 2.5501042544807966,  lng: 98.90541507954137, title: "Bukit Sipira" },
                    { lat: 2.307726300207938,  lng: 98.80611556269736, title: "Tombak Sulu-sulu" },
                    { lat: 2.3556554863397365,  lng: 98.8057645655858, title: "Air Terjun Sipoltak Hoda (Sigota-gota)" },
                    { lat: 2.387467156859831,  lng: 98.80871423469377, title: "Batu Maranak" },
                    { lat: 2.256678640357492,  lng: 99.04046840473846, title: "Lombang Rarat Siborong-borong" },
                    //{ lat: 2.513611, lng: 98.842500, title: "Hotspring Panabungan" }, // gtau
                    { lat: 2.0269665636240486, lng: 98.98092815259714, title: "Arung Jeram Aek Situmanding" },
                    { lat: 2.816360935286847,  lng: 98.52376813255994, title: "Bukit Siattar Atas" },
                    { lat: 2.7598553603449303,  lng: 98.56810126272056, title: "Batu Sigumbang Silalahi (Batu Nauli Basa)" },
                    //{ lat: 2.535000, lng: 98.964167, title: "Panorama Indah Puncak Sidikalang" }, // gtau
                    //{ lat: 2.490000, lng: 98.714722, title: "Rafting Lae Ordi" },// gtau
                    //{ lat: 2.538611, lng: 98.861667, title: "Mejan Penanggelang" }, // gtau
                    //{ lat: 2.478889, lng: 98.764167, title: "Rafting Lae Kombih" }, // gtau
                    //{ lat: 2.868611, lng: 98.512500, title: "Rumah Adat Siwaluh Jabu (Dokan, Lingga)" },// gtau
                    { lat: 2.899046180173312,  lng: 98.51087636601007, title: "Bukit Gajah Bobok" },
                    { lat: 3.180819422717052,  lng: 98.40796426239132, title: "Sabana Gunung Sinabung" }
                ],
                "rumah-sakit": [
                    { lat: 2.330776403142542,  lng: 99.06676372121491, title: "Rumah Sakit HKBP Balige" },
                    { lat: 2.434081787235698,  lng: 99.15641257265767, title: "Rumah Sakit Porsea" },
                    { lat: 2.334138027399342,  lng: 99.061482170841, title: "Puskesmas Tandang Buhit Balige" },
                    { lat: 2.667821766405243,  lng: 98.93767591495642, title: "Rumah Sakit Parapat" },
                    { lat: 2.9640310169119934,  lng: 98.86100989497704, title: "Hospital Mr. Rondahaim - Raya" },
                    { lat: 2.6581131246078926,  lng: 98.95284883045977, title: "Puskesmas Parapat" },
                    { lat: 2.596109847265608,  lng: 98.70646760472468, title: "RSUD Hadrianus Sinaga Pangururan" },
                    { lat: 2.636734899550492,  lng: 98.69081504005551, title: "Puskesmas Buhit" },
                    { lat: 2.737347424375216,  lng: 98.70506218759682, title: "Puskesmas Simarmata" },
                    { lat: 2.2595640111612716, lng: 98.75263691193216, title: "Rumah Sakit Umum Dolok Sanggul" },
                    { lat: 2.3033627184992187, lng: 98.92660128978555, title: "Puskesmas Paranginan" },
                    { lat: 2.5866843501849828,  lng: 98.76606820303452, title: "Puskesmas Lintong Nihuta" },
                    { lat: 2.0342671216408768,  lng: 98.9585817075897, title: "Rumah Sakit Umum Tarutung" },
                    { lat: 2.210340277389314, lng: 98.97110828432658, title: "Rumah Sakit Sint Lucia Siborong-borong" },
                    { lat: 2.0772165758582104,  lng: 98.93952546263377, title: "Puskesmas Sipoholon" },
                    { lat: 2.7392551371989393,  lng: 98.31923134127426, title: "Rumah Sakit Umum Sidikalang" },
                    { lat: 2.735832404746629,  lng: 98.34074148207428, title: "Puskesmas Batang Beruh" },
                    { lat: 2.55742834156085,  lng: 98.33128754736694, title: "Rumah Sakit Umum Daerah Salak" },
                    { lat: 3.0976553705574954,  lng: 98.4918954590842, title: "RSUD Kabanjahe" },
                    { lat: 3.1455163996414663,  lng: 98.5072625927676, title: "Rumah Sakit Efarinna Etaham" },
                    { lat: 3.6637949691776335,  lng: 98.65644090758495, title: "Rumah Sakit Ester" }
                ]

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