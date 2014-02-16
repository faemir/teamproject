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
        <title>Help Page</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
		$(document).ready(function(){validateUser();});
		
			function validateUser(){
				var user= "<?php echo $_SESSION['username'] ?>";
				var sessionid= "<?php echo session_id(); ?>";
				$.get("GETuserpassdeets.php", {'username':user, 'sessionid':sessionid}, function(JSON){
					if (JSON.length==0)
					window.location.replace("login.php");
				}, 'json');
			}	
		
		</script>
    </head>

    <body>
        <div id="navwrap">
            <ul id="topnav">
                <li><a href="viewRequests.php"><img src="LU-mark-rgb.png" alt="Home"></a></li>
                <li><a href="addRequests.php">Add New Requests</a></li>
                <li><a href="viewTimetable.php">View Timetable</a></li>
                <li><a href="helpPage.php">Help</a></li>
                <li><a href="accountPage.php">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div id="pagewrap">


        </div>
    </body>
</html>
