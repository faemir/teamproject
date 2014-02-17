This page is passed the username and password inputted by the user, and then
checks it against the database table of users. The passwords are
encrypted by a 256 hash and salt for security purposes.

The session variables stored are the user-inputted user and password.

Where JS is needed, the variables are assigned by echoing the PHP values.

Daniel Cohen

<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
    $_SESSION['username']=$_POST['user_input'];
    $_SESSION['password']=$_POST['pass_input'];
?>
$(document).ready ( function(){
	var userUser = "<?php echo $_SESSION['username']; ?>";
	$.get("GETauth.php",function(JSON){
		var sessid = "<?php echo SID ?>";
		var userPass;
		userPass="<?php echo md5($_SESSION['password']. '4509ns;epkgjs3u');?>";
		
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
			$sql="UPDATE UserTable SET sessid='$sessid' ";
			$sql.="WHERE username = '$usernameSesh'";
			$db->setFetchMode(MDB2_FETCHMODE_ASSOC);

			$res =& $db->query($sql);
			if(PEAR::isError($res)){
				die($res->getMessage());
			}

		?>
	},'JSON');
});
</script>
</head>
<body>
</body>
</html>
