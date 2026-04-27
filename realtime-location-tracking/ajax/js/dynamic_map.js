
jQuery(document).ready(function ($) {

    let map = L.map('map').setView([51.505, -0.09], 5);

    let isSelecting = false;
    let pickupPoint = null;
    let dropoffPoint = null;
    let markerPickup, markerDropoff, line;


    function drawRouteIfReady() {

        if (!pickupPoint || !dropoffPoint) return;

        // 📏 Remove old line
        if (line) {
            map.removeLayer(line);
        }

        // ✈️ Draw route
        line = L.polyline([pickupPoint, dropoffPoint], {
            color: 'blue',
            weight: 3,
            opacity: 0.7
        }).addTo(map);

        // 🔍 Fit both points
        map.fitBounds(line.getBounds());
    }

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 🔍 Search airport for pickup locations
    $('#pickup-location').on('keyup', function (e) {

        e.preventDefault();

        if (isSelecting) return; // ⛔ prevent re-trigger

        let query = $(this).val();
        // console.log(query);

        if (query.length < 3) {
            $('#pickup-suggestions').hide();
            return;
        }

        $.ajax({
            url: custom_ajax_3.ajax_url,
            type: 'POST',
            data: {
                action: 'get_airports',
                nonce: custom_ajax_3.nonce,
                query: query
            },
            success: function (response) {

                if (response.success) {

                    let list = '';

                    response.data.forEach(item => {
                        list += `<li style="padding:8px; cursor:pointer;" 
                        data-lat="${item.lat}" 
                        data-lng="${item.lng}" 
                        >${item.name}</li>`;
                    });

                    // list += `<li style="padding:8px; cursor:pointer;">${response.data}</li>`;

                    $('#pickup-suggestions').html(list).show();

                    // reset AFTER a small delay
                    setTimeout(() => {
                            isSelecting = true;
                      }, 200);

                }
            }
        });

    });


    // PICKUP LOCATION
    $(document).on('click','#pickup-suggestions li', function (e) {

    e.preventDefault();

    isSelecting = true; // ⛔ block keyup

    let name = $(this).text();
    let lat = parseFloat($(this).data('lat'));
    let lng = parseFloat($(this).data('lng'));

    // console.log(name, lat, lng);

     // ✅ Hide suggestion list
    $('#pickup-suggestions').empty().hide();

    // Optional: also set input value
    $('#pickup-location').val(name);


    // reset AFTER a small delay
    setTimeout(() => {
        isSelecting = false;
    }, 200);

    if (isNaN(lat) || isNaN(lng)) return;

    pickupPoint = [lat, lng];

    // 📍 Move map
    map.setView(pickupPoint, 8);

    // 📍 Remove previous marker
    if (markerPickup) {
        map.removeLayer(markerPickup);
    }

    markerPickup = L.marker(pickupPoint)
        .addTo(map)
        .bindPopup("Pickup: " + name)
        .openPopup();


    drawRouteIfReady();

    });


     // 🔍 Search airport for drop-off location
    $('#dropoff-location').on('keyup', function (e) {

        e.preventDefault();

        if (isSelecting) return; // ⛔ prevent re-trigger

        let query = $(this).val();
        // console.log(query);

        if (query.length < 3) {
            $('#dropoff-suggestions').hide();
            return;
        }

        $.ajax({
            url: custom_ajax_3.ajax_url,
            type: 'POST',
            data: {
                action: 'get_airports',
                nonce: custom_ajax_3.nonce,
                query: query
            },
            success: function (response) {

                if (response.success) {

                    let list = '';

                    response.data.forEach(item => {
                        list += `<li style="padding:8px; cursor:pointer;" 
                        data-lat="${item.lat}" 
                        data-lng="${item.lng}" 
                        >${item.name}</li>`;
                    });

                    // list += `<li style="padding:8px; cursor:pointer;">${response.data}</li>`;

                    $('#dropoff-suggestions').html(list).show();


                     // reset AFTER a small delay
                    setTimeout(() => {
                            isSelecting = true;
                      }, 200);
                }
            }
        });

    });


    // DROPOFF LOCATION
    $(document).on('click','#dropoff-suggestions li', function (e) {

    e.preventDefault();

    isSelecting = true; // ⛔ block keyup
    
    let name = $(this).text();
    let lat = parseFloat($(this).data('lat'));
    let lng = parseFloat($(this).data('lng'));

    $('#dropoff-suggestions').empty().hide();
    $('#dropoff-location').val(name);

    // reset AFTER a small delay
    setTimeout(() => {
        isSelecting = false;
    }, 200);

    if (isNaN(lat) || isNaN(lng)) return;

    dropoffPoint = [lat, lng];

    // 📍 Remove old dropoff marker
    if (markerDropoff) {
        map.removeLayer(markerDropoff);
    }

    markerDropoff = L.marker(dropoffPoint)
        .addTo(map)
        .bindPopup("Dropoff: " + name)
        .openPopup();

        drawRouteIfReady();
    });

});

