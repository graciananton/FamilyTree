<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "admin/classes/QueryBuilder.php";
spl_autoload_register(function($class){
    $file = "admin/classes/$class.php";
    if (file_exists($file)) {
        include_once $file;
    }
});

$DatabaseManager = new DatabaseManager("");

$Builder = new TreeBuilderImpl();
$request = $_REQUEST;
if(isset($request['display_type'])){
    $display_type = $request['display_type'];
    print_r($display_type);
}

if(isset($request['pid']) && isset($request['select'])){
    $select = $request['select'];
    $pid = $request['pid'];
    $req = $request['req'];

    $html = $Builder->build($pid,$select);
    $aView = new View($request,$html);
    $aView->process();
}
if(isset($request['pid'])){
    $pid = $request['pid'];
    $person = $DatabaseManager->getPersonByPid($pid);
    $person = json_encode($person);
}
if(isset($request['sValue']) && strlen($request['sValue']) > 0){
    $sValue = $request['sValue'];
    $persons = $DatabaseManager->getPersonsByLetter($sValue,"firstName");  
    $list = array();
    for($i=0;$i<count($persons);$i++){ 
        $person = $persons[$i];
        $list[] = array(
            'pid' => $person->pid,
            'firstName' => $person->firstName,
            'lastName' => $person->lastName
        );
    }
    $list = json_encode($list);
    print_r($list);
}

