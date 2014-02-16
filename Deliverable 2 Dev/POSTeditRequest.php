<?php
	$id= $_GET["id"];
	header('Location: addRequests.php?requestID=<?php echo $id ?>');

?>