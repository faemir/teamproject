<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
    $_SESSION['username']=$_POST['user_input'];
    $_SESSION['password']=$_POST['pass_input'];
?>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready ( function(){
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
        passes = JSON[i].password;
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
});
</script>
</head>
<body>
<?php

    echo $_SESSION['username'];
    echo $_SESSION['password'];
?>
</body>
</html>
