<!--html/php-->
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 400px;
          width: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -37.8136, lng: 144.9631},
          zoom: 8
        });
      }
    </script>
      <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCy80MlY38aLf6t8raaXYy8rU3-kpvVzck&callback=initMap"></script>
  </body>
</html>