<?php

require 'dbConnection.php';

//Logout the user
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
;
session_destroy();
header("Location: index.php");

?>