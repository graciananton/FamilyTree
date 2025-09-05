    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script src="admin/dTree.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
<?php

if(!array_key_exists("req",$_REQUEST)){
    $_REQUEST['req'] = "searchForm";
}

error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include "admin/classes/QueryBuilder.php";
//error_reporting(E_ALL);
    include "templates/header.php";  
    spl_autoload_register(function($class){
        $file = "admin/classes/$class.php";

        if (file_exists($file)) {
            include_once $file;
        }
    });
    
    if (!isset($_REQUEST['req']) || !array_key_exists("req",$_REQUEST)) {
        header("Location: index.php?req=searchForm");
    }  
    if(array_key_exists("display_type",$_REQUEST) || array_key_exists("pageType",$_REQUEST)){
                    echo '<script>window.location.hash = "#result";</script>';
    }
  ?>
  <div style="width: 100%;height:100%;">
    <?php   
        $request = $_REQUEST;
        $validation = new Validation($request);
        if(!$validation->validate()){
            $request['pageType'] = "page_error";
        }
        $Controller = new Controller($request);
        $Controller->process();

        include "templates/footer.php";
    ?>
  </div>
  <script src="js/script.js" type="text/javascript"></script>
