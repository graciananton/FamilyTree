<?php 
class PageService{
    private $activeUser;
    private $DatabaseManager;
    private $html;
    private $contentList;
    private $contentText;
    private $tags;
    private $req_name;
    private $request;
    private $req;
    private SettingService $SettingService;
    private $person;
    private $personPropertyList = [];
    public function __construct($request,$activeUser){
        $this->request = $request;
        //$this->req = $request['req'];
        $this->req = $request['pageType'];
        
        $this->req_name = explode("page_",$this->req,2)[1];
        $this->activeUser = $activeUser;
        $this->DatabaseManager = new DatabaseManager("");
        $this->setContent();
        $this->setTags();
        echo "<link rel='stylesheet' href='css/pageService.css'/>";

        if(array_key_exists("pid",$request)){
            $person = $this->DatabaseManager->getPerson("pid",$request['pid']);
            $this->person = new Person($person);
            $relationPerson = $this->DatabaseManager->getPersonAndRelationship($this->request['pid']);
            $this->relationPerson = new Person($relationPerson);

        }
    }
    private function setContent(){
        $page = $this->DatabaseManager->getPage($this->req_name);
        $page = new Page($page);
        $content = $page->getContent();
        $this->contentList = explode("\n",$content);
        $this->contentText = "<div id='pageService' >";
        $this->contentText .= htmlspecialchars($content);
        $this->contentText .= "</div>";
    }
    private function setTags(){
        $tags = [];
        foreach($this->contentList as $contentLine){
            if(preg_match_all('/#(\w+)#/',$contentLine,$matches)>0){
                $match = $matches[1];
                for($i=0;$i<count($match);$i++){
                    $tags[] = $match[$i];
                }
            }
        }
        $this->tags = $tags;
    }
    public function renderContent():void{
        if(isset($this->person) && is_object($this->person)){
            $reflection = new ReflectionClass($this->person);
            $properties = $reflection->getProperties();
            foreach($properties as $property){
                $personPropertyList[] = $property->name;                
            }
            foreach($this->tags as $tag){
                foreach($personPropertyList as $property){
                    if(trim($property) == trim($tag)){    
                        $method = "set".ucfirst($property);
                        if(method_exists($this,$method)){
                            $this->contentText = str_replace("#".$tag."#",$this->$method(),$this->contentText);
                        }
                    }
                }
            }
        }
        else if($this->req_name == "home"){
            foreach($this->tags as $tag){
                if($this->activeUser->getRole() == "admin"){
                    if(trim($tag) != "UAPIDPerson" & trim($tag) != "ActionHistory"){
                        $method = "set".ucfirst($tag);
                        if(method_exists($this,$method)){
                        
                            $this->contentText = str_replace("#".$tag."#",$this->$method(),$this->contentText);
                        }
                    }
                    else{
                        $this->contentText = str_replace("#".$tag."#","",$this->contentText);
                        $this->contentText = str_replace("You have added/edited/deleted the following individuals to our system:","",$this->contentText);
                    }
                }
                else{
                        $method = "set".ucfirst($tag);
                        if(method_exists($this,$method)){
                        
                            $this->contentText = str_replace("#".$tag."#",$this->$method(),$this->contentText);
                        }
                    
                }
            }
        }
        else if($this->req_name == "error"){
            $this->contentText = $this->DatabaseManager->getPage("error")['content'];
        }
    }
    public function setFirstName(){
        return $this->person->getFirstName();

    }
    public function setLastName(){
        return $this->person->getLastName();
        

    }
    public function setImage(){
        $this->SettingService = new SettingServiceImpl($this->DatabaseManager);
        $personImagePath = $this->SettingService->getSettingValueByName("profile_pic");
        $personImagePath = new Setting($personImagePath);
        $personImagePath = $personImagePath->getValue().$this->person->getPid().".png";
        return $personImagePath;
    }
    public function setBirthDate(){
        return $this->person->getBirthDate();
    }
    public function setDeathDate(){
        if($this->person->getDeathDate() == ""){
            return "?";
        }
        return $this->person->getDeathDate();
    }
    public function setGender(){
        return $this->person->getGender();
    }
    public function setBiography(){
        return $this->person->getBiography();
    }
    public function setEmail(){
        return $this->person->getEmail();
    }
    public function setPhoneNumber(){
        return $this->person->getPhoneNumber();
    }
    public function setAddress(){
        return $this->person->getAddress();
    }

    public function setPsid(){
        print_r($this->request);

        
        if($this->relationPerson->getpsid() != "0"){
            $partner = $this->DatabaseManager->getPerson("pid", $this->relationPerson->getpsid());
            $partner = new Person($partner);
            $name = "<a href='?req=searchForm&display_type=horizontal&personName=" 
                    . $partner->getFirstName() . " " . $partner->getLastName() 
                    . "&select=" . $partner->getPid() . "&pid=" . $partner->getPid() 
                    . "'>" . $partner->getFirstName() . "</a>";
        } else {
            $name = "N/A";
        }
        return $name;
    }

