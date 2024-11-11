<?php
include '../classes/User.php';

//Creat an obj
$user = new User;

//Call the method
$user->login($_POST);

?>