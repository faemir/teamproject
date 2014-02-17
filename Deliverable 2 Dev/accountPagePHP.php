The accountsPage sorts preferences for the review and add request page,
including adding new modules, sorting table headers and defaults.
Nikk Williams

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
}	