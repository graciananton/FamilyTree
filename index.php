    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script src="admin/dTree.min.js"></script>
<?php   
error_reporting(0);
echo "Index.php";
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/
include "admin/classes/QueryBuilder.php";
//error_reporting(E_ALL);
    include "templates/header.php";  
    spl_autoload_register(function($class){
        $file = "admin/classes/$class.php";

        if (file_exists($file)) {
            include_once $file;
        }
    });
    
    if (!isset($_REQUEST['req'])) {
        header("Location: index.php?req=searchForm");
        exit();
    }  
  ?>
  <div style="width: 100%;height:100%">
    <?php   
      $request = $_REQUEST;

      $validation = new Validation($request);
    if(!$validation->validate()){
        $request['req'] = "page_error";
    }
    $Controller = new Controller($request);
    $Controller->process();

    include "templates/footer.php";
    ?>
  </div>
  <script src="js/script.js" type="text/javascript"></script>
