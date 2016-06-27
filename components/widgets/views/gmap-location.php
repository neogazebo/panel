<?php
if($type == 'static'):
?>

<img src="https://maps.googleapis.com/maps/api/staticmap?center=<?= $lat ?>,<?= $long ?>&zoom=11&size=<?= $width ?>x<?= $height ?>&maptype=roadmap&markers=color:blue|<?= $lat ?>,<?= $long ?>">

<?php
else:
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);

$this->registerJs("
    $(document).ready(function() {
        var directionsDisplay;
        var geocoder;
        var directionsService = new google.maps.DirectionsService();
        var map;
        geocoder = new google.maps.Geocoder();
        var marker;
        
        // get automatic location by IP
        getLocation();

        function updateMarkerPosition(latLng) {
            $('#lat').val(latLng.lat());
            $('#long').val(latLng.lng());
        }

        function initialize() {
            directionsDisplay = new google.maps.DirectionsRenderer();
            var center = new google.maps.LatLng($('#lat').val(), $('#long').val());
            var mapOptions = {
                zoom: 10,
                center: center
            }
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            directionsDisplay.setMap(map);

            marker = new google.maps.Marker({
                position: center,
                map: map,
                draggable: true,
                title: 'Drag me!'
            });

            google.maps.event.addListener(marker, 'drag', function() {
                updateMarkerPosition(marker.getPosition());
            });
        }

        $('#location').on('mouseup', function() {
            console.log('change');
            var address = $(this).val();
            if (address.length > 0) {
                geocoder.geocode({'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        marker.setPosition(results[0].geometry.location);
                        map.setCenter(results[0].geometry.location);
                        updateMarkerPosition(marker.getPosition());
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
        });
        google.maps.event.addDomListener(window, 'load', initialize);
        
        // for get automatic location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } 
        }
        function showPosition(position) {
            //var lat = position.coords.latitude;
            $('#lat').val(position.coords.latitude);
            //var lon = position.coords.longitude;
            $('#long').val(position.coords.longitude);

            initialize();
        }

    });

    
", yii\web\View::POS_END, 'gmap');

$this->registerCss("
    #gmap-waypoints {
        position: relative;
        display: block;
        width: " . $width . "px;
        height: " . $height . "px;
    }
    #map-canvas {
        width: 100%;
        height: 100%;
    }
");
?>
<div id="gmap-waypoints">
    <div id="map-form">
        <input type="hidden" name="lat" value="<?= $lat ?>" id="lat" />
        <input type="hidden" name="long" value="<?= $long ?>" id="long" />
    </div>
    <div id="map-canvas"></div>
</div>
<?php
endif;
?>
