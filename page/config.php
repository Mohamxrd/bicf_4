<?php
session_start();

$conn = new PDO('mysql:host=localhost;dbname=bicf;charset=utf8;', 'root', '');

error_reporting(E_ALL);
ini_set('display_errors', 1);


