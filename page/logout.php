<?php
 
 @include 'config.php';

 session_start();
 session_unset();
 session_destroy();

 $_SESSION = array();

 header('location:auth/login.php');

?>