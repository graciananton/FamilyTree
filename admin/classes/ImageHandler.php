<?php
class ImageHandler{
    private $image;
    private $pid;
    public function __construct($image,$pid){
        $this->image = $image;
        $this->pid = $pid;
    }

    public function unlinkImages(){
        $folders = $this->getFoldersFromDirectory("img/people");
        for($i=0;$i<count($folders);$i++){
            $folder = $folders[$i];
            $file = "img/people/".$folder."/".$this->pid.".png";
            
            
            if(file_exists($file)){
                if(unlink($file)){
                }
                else{
                }
            }
            else{
            }
            $file = "img/people/default/".$this->pid.".png";

            if(file_exists($file)){
                unlink($file);
            }
            

        }
    }
    public function getFoldersFromDirectory($targetDir){
        $folders = [];
        $items = scandir($targetDir);
        foreach($items as $item){
            if($item != "." && $item != ".." && strpos($item,"ph_") !== false)
               $folders[] = $item;
            
        }
        return $folders;
    }
    public function getFilesFromDefault(){
        $defaultFolder = Config::getDefaultImageLocation();
        $files = array_values(array_diff(scandir($defaultFolder),array('.','..')));
        return $files;

    }
    public function resizeImages($defaultFiles){

        $pythonBridge = new pythonBridge("resizeImages.py",$defaultFiles);
        $output = $pythonBridge->process();
        if ($output === null) {
        } else {
            var_dump($output);
        }
    }
    public function moveImageToDefault(){
        $targetBaseDir = Config::getImageLocation();

        $base_name = basename($this->image['name']);
        $tmp_file_path = $this->image['tmp_name'];

        $pid = $this->pid;

        $defaultDir = $targetBaseDir ."/default/";
        $originalTargetFile = $defaultDir . $pid. ".png";
        if(move_uploaded_file($tmp_file_path,$originalTargetFile)){
        }
        else{
        }
        return $originalTargetFile;
    }
    public function moveFilesByDimensions($originalTargetFile) {


        $originalTargetFileInfo = pathinfo(basename($originalTargetFile));
        $originalTargetFileInfoName = $originalTargetFileInfo['filename'];

        $targetBaseDir = Config::getImageLocation();


        list($width, $height) = getimagesize($originalTargetFile);

        $folders = $this->getFoldersFromDirectory($targetBaseDir);
        $dimensionFolders = [];
        foreach($folders as $folder){
            $folder = str_replace('ph_','',$folder);
            array_push($dimensionFolders,$folder);
            $newWidth = (int) $folder;
            $newHeight = (int) $folder;
            $thumb = imagecreatetruecolor($newWidth,$newHeight);
            $source = imagecreatefrompng($originalTargetFile);
            imagecopyresized($thumb,$source,0,0,0,0,$newWidth,$newHeight,$width,$height);
            $newFileLocation = "img/people/ph_".$folder."/".$originalTargetFileInfoName.".png";
            imagejpeg($thumb,$newFileLocation);
            imagedestroy($thumb);
            imagedestroy($source);
        }
    }
    public function moveFilesToFolders($files,$folderType){
        for($i=0;$i<count($files);$i++){
            $file = (string) $files[$i];
            $file = "img/people/default/".$file;
            $originalTargetFileInfo = pathinfo(basename($file));
            $originalTargetFileInfoName = $originalTargetFileInfo['filename'];

            $extension = strtolower($originalTargetFileInfo['extension']);

            list($width,$height) = getimagesize($file);
            $targetBaseDir = Config::getImageLocation();

            $dimensionFolders = [];
            $folders = $this->getFoldersFromDirectory($targetBaseDir);
            if($folderType == "all"){
                foreach($folders as $folder){
                    $folder = str_replace("ph_","",$folder);
                    array_push($dimensionFolders,$folder);
                    $newWidth = (int) $folder;
                    $newHeight = (int) $folder;


                    $thumb = imagecreatetruecolor($newWidth,$newHeight);
                    if($extension == "jpg" || $extension === "jpeg"){
                        $source = imagecreatefromjpeg($file);
                    }
                    else{
                        $source = imagecreatefrompng($file);
                    }
                    imagecopyresized($thumb,$source,0,0,0,0,$newWidth,$newHeight,$width,$height);
                    $newFileLocation = "img/people/ph_".$folder."/".$originalTargetFileInfoName.".png";

                    if ($extension === "jpg" || $extension === "jpeg") {                    
                        imagejpeg($thumb,$newFileLocation);
                    }
                    else{
                        imagepng($thumb,$newFileLocation);
                    }
                    imagedestroy($thumb);
                    imagedestroy($source);
                }
            }
            else if($folderType == "ph_20" 
                    || $folderType == "ph_100" 
                    || $folderType == "ph_150" 
                    || $folderType == "ph_200" 
                    || $folderType == "ph_50"
                   )
            {
                    $folder = $folderType;
                    $folder = str_replace("ph_","",$folder);

                    array_push($dimensionFolders,$folder);
                    $newWidth = (int) $folder;
                    $newHeight = (int) $folder;


                    $thumb = imagecreatetruecolor($newWidth,$newHeight);
                    if($extension == "jpg" || $extension === "jpeg"){
                        $source = imagecreatefromjpeg($file);
                    }
                    else{
                        $source = imagecreatefrompng($file);
                    }
                    imagecopyresized($thumb,$source,0,0,0,0,$newWidth,$newHeight,$width,$height);
                    $newFileLocation = "img/people/ph_".$folder."/".$originalTargetFileInfoName.".png";

                    if ($extension === "jpg" || $extension === "jpeg") {                    
                        imagejpeg($thumb,$newFileLocation);
                    }
                    else{
                        imagepng($thumb,$newFileLocation);
                    }
                    imagedestroy($thumb);
                    imagedestroy($source);

            }
        }
    }
}