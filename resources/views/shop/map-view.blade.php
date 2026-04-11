@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-body">
                        <div id="googleMap" style="width: 100%; height: 85vh;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ mapApiKey() }}"></script>

<script>
    $(document).ready(function() {
        getLocation();
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, function(error){

                initMap();
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        localStorage.setItem('lat', lat);
        localStorage.setItem('lng', lng);

        initMap(lat, lng);
    }

    function initMap(userLat, userLng) {

        $.ajax({
            url: '/shop/locations',
            type: "get",
            dataType: "json",
            success: function(data) {

                var map = new google.maps.Map(document.getElementById('googleMap'), {
                    zoom: 16,
                    center: new google.maps.LatLng(userLat, userLng),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infowindow = new google.maps.InfoWindow();

                for (let i = 0; i < data.length; i++) {

                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(data[i]['lat'], data[i]['lng']),
                        icon: "{{ asset('web/final-shop.png') }}",
                        map: map
                    });

                    let url = `/shops/${data[i]['id']}/details`;

                    let details = `
                        Name: <a href="${url}">
                        <strong style="color:blue">${data[i]['name']}</strong></a><br>
                        Email: <strong>${data[i]['email']}</strong><br>
                        Phone: <strong>${data[i]['phone']}</strong><br>
                        Rating:
                        <span style="color:#fbb340">&#9733</span>
                        <span style="color:#fbb340">&#9733</span>
                        <span style="color:#fbb340">&#9733</span>
                        <span style="color:#fbb340">&#9733</span>
                        <span style="color:#fbb340">&#9733</span>
                    `;

                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(details);
                        infowindow.open(map, marker);
                    });
                }

                //  User location marker
                new google.maps.Marker({
                    position: new google.maps.LatLng(userLat, userLng),
                    map: map,
                    title: "Your Location"
                });
            }
        });
    }
</script>
@endpush
