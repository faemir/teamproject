Upon the user clicking logout, this page destroys the stored session
and pushes them back to the login page.

Dan and Matt

<?php
session_start();
session_destroy();
header( 'Location: index.htm' ) ;
?>