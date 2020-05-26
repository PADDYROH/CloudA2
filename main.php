<!--html/php-->
<?php
session_start();

ini_set("allow_url_fopen", 1);$jsonDecode = json_decode($jsonData, TRUE);
//game news
$csgo_url = 'http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=730&count=10&maxlength=300&format=json';
$rl_url = 'http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=252950&count=10&maxlength=300&format=json'; 

//Login user stats
$userStats_url = 'http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=C1A9D93B831592B9BA3AF5A0D7F24CD9&steamid='.$_SESSION['steamid'].'&fbclid=IwAR0DJNGLibzm0p92u5TDZKsuQpRxc4mzwAqPWBZQow-r57Yhy_OnO3R0Jfs&format=json';
//storing User stats in json file to use in BigQuery
$jsonStats = file_get_contents($userStats_url);
file_put_contents('gs://a2cloud_userstats/userStats.json', $jsonStats);
$userstatsdecoded = json_decode($jsonStats);       

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
          width: 30%;
      }
      html, body {
        height: 80%;
        margin: 0;
        padding: 0;
      }
    </style>

<body>
    <?php
    if(isset($_SESSION['username'])){
    if(!empty($_SESSION['steamid'])){
        $steamuser = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=C1A9D93B831592B9BA3AF5A0D7F24CD9&steamids='.$_SESSION['steamid'];  
        $json = file_get_contents($steamuser);
        file_put_contents('gs://a2cloud-bucket/steamusers.json', $json);
        $userdecoded = json_decode($json);
        
        //Showing the details of the logged in user with there steamID
    echo "<h1>Welcome " . $userdecoded->response->players[0]->personaname ."</h1>";
         echo "<label><strong> Steam ID: </strong></label> <br />";
    echo $userdecoded->response->players[0]->steamid. "<br />";
        echo "<label><strong> Username: </strong></label> <br />";
   echo $userdecoded->response->players[0]->personaname . "<br />";
        echo "<label><strong> Country: </strong></label> <br />";;
   echo $userdecoded->response->players[0]->loccountrycode. "<br />";
        echo "<label><strong> Profile Picture: </strong></label> <br />";
    echo '<img src= '.$userdecoded->response->players[0]->avatarmedium . "><br />";
        echo "<label><strong> Profile url: </strong></label> <br />";
    echo '<a href=" '. $userdecoded->response->players[0]->profileurl.'">' . $userdecoded->response->players[0]->profileurl.' </a> <br />';
    
            //us: 76561198108540186
            //au: 76561198096743032
    }
}
    
            echo "<h1>" . $userdecoded->response->players[0]->personaname ."'s CSGO Statistics</h1>";
            for($i; $i < 5; $i++){
            preShow($userstatsdecoded->playerstats->stats[$i]);   
            }
            ?>
    <h2>Find Steam Player</h2>
    <!--This form will allow the user to search any player using there steam ID-->
    <div class="form">
        <form method="POST" action="main.php">
            <label>Enter: Steam ID</label>
            <br>
            <input type='text' id='usersteamid' name='usersteamid'>
            <button type='submit' id='submit1' name='submit1'>Search</button>
            <p><i>AU steam id: 76561198096743032</i></p>
            <p><i>EU steam id: 76561197994575504</i></p>
            <p><i>US steam id: 76561198108540186</i></p>
        </form>
        <?php
        if(isset($_POST['submit1'])){
    if(!empty($_POST['usersteamid'])){
        //user profile info
        $user_url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=C1A9D93B831592B9BA3AF5A0D7F24CD9&steamids='.$_POST['usersteamid'];   
        $json = file_get_contents($user_url);
        $decoded = json_decode($json);
        
        //Display the user in relation to there steamID
        echo "<label><strong> Steam ID: </strong></label> <br />";
    echo $decoded->response->players[0]->steamid. "<br />";
        echo "<label><strong> Username: </strong></label> <br />";
   echo $decoded->response->players[0]->personaname . "<br />";
        echo "<label><strong> Country: </strong></label> <br />";
   echo $decoded->response->players[0]->loccountrycode. "<br />";
        echo "<label><strong> Profile Picture: </strong></label> <br />";
     echo '<img src= '.$decoded->response->players[0]->avatarmedium . "><br />";
        echo "<label><strong> Profile url: </strong></label> <br />";
    echo '<a href=" '. $decoded->response->players[0]->profileurl.'">' . $decoded->response->players[0]->profileurl.' </a> <br />';
            //example us: 76561198108540186
            //example au: 76561198096743032
   
        if ($decoded->response->players[0]->loccountrycode == "AU"){
           $LOC = "lat: -37.8136, lng: 144.9631"; // AU
        }elseif ($decoded->response->players[0]->loccountrycode == "US"){
           $LOC = "lat: 37.0902, lng: 995.7129"; //us
        }else{
           $LOC = "lat: 46.5260, lng: -2.2551"; //eu
        }
    }
    }
        ?>
            <!--Displaying the google map-->
            <div id="map"></div>
        <script>
            var map;

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        <?php echo $LOC ?>
                    },
                    zoom: 4
                });
            }

        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7ApZYfrCx_mmzhKjvQlDiOAkaTLAQcz8&callback=initMap"></script>
        </div>
    </body>
</html>