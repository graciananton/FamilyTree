<?php
class Controller{
    private $request;
    private $req;
    private $pid;
    private $select;
    private $aView;
    private $DatabaseManager;
    private $aPerson;
    private $user;
    private SettingService $SettingService;

    public function __construct($request){
        $this->DatabaseManager = new DatabaseManager("");
        $this->request = $request;
        $this->req = $request['req'];
        
        if(array_key_exists("pid",$request)){
            $this->pid = $request['pid'];

        }
        if(array_key_exists("select",$request)){
            $this->select = $request['select'];
        }
    }   
    public function process(){
        if($this->req == "searchForm" || $this->req == ""){
            $statistics = new Statistics();

            $individuals = $statistics->findNumberOfIndividuals();
            $families = ceil(($statistics->findNumberOfFamilies())/2);
            if(stripos($this->request['pageType'],"page_") !== false){
                $PageService = new PageService($this->request,"");
                $PageService->renderContent();
                $form = $PageService->getContentText();
            }

            else if($this->request['pageType'] == "page_profile"){
                    $PageService = new PageService($this->request,"");
                    $PageService->renderContent();
                    $form = $PageService->getContentText();

            }

            else if(array_key_exists("display_type",$this->request)){
                if($this->request['display_type'] == "vertical"){
                    $Builder = new TreeBuilderImpl();
                }
                else{
                    $Builder = new FamilyBuilderImpl();
                }
                $form = $Builder->generateTree($this->pid,$this->select,$this->request);
            }
        }

        else if($this->req == "contact"){
            $contact = new Contact($this->request);

            $validation = new Validation($this->request);

                $errors  =  $validation->validateEmail();
                if($errors == ""){
                    $contact->send();
                    
                    $this->request['successMessage'] = 1;
                    $this->request['req'] = "searchForm";
                    $this->request['pageType'] ="";

                    
                    $HomeView = new HomeView($this->request,"");

                    $HomeView->setMainLinksNavMenu();

                    $HomeView->render();

                    $HomeView->setFooter();

                    return;
                }
                else{
                    $this->request['successMessage'] = 0;
                    $this->request['errors'] = $errors;
                    $this->request['pageType'] = "";
                    $this->request['req'] = "searchForm";
                    
                    
                    $HomeView = new HomeView($this->request,"");
                    $HomeView->setMainLinksNavMenu();

                    $HomeView->render();

                    $HomeView->setFooter();

                    return;
                }
            $form = "";

        }
        else if(stripos($this->request['pageType'],"page_") !== false){
            $PageService = new PageService($this->request,"");
            $PageService->renderContent();
            $form = $PageService->getContentText();
        }

        else if($this->req == "termsofuse" || $this->req == "privacynotice"){
            $form = "";
        } 
        
        $HomeView = new HomeView($this->request,$form);

        echo "<div id='navbar'>";
                $HomeView->setMainLinksNavMenu();
        
        echo "</div>";


        $HomeView->render();
        $HomeView->setFooter();
    }
}
?>