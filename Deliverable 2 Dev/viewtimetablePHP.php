<?php
  ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
?>
	function getUser(){
		passedUsername = "<?php echo $_SESSION['username'] ?>";
	}

	function validateUser(){
		var user= "<?php echo $_SESSION['username'] ?>";
		var sessionid= "<?php echo session_id(); ?>";
		$.get("GETuserpassdeets.php", {'username':user, 'sessionid':sessionid}, function(JSON){
			if (JSON.length==0)
			window.location.replace("index.htm");
		}, 'json');
	}