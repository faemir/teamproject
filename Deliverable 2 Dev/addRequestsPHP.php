This page allows timetablers to add a new timetable request.
The session is stored across pages in the URL from login.
The session variables below are passed for the purpose of editing
requests or 'adding similar'.

Session variables are echoed to javascript variables.

Josh and Nikk

<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
	$_SESSION["editreqid"] = $_POST["reqid"];
	$_SESSION["editBool"] = $_POST["bool"];
	$_SESSION["addSim"] = $_POST["similar"];
?>

function getUser(){
	passedUsername = "<?php echo $_SESSION['username']; ?>";
	seshId = "<?php echo session_id();?>";
}

function validateUser(){
	var user= "<?php echo $_SESSION['username'] ?>";
	var sessionid= "<?php echo session_id(); ?>";
}

function isEditreq(){
	editBool = "<?php echo $_SESSION['editBool']; ?>";
	if(editBool == "true"){
		editrequestid = "<?php echo $_SESSION["editreqid"]; ?>";
		addSim = "<?php echo $_SESSION["addSim"]; ?>";
	}
}