<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href='cssTimetable.css' rel='stylesheet' type='text/css'>
        <title>Team 4 - LU Timetable Login</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">


            function checkusername(){
                var auth=false;
				$.get("GETauth.php",function(JSON){
                    var sessid = "<?php echo SID ?>";
                    var users = "";
                    var passes = "";
                    var userUser = "<?php echo $_SESSION['username']; ?>";
                    var userPass = "<?php echo $_SESSION['password']; ?>";
                    for(var i=0;i<JSON.length;i++){
                        console.log("in the loop");
                        users = JSON[i].username;
                        passes = JSON[i].passes;
                        console.log(userUser);
                        console.log(userPass);
                        console.log(users);
                        console.log(passes);
                        if (users == userUser && passes == userPass)
                            auth=true;
                        }
                        if (auth == true){
                            console.log("authorised");
                            window.location.replace("viewRequests.php?" + sessid);
                        }
				},'JSON');

            }
        </script>
    </head>

    <body>
        <div class="contentBox" id="loginBox">
            <p>Welcome to LU Timetabling System</p>
            <img alt="Home" src="LU-mark-rgb.png">

                <table align="center">
                    <tr>
                        <td><input type="text" placeholder="username" name="user_input"></td>
                    </tr>
                    <tr>
                        <td><input type="password" placeholder="password" name="pass_input"></td>
                    </tr>
                    <?php
                        if (isset($_POST['submit'])){
                            $_SESSION['username']=$_POST['user_input'];
                            $_SESSION['password']=$_POST['pass_input'];
                        }
                    ?>
                    <tr>
                        <td><input type="submit" id="submit" name="submit" value="Submit" onclick="checkusername();"></td>
                    </tr>
                    <tr>
                        <td><a href="#">Forgot Password?</a></td>
                    </tr>
                </table>
        </div>

    </body>
</html>
