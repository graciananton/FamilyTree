<?php
class AdminController extends Controller{
    private $activeUser;
    private $role;
    private $UAPID;
    private $imageHandler;
    private $user;
    public function __construct(array $request,object $activeUser){
        $this->request = $request;
        $this->activeUser = $activeUser;
        $this->role = $activeUser->getRole();
        $this->UAPID = $activeUser->getUAPID();
        $this->req = $request['req'];
        if($this->req != "pf-insert_person" && $this->req != "sf-userTable" && $this->req != "pf-insert_relationship" && $this->req !="pf-display_persons" ){//form submission
            if($this->req == "uts-table" || $this->req == "ufe-table" || $this->req == "ufs-table"){
                $this->user = new user($this->request);
                $this->DatabaseManager = new DatabaseManager($this->user);  
            }
            else if($this->req == "ut-edit"){
                $this->DatabaseManager = new DatabaseManager("");
            }
            /*else if($this->req = "setting"){
                $Setting = new Setting($request)
                $this->DatabaseManager->insertRecords($setting)
            }*/
            else{         
                $this->aPerson = new Person($this->request);
                $this->DatabaseManager = new DatabaseManager($this->aPerson);  
                $this->imageHandler = new ImageHandler("","");  
            }
            

        }
    }
    public function process(){
        if($this->req == "uf-table" && $this->role == "admin"){
            $this->AdminView = new AdminView($this->request,"");
            $this->AdminView->render();
        }
        else if($this->req == 'ut-table'){
            $userInfo = $this->DatabaseManager->getUserInfo();
            $this->AdminView = new AdminView($this->request,$userInfo);
            $this->AdminView->render();
        }
        else if($this->req == "es-table"){
            $this->DatabaseManager = new DatabaseManager("");// send to SettingServiceImpl.php
            $this->SettingService = new SettingServiceImpl($this->DatabaseManager);
            $records = $this->SettingService->getRecords("","");
            $this->AdminView = new AdminView($this->request,$records);
            $this->AdminView->render();
        }
        else if($this->req == 'es-form'){
            $this->DatabaseManager = new DatabaseManager("");
            $this->SettingService = new SettingServiceImpl($this->DatabaseManager);
            $record = $this->SettingService->getRecords("pid",$this->request['pid']);
            $Setting = new Setting($record);
            $this->AdminView = new AdminView($this->request,$Setting);
            $this->AdminView->render();

        }
        else if($this->req == "es-submit") {
            $Setting = new Setting($this->request);

            $this->DatabaseManager = new DatabaseManager($Setting);

            $this->SettingService = new SettingServiceImpl($this->DatabaseManager);

            $this->AdminView = new AdminView("",$this->activeUser);

            if($this->SettingService->updateRecord($Setting)){
                $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();

            }
        }
        else if($this->req == "ut-edit"){
            /// this is the one
            $userInfo = $this->DatabaseManager->getUserInfoBy("pid",$this->request['pid']);
            

            $user = new user($userInfo);
            $this->AdminView = new AdminView($this->request,$user);
            $this->AdminView->render();
        }

        else if($this->req == "ut-delete"){
            $this->AdminView = new AdminView("",$this->activeUser);
            if($this->DatabaseManager->deleteUser($this->request['pid'])){
                    $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();
            }

        }
        else if($this->req == "ufe-table"){
            $this->DatabaseManager->addModifiedDate($this->user);

            $this->AdminView = new AdminView("",$this->activeUser);
            if($this->DatabaseManager->updateUser($this->user)){
                $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();
            }


        }
        else if($this->req == "generateImages"){
            $this->AdminView = new AdminView($this->request,$this->activeUser);
            $this->AdminView->render();
           /* $imageLocation = Config::getImageLocation();            
            $folders = $this->imageHandler->getFoldersFromDirectory($imageLocation);
            $defaultFiles = $this->imageHandler->getFilesFromDefault();
            $this->imageHandler->resizeImages($defaultFiles);
          */
        }
        else if($this->req == "ufs-table" && $this->role == "admin"){
            $this->AdminView = new AdminView("",$this->activeUser);
            if($this->DatabaseManager->saveUser()){
                $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();
            }
        }
        else if(stripos($this->req,"page_") !== false){
            $PageService = new PageService($this->request,$this->activeUser);
            
            $PageService->renderContent();
            $contentText = $PageService->getContentText();
            echo $contentText;
        }
        if($this->req == "pf-insert_person" || $this->req == "pf-insert_relationship" || $this->req == "pf-display_persons"){
            
            $this->AdminView = new AdminView($this->request,$this->activeUser);
            $this->AdminView->render();
        }
        if($this->req == "pf-delete_person"){



            $person = $this->DatabaseManager->getPerson("pid",$this->request['pid']);
            $this->AdminView = new AdminView($this->request,$person);
            $this->AdminView->render();

        }
        if($this->req == "pf-display_persons_edit"){
            $persons = $this->DatabaseManager->getPersons();
            $this->AdminView = new AdminView($this->request,$persons);
            $this->AdminView->render();
        }
        if($this->req == "pf-edit_person"){
            $person = $this->DatabaseManager->getPerson('pid',$this->request['pid']);
            
            $person = new Person($person);
            $this->AdminView = new AdminView($this->request,$person);
            $this->AdminView->render();
        }
        if($this->req == "pf-edit_relationship"){
            $relationship = $this->DatabaseManager->getRelationship("pid",$this->request['pid']);

            $this->AdminView = new AdminView($this->request,$relationship);
            $this->AdminView->render();
        }
        if($this->req == "sf-delete_person"){
            $ImageHandler = new ImageHandler("",$this->request['pid']);

            $ImageHandler->unlinkImages();  

            $this->AdminView = new AdminView("",$this->activeUser);

            
            if($this->DatabaseManager->deletePerson()){
                $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();
            }


            $this->DatabaseManager->saveHistory($this->req,$this->activeUser);
        }
        if($this->req == "sf_insert_person_relationship" || $this->req == "sf_edit_person_relationship"){
            if($this->req == "sf_insert_person_relationship"){
                            $this->DatabaseManager->updateCreatedDate();
            }
            else{
                            $this->DatabaseManager->updateModifiedDate();
            }
            $this->AdminView = new AdminView("",$this->activeUser);

            if($this->DatabaseManager->saveRelationship()){

                $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();
            }
            $this->DatabaseManager->saveHistory($this->req,$this->activeUser);
        
        
        }
        if($this->req == "sf_insert_person_details" || $this->req == "sf_update_person_details"){
            $pid = $this->DatabaseManager->save();
            $this->AdminView = new AdminView("",$this->activeUser);
            if(isset($pid)){

                $this->AdminView->renderSuccessMessage();
            }
            else{
                $this->AdminView->renderErrorMessage();
            }
            $this->DatabaseManager->saveHistory($this->req,$this->activeUser);
            if(!empty($this->request['image']['name'])){
                $ImageHandler = new ImageHandler($this->aPerson->getImage(),$pid);
                $originalTargetFile = $ImageHandler->moveImageToDefault(); 
                $ImageHandler->moveFilesByDimensions($originalTargetFile);   

            }   
        }
    }

}
?>