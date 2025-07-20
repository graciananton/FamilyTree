<?php
include 'classes/DatabaseManager.php';
include 'classes/Config.php';
include 'classes/QueryBuilder.php';
include "classes/ImageHandler.php";
$request = $_REQUEST;
if(array_key_exists("req",$request)){
    $req = $request['req'];
}
if(array_key_exists("sfValue",$request)){
    $DatabaseManager = new DatabaseManager($request);
    $persons = $DatabaseManager->getPersonsByLetter($request['sfValue'],$request['selector']);
    $json_persons = json_encode($persons);
    echo $json_persons;
}
else if($req == "sf-generateImages"){
    $folderType = $request['folder'];
    $ImageHandler = new ImageHandler("","");
    $files = $ImageHandler->getFilesFromDefault();
    $ImageHandler->moveFilesToFolders($files,$folderType);
}   
else if($req == "father" || $req =="mother" || $req == "partner"){
    $DatabaseManager = new DatabaseManager($request);

    $relative = $DatabaseManager->getPerson('and_like',$request);


    $json_relative = json_encode($relative);
    print_r($json_relative);
}
else{
    $activeUser = $request['activeUser'];
    $category = $request['category'];
    $sValue = strtolower(str_replace(" ","",$request['sValue']));
    $DatabaseManager = new DatabaseManager($request);
    if(isset($activeUser['role']) && $activeUser['role']== "user"){
       $descendants = $DatabaseManager->getDescendants($activeUser['UAPID']);
       $orphanChildren = $DatabaseManager->getOrphanChildren();
     
       $individuals = array_merge($descendants,$orphanChildren);
       if(ctype_alpha($sValue)){
            $persons = [];
            for($i=0;$i<count($individuals);$i++){
                    $individual = $individuals[$i];
                    $element = strtolower(str_replace(" ","",$individual->firstName));
                    if(strpos($element,$sValue) !== false){
                        $persons[] = $individual;
                    }
            }
       }
       else{
        $persons = $individuals;
       }
    }
    else{
        $persons = $DatabaseManager->getPersonCategoryInfo($request['category'],$request);
    }
    $json_persons = json_encode($persons);
    echo($json_persons);
}


