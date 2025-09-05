<?php
include "admin/classes/pythonBridge.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_POST['host'] = $_SERVER['HTTP_HOST'];
    $pythonBridge = new pythonBridge($_POST);
    echo $pythonBridge->process();
}
?>
