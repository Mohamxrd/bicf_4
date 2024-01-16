<?php
 
 @include('../page/config.php');


 session_start();
 session_unset();
 session_destroy();

 $_SESSION = array();

 header('location: ../page/auth/adlogin.php');

?>
