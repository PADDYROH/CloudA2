<?php
session_start();
?>
<html>

<body>
<h2>Login</h2>
    <br>
    <form name="login" method="POST" action="login.php" onsubmit="return validateform()">
        <label for="username1">Username</label>
        <input type="text" name="usernamel" id="usernamel" class="input-login" required>
        <br />
        <label for="password1">Password</label>
        <input type="password" name="passwordl" id="passwordl" class="input-login" required>
        <br />
        <p>Don't have an account? <a href="register.php">Register</a></p><br>
        <button id="submit" type="submit">Login</button>
    </form>
    <script>
        function validateform() {
            var x = document.forms["login"]["usernamel"].value;
            var y = document.forms["login"]["passwordl"].value;
            if (x == "") {
                alert("User name or password cannot be empty");
                return false;
            }
            if (y == "") {
                alert("User name or password cannot be empty");
                return false;
            }
        }

    </script>
    <?php
    //CREATING ARRAY------------------------------
    if(!empty($_POST['username'])){
    $user = array(
        "username" => $_POST['username'],
        "password" => $_POST['password'],
        "steamid" => $_POST['steamid']);
    }
    //LOGIN-----------------------------------------------------    
    if(!empty( $_POST['usernamel'])){
    $_SESSION["username"] = $_POST['usernamel'];
        $exist = 0;
        //check if the input exist
        $string_data = file_get_contents("gs://a2cloud-bucket/users.txt");
        $explode = explode("|", $string_data);
        $count = count($explode);
        for($i = 0; $i < $count; $i++){
            $array = unserialize($explode[$i]);
           // preShow($array);
            if ( $array['username'] == $_POST['usernamel']){
                if($array['password'] == $_POST['passwordl'] ){
                $exist = 1;
                    $_SESSION['steamid'] = $array['steamid'];
                    //echo $array['steamid'];
                }
            }
            }
    if ($exist == 1){
         echo "<script>location.href='main.php';</script>";
    }
       if ($exist == 0){
    }
        }
         //REGISTER-------------------------------------------
    if (!empty($_POST['username'])){
            $exist = 0;
        //check if the input exist
        $string_data = file_get_contents("gs://a2cloud-bucket/users.txt");
        $explode = explode("|", $string_data);
        $count = count($explode);
        for($i = 0; $i < $count; $i++){
            $array = unserialize($explode[$i]);
           // echo "count: " . $i;
           // preShow($explode[$i]);
            if ( $array['username'] == $_POST['username']){
                 $exist = 1;
                
            }
        }
    
    if($exist == 1){
	}else{
        $input = serialize($user);
        $content = file_get_contents("gs://a2cloud-bucket/users.txt");
        $new_content = $content ."|".$input;
        //echo $new_content;
        file_put_contents("gs://a2cloud-bucket/users.txt", $new_content);
        echo "<label>"."User added"."</label>";
    }
    }
    
    //Display array function-------------------------------------------
function preShow( $arr, $returnAsString=false ) {
  $ret  = '<pre>' . print_r($arr, true) . '</pre>';
  if ($returnAsString)
    return $ret;
  else 
    echo $ret; 
}
?>
    </body>
</html>