<?php
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

            <form method="POST">
                <table align="center">
                    <tr>
                        <td><input type="text" placeholder="username" name="user_input"></td>
                    </tr>
                    <tr>
                        <td><input type="password" placeholder="password" name="pass_input"></td>
                    </tr>
                    <tr>
                        <td id="submit"><input type="submit" name="submit" value="Submit"></td>

                        <script type="text/javascript">
                            document.getElementById("submit").onclick = function () {
                                location.href = "viewRequests.htm";
                            };
                        </script>

                    </tr>
                    <tr>
                        <td><a href="#">Forgot Password?</a></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php
            if (isset($_POST['submit'])){
                $_SESSION['username']=$_POST['user_input'];
                $_SESSION['password']=$_POST['pass_input'];
            }
            echo "debug: ";
            echo "username=". $_SESSION['username']. " ";
            echo "password=". $_SESSION['password'];
        ?>
    </body>
</html>
