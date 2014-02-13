<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
?>
<html>
<body>
<?php
    $_SESSION['username']=$_POST['user_input'];
    $_SESSION['password']=$_POST['pass_input'];
    echo $_SESSION['username'];
    echo $_SESSION['password'];
?>
</body>
</html>