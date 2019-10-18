<?php
session_start();
?>
<html>

<body>

    <h2>Register</h2>

    <form action="login.php" name="register" method="POST" onsubmit= "return validateform()" oninput="CheckPassword()">
        <label>Username:</label>
        <input type="text" name="username" id="username">
        <br /><br />
        <label>Password:</label>
        <input type="text" name="password" id="password">
        <br /><br />
        <label>SteamID:</label>
        <input type="text" name="steamid" id="steamid">
        <br /><br />
        <button id="register" type="submit" onclick="IsEmpty()" disabled>Register</button>
    </form>
    <script type="text/jscript">
        function CheckPassword() {
            password = document.getElementById('password').value;
            if (password.length < 6) {
                document.getElementById('submit').disabled = true;
                document.getElementById('submit').innerHTML = "Register";
            } else {
                document.getElementById('submit').disabled = false;
                document.getElementById('submit').innerHTML = "Register";
            }
        }
        
        function validateform(){
        var x = document.forms["register"]["username"].value;
        if(x==""){
        alert ("User name or password cannot be empty");
        return false;
        }
        }
    </script>
    <i>Username and password required</i>
</body>

</html>
