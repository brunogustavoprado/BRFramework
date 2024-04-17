<?php
require_once 'framework-br/BR_Framework.php';
session_start();

//br()->conexaobd();
br()->authuser();
br()->vardumpsession();
?>

