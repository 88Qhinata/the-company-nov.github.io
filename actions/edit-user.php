<?php

include '../classes/User.php';

$user = new User;

$user->update($_POST, $_FILES);
//$_POST - collect data from the form
//$_FILES - collect the uploading image from the form

?>