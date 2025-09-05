<?php
session_start();

include "classes/DatabaseManager.php";
include "classes/TimeStamp.php";
include "classes/Config.php";
include "classes/QueryBuilder.php";
include "classes/ActiveUser.php";
include "templates/header.php";
include "classes/View.php";
include "classes/AdminView.php";
include "classes/User.php";
include "classes/Setting.php";
include "classes/SettingService.php";
include "classes/SettingServiceImpl.php";
include "classes/ImageHandler.php";
$activeUser = "";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $request = $_REQUEST;
        $_SESSION['password'] = $request['password'];
        $_SESSION['username'] = $request['username'];
        $activeUser = new ActiveUser($request);
        if($activeUser->verify()){
            echo '<meta http-equiv="refresh" content="0;url=index.php?req=home">';          
        }
        else{
            echo "Password or Username is incorrect";
        }
}
$AdminView = new AdminView("", $activeUser);
$AdminView->setMenu();

?>
<div class='ml-10 p-5'>
    <form action="" enctype='multipart/form-data' id='login' method='post'>
        <div class='form-group'>
            <label for='username' class='form-label'>Username:</label>
            <input type='input' id='username' class='form-control w-25' name='username' value='basil_anton@yahoo.ca'/>
        </div>
        <div class='form-group'>
            <label for='password' class='form-label'>Password:</label>
            <input type='input' id='password' class='form-control w-25 mb-3' name='password' value='2008'/>
        </div>
        <input type='submit' id='submit' value='Submit' name='submit'/>
        <input type='hidden' name='submit_form' value='submit_form'/>
    </form>
</div>
    <div class='container-fluid' style='position:absolute;bottom:0;box-shadow: 0 -5px 10px -5px #7F4444;'>
        <div class='row'>
            <p style="text-align: center; font-size: 14px; color: black;padding-top:15px;">
                © <?php echo date("Y"); ?> Family Tree. All rights reserved. Please view our <a href="/gracian/familyTree/index.php?req=termsofuse" style='text-decoration:underline;color:#7F4444;'>Terms of Use</a> & <a href="/gracian/familyTree/index.php??req=privacynotice" style='color:#7F4444;text-decoration:underline;'>Privacy Notice</a>
            </p>
        </div>
    </div>
