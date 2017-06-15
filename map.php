<!DOCTYPE html>
<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
  <title>Google Maps Multiple Markers</title> 
 
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCajg2gehjcPV6DEmPRoKOtoXo4y4QOdY0&angular.noop=initMap"></script> 
</head> 
<body>
  <div id="map" style="width: 500px; height: 400px;"></div>

  <script type="text/javascript">
    var locations = [
       ['Udhab' , '23.23927', '77.43620666666668',4],
    ['Tanya' , '23.23843726', '77.43320262',5],
    ['Shaziya', '23.23846051', '77.4332301',6],
    ['Kajal' , '22.454375845455453454', '77.17543758475345845',7],
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(20.5937, 78.9629),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>
</body>
</html>