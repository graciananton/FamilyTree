<?php
include "admin/classes/pythonBridge.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pythonBridge = new pythonBridge($_POST);
    $pythonBridge->process();
}
?>
