<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Redirect if no 'req' parameter is set


// Turn on error reporting
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
*/

include "templates/header.php";
?>

<body>
<?php
include "classes/QueryBuilder.php";
include "classes/User.php";
include "classes/SettingService.php";
include "classes/SettingServiceImpl.php";
include "classes/PageService.php";

spl_autoload_register(function($class){
    $file = "classes/$class.php";
    if (file_exists($file)) {
        include_once $file;
    }
});

error_reporting(E_ALL);
ini_set('display_errors', 1);

$request = $_REQUEST;


if (array_key_exists('image', $_FILES)) {
    $request['image'] = $_FILES['image'];
}
$activeUser = new ActiveUser($_SESSION);
$activeUser->verify();

$AdminView = new AdminView("", $activeUser);
$AdminView->setMenu();

$adminController = new AdminController($request,$activeUser);
$adminController->process();

$AdminView->setFooter();
?>
</body>
</html>
