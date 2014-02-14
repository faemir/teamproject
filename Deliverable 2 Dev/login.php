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
    </head>

    <body>
        <div class="contentBox" id="loginBox">
            <p>Welcome to LU Timetabling System</p>
            <img alt="Home" src="LU-mark-rgb.png">

                <table align="center">
                    <form action="auth.php" method="post">
                    <tr>
                        <td><input type="text" placeholder="username" value="team04" name="user_input"></td>
                    </tr>
                    <tr>

                        <td><input type="password" placeholder="password" name="pass_input" ></td>

                    </tr>
                    <tr>
                        <td><input type="submit" id="submit" name="submit" value="Submit"></td>
                    </tr>
                    <tr>
                        <td><a href="#">Forgot Password?</a></td>
                    </tr>
                    </form>
                </table>
        </div>

    </body>
</html>
