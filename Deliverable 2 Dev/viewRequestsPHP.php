This page keeps the session data from the URL.
Where JS variables are needed, they are assigned with PHP echoes.

Nikk, Matt

<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
	$_SESSION["editBool"] = "false";
?>		
	function getUser(){
		passedUsername = "<?php echo $_SESSION['username'] ?>";
		seshId = "<?php echo session_id();?>";
	}
	function validateUser(){
		var user= "<?php echo $_SESSION['username'] ?>";
		var sessionid= "<?php echo session_id(); ?>";
	}