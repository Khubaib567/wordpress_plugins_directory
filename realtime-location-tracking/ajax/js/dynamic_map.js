
jQuery(document).ready(function ($) {

    let map = L.map('map').setView([51.505, -0.09], 5);

    let isSelecting = true;
    let pickupPoint = null;
    let dropoffPoint = null;
    let pickupName = '';
    let dropoffName = '';
    let markerPickup, markerDropoff, line;


    // 📏 Haversine Distance (KM)
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;

        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    // ⏱️ Duration (based on avg speed)
    function getDuration(distanceKm, speed = 60) {
        const time = distanceKm / speed;

        const hours = Math.floor(time);
        const minutes = Math.round((time - hours) * 60);

        return `${hours}h ${minutes}m`;
    }


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

        // ================================
        // ✅ CALCULATE DISTANCE + DURATION
        // ================================
        let distance = getDistance(
            pickupPoint[0],
            pickupPoint[1],
            dropoffPoint[0],
            dropoffPoint[1]
        );

         let duration = getDuration(distance, 60);

        let distanceText = distance.toFixed(2) + " km";

         // ================================
        // ✅ UPDATE EACH POPUP SEPARATELY
        // ================================

        if (markerPickup) {
            markerPickup.setPopupContent(`
                <b>Pickup:</b> ${pickupName} <br>
                📏 Distance: ${distanceText} <br>
                ⏱ Duration: ${duration}
            `).openPopup();
        }

        if (markerDropoff) {
            markerDropoff.setPopupContent(`
                <b>Dropoff:</b> ${dropoffName} <br>
                📏 Distance: ${distanceText} <br>
                ⏱ Duration: ${duration}
            `).openPopup();
        }

    }

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 🔍 Search airport for pickup locations
    $('#pickup-location').on('keyup', function (e) {

        e.preventDefault();

        if (!isSelecting) return; // ⛔ prevent re-trigger

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


                }
            }
        });

    });


    // PICKUP LOCATION
    $(document).on('click','#pickup-suggestions li', function (e) {

    e.preventDefault();

    isSelecting = true; // ⛔ block keyup

    let name = $(this).text();
    pickupName = name; // ✅ store it
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
    // console.log("pickup: " , pickupPoint)

    // 📍 Move map
    map.setView(pickupPoint, 8);

    // 📍 Remove previous marker
    if (markerPickup) {
        map.removeLayer(markerPickup);
    }

    markerPickup = L.marker(pickupPoint)
        .addTo(map)
        .bindTooltip("Pickup Location", {
            permanent: true,
            direction: "top"
        })
        .bindPopup("Pickup: " + name, {
            autoClose: false,
            closeOnClick: false
        })
        .openPopup();


    drawRouteIfReady();

     // reset AFTER a small delay
    setTimeout(() => {
        isSelecting = true;
    }, 200);


    });


     // 🔍 Search airport for drop-off location
    $('#dropoff-location').on('keyup', function (e) {

        e.preventDefault();

        if (!isSelecting) return; // ⛔ prevent re-trigger

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

                }
            }
        });

    });


    // DROPOFF LOCATION
    $(document).on('click','#dropoff-suggestions li', function (e) {

    e.preventDefault();

    isSelecting = true; // ⛔ block keyup
    
    let name = $(this).text();
    dropoffName = name; // ✅ store it
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
    // console.log("drop-off: " , dropoffPoint)

    // 📍 Remove old dropoff marker
    if (markerDropoff) {
        map.removeLayer(markerDropoff);
    }

    markerDropoff = L.marker(dropoffPoint)
        .addTo(map)
        .bindTooltip("Pickup Location", {
            permanent: true,
            direction: "top"
        })
        .bindPopup("Pickup: " + name, {
            autoClose: false,
            closeOnClick: false
        })
        .openPopup();

    drawRouteIfReady();

    // reset AFTER a small delay
    setTimeout(() => {
            isSelecting = true;
    }, 200);
});

});

