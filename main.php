<!--html/php-->
<?php
if(isset($_POST['submit'])){
    if(!empty($_POST['steamid'])){
ini_set("allow_url_fopen", 1);$jsonDecode = json_decode($jsonData, TRUE);
        //https://api.steampowered.com/ISteamApps/GetAppList/v2/?minlength=300&key=C1A9D93B831592B9BA3AF5A0D7F24CD9
$csgo_url = 'http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=730&count=10&maxlength=300&format=json';
$rl_url = 'http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=252950&count=10&maxlength=300&format=json';
$user_url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=C1A9D93B831592B9BA3AF5A0D7F24CD9&steamids='.$_POST['steamid'];      
        
$json = file_get_contents($user_url);
file_put_contents('gs://a2cloud-bucket/steamusers.json', $json);
$decoded = json_decode($json);
//preShow($decoded);
}
}

function preShow( $arr, $returnAsString=false ) {
  $ret  = '<pre>' . print_r($arr, true) . '</pre>';
  if ($returnAsString)
    return $ret;
  else 
    echo $ret; 
}
?>
<html>

<head>
    <title>CloudA2-s3720957</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheets" href="style.css">
    <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js" nonce="D21E7BBA940CEF42AEDF8F66296D4C3D" charset="UTF-8"></script>
    <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous">
    </script>
    <script src="script.js"></script>
</head>

<style>
    /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 50%;
          width: 50%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>

<body>
    <h1>Steam Statics Explorer</h1>
    <div class="form">
        <form method="POST" action="main.php">
            <label>Enter: Steam ID</label>
            <p>example steam id: 76561198096743032</p>
            <br>
            <input type='text' id='steamid' name='steamid'>
            <button type='submit' id='submit' name='submit'>Search</button>
        </form>
        <?php
if(isset($_POST['submit'])){
    if(!empty($_POST['steamid'])){
        echo "<label><strong> Steamid </strong></label>";
    echo $decoded->response->players[0]->steamid. "<br />";
        echo "<label><strong> personaname </strong></label> <br />";
   echo $decoded->response->players[0]->personaname . "<br />";
        echo "<label><strong> loccountrycode </strong></label> <br />";
   echo $decoded->response->players[0]->loccountrycode. "<br />";
        echo "<label><strong> avatarmedium </strong></label> <br />";
    echo $decoded->response->players[0]->avatarmedium. "<br />";
        echo "<label><strong> profileurl </strong></label> <br />";
    echo $decoded->response->players[0]->profileurl. "<br />";
    }
}
    ?>
         <div class="map">
    <div id="map"></div>
        </div>
    <script>
        var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -37.8136, lng: 144.9631},
          zoom: 4
        });
      }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7ApZYfrCx_mmzhKjvQlDiOAkaTLAQcz8&callback=initMap"></script>
    </div>
</body>
</html>
