<?php
session_start();
include "classes/ActiveUser.php";
include "classes/DatabaseManager.php";
include "classes/Config.php";
include "classes/QueryBuilder.php";
if($_SESSION['active'] == "1"){
    $_SESSION['active'] = "0";
}
$activeUser = new activeUser($_SESSION);
$activeUser->setLogout();
session_destroy();

header("Location: login.php");