    public function setMid(){
    if($this->relationPerson->getmid() != "0"){
        $mother = $this->DatabaseManager->getPerson("pid", $this->relationPerson->getmid());
        $mother = new Person($mother);
        $name = "<a href='?req=searchForm&display_type=horizontal&personName=" 
                . $mother->getFirstName() . " " . $mother->getLastName() 
                . "&select=" . $mother->getPid() . "&pid=" . $mother->getPid() 
                . "'>" . $mother->getFirstName() . "</a>";
    } else {
        $name = "N/A";
    }
    return $name;
}

public function setFpid(){
    if($this->relationPerson->getfpid() != "0"){
        $father = $this->DatabaseManager->getPerson("pid", $this->relationPerson->getfpid());
        $father = new Person($father);
        $name = "<a href='?req=searchForm&display_type=horizontal&personName=" 
                . $father->getFirstName() . " " . $father->getLastName() 
                . "&select=" . $father->getPid() . "&pid=" . $father->getPid()
                . "'>" . $father->getFirstName() . "</a>";
    } else {
        $name = "N/A";
    }
    return $name;
}

    public function setEditableIndividuals(){
        $html = "";
        if($this->activeUser->getRole() == "user"){
            $descendants = $this->DatabaseManager->getDescendants($this->activeUser->getUAPID());
            $orphans = $this->DatabaseManager->getOrphanChildren();
            $persons = array_merge($descendants,$orphans);
        }
        else if($this->activeUser->getRole() == "admin"){
            $persons = $this->DatabaseManager->getPersons();
            //$persons = $this->DatabaseManager->getPersons();
        }
        $html .= "<ul id='beforeDropdown' class='custom-summary mb-0'>";
                        for($i=0; $i < count($persons); $i++){
                            $person = $persons[$i];
                            $html .= '<li>';
                            $html .= $person->firstName . " " . $person->lastName;
                            $html .= '</li>';
                        }
        $html .= "</ul>";

        return $html;
    }
    public function setActionHistory(){
        $user = $this->DatabaseManager->getUser("emailAddress",$this->activeUser->getUsername());
        $user = new User($user);
        $history = $user->getHistory();
        $actionHistory = "";
        $history = explode("|",$user->getHistory());
        foreach($history as $element){
            if (preg_match('/(?:ep|i):\s*(\d+)/', $element, $match)) {
                $ids[] = $match[1]; // Extract just the number
            }
            if(preg_match("/ep|i/i",$element,$match)){
                $actions[] = $match[0];
            }
        }
        for($i=0;$i<count($ids);$i++){
            $person = $this->DatabaseManager->getPerson("pid",$ids[$i]);
            if(is_object($person)){
                $person = new Person($person);
                $ids[$i] = $person->getFirstName()." ".$person->getLastName();
            }
        }

        for($g=0;$g<count($ids);$g++){
            $id = $ids[$g];
            $person = $this->DatabaseManager->getPerson("pid",$id);
            if(!is_array($person)){continue;}

            $person = new Person($person);

            if($actions[$g]== "ep"){
                $actionHistory .= "<li>".$person->getFirstName()." - Edited Person</li>";                                        
            }
            else if($actions[$g] == "i"){
                $actionHistory .= "<li>".$person->getLastName()." - Inserted Person</li>";
            }
                                    
        }
        return $actionHistory;
    }
    public function setUAPIDPerson(){
        $UAPIDPerson = $this->DatabaseManager->getPerson("pid",$this->activeUser->getUAPID());
        $UAPIDPerson = new Person($UAPIDPerson);
        $html = "
        <div class='col-md-2'>
        <div class='fw-semibold'>
                You can edit all people who are descendants of:
            </div>
            <ul>";
        $html .="<li>";
        $name = trim($UAPIDPerson->getFirstName())." ".trim($UAPIDPerson->getLastName());
        $html .= $name;
        $html .= "</li>";
        $html .= "</ul></div>";

        return $html;
    }
    public function setActionLinks() {
        if ($this->activeUser->getRole() == "admin") {
            $actionLinks = Config::getNavLinksMenuAdmin();
        } else if ($this->activeUser->getRole() == "user") {
            $actionLinks = Config::getNavLinksMenuUser();
        }

        $actionLinks = explode(",", $actionLinks);
        $html = "";

        foreach ($actionLinks as $link) {
            $parts = explode("=>", $link);

            // Skip malformed entries
            if (count($parts) != 2) continue;

            $actionLinkName = trim($parts[0]);
            $actionLinkReq = trim($parts[1]);

            $html .= "<li><a href='?req=" . htmlspecialchars($actionLinkReq) . "'>" . htmlspecialchars($actionLinkName) . "</a></li>";
        }
        return $html;
    }

    public function setIntroduction(){
        $user = $this->DatabaseManager->getUser("emailAddress",$this->activeUser->getUsername());
        $user = new User($user);

        $Statistics = new Statistics();
        $Statistics->findHour();
        $hour = $Statistics->getHour();
        if($hour >= 0 && $hour <= 12){
            $timeOfDay = "Good Morning";
        }
        else if($hour > 12 && $hour <= 16){
            $timeOfDay = "Good Afternoon";
        }
        else if($hour > 16 && $hour <= 24){
            $timeOfDay = "Good Evening";
        }
        $introduction = $timeOfDay.", ".$user->getFirstName();
        return $introduction;
    }
    public function getContentText(){
        return htmlspecialchars_decode($this->contentText);
    }
}