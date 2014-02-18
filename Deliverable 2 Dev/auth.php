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
	var userUser = "<?php echo $_SESSION['username']; ?>";
	var newUser = false;

	$.get("GETauth.php",function(JSON){
		var sessid = "<?php echo SID ?>";
		var users = [];
		var passes = [];
		var userPass = "<?php echo md5($_SESSION['password'] . '4509ns;epkgjs3u'); ?>";

		<?php
			require_once 'MDB2.php';
			include "LTF.php"; //to provide $username,$password

			$host='co-project.lboro.ac.uk'; //accesses database
			$dbName='team04';//login
			$dsn = "mysql://$username:$password@$host/$dbName";

			$db =& MDB2::connect($dsn);
			if(PEAR::isError($db)){
				die($db->getMessage());
			}

			$sessid = md5(session_id());
			$usernameSesh = mysql_real_escape_string($_SESSION['username']);
			$sql="UPDATE UserTable SET sessid='$sessid' WHERE username = '$usernameSesh'";
			$db->setFetchMode(MDB2_FETCHMODE_ASSOC);

			$res =& $db->query($sql);
			if(PEAR::isError($res)){
				die($res->getMessage());
			}

		?>

		for(var i=0;i<JSON.length;i++){
			users[i] = JSON[i].username;
			passes[i] = JSON[i].password;
			if (users[i] == userUser && passes[i] == userPass){
					auth=true;
			}
		}
		if (auth == true && newUser==false){
			window.location.replace("viewRequests.php?" + sessid);
		}
		//else if(auth==true && newUser==true){
		//	window.location.replace("accountPage.php?" + sessid);
		//}
		else {
			window.location.replace("index.htm");
		}
	},'JSON');
});
</script>
</head>
<body>
</body>
</html>
