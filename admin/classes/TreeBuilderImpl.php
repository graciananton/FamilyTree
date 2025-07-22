<?php
class TreeBuilderImpl implements Builder{
    private $DatabaseManager;
    private $cssFileName;
    private SettingService $SettingService;
    private $parents = array();
    public function __construct(){
        $this->DatabaseManager = new DatabaseManager("");
        $this->SettingService = new SettingServiceImpl($this->DatabaseManager);
    }
    private function build(string $pid,string $select, array $request):string{
        $response = $this->SettingService->getSettingValueByName("homeTreeImagePath");
        $Setting = new Setting($response);
        $person = $this->DatabaseManager->getPersonByPid($pid);
        $list = $this->DatabaseManager->getChildrenList($pid,$person->gender);
        $html = '<link rel="stylesheet" href="css/vertical_tree.css" type="text/css" />';
        if ($person) {
            if(count($list) > 0){
                $this->parents[] = $pid;
                $html .= "<li><details open>";
                if($person->pid == $select){
                    $html .= "<summary><mark>" . $person->firstName . "</mark> ";
                }
                else{
                    $tracker = false;
                    for($i=0;$i<count($this->parents);$i++){
                        
                        if(trim($person->psid) == trim($this->parents[$i])){
                            $list = array();
                            $tracker = true;
                            $html .= "<summary>".$person->firstName;
                        }
                    }
                    if($tracker == false){
                        $html .=  "<summary>".$person->firstName;
                    }
                }
                if ($person->psid != 0) {
                    $partner = $this->DatabaseManager->getPersonByPid($person->psid);
            
                    if ($partner) {
                        if($partner->fpid != "0"){
                            $name = $partner->firstName." ".$partner->lastName;
                            if($tracker == true){
                                $html .= " & <a id='partnerWithFather' href='?req=searchForm&display_type=".$request['display_type']."&personName=".$name."&select=".$partner->pid."&pid=".$partner->fpid."'>" . $partner->firstName . " " . $partner->lastName."</a> 
                                             <mark>(Duplicate)</mark>";
                            }
                            else{
                                $html .= " & <a id='partnerWithFather' href='?req=searchForm&display_type=".$request['display_type']."&personName=".$name."&select=".$partner->pid."&pid=".$partner->fpid."'>" . $partner->firstName . " " . $partner->lastName."</a><br/>"; 
                            
                            }
                            $imagePath = $Setting->getValue();
                            $html .= "<a href='?pid={$person->pid}&pageType=page_profile&req=searchForm'><img style='box-shadow: 5px 10px 18px #06635a;' src='admin/img/people/ph_20/" . $person->psid . ".png' id='treePerson' value='" . $person->psid . "' alt=''></a>";

                        }
                        else{
                            $html .=  " & ".$partner->firstName . " " . $partner->lastName;
                        }
                    }
                    $html .= <<<HTML
                    <br/><a href='?pid={$person->pid}&pageType=page_profile&req=searchForm' style='margin-right:6px;'>
                        <img 
                            src='admin/img/people/ph_20/{$person->pid}.png' 
                            id='treePerson'  
                            style='box-shadow: 5px 10px 18px #06635a;'
                            onerror='this.onerror=null; this.src="admin/img/man.png";'  
                            value='{$person->pid}' 
                            alt=''
                        >
                    </a>
                    HTML;
                    $html .= <<<HTML
                    <a href='?pid={$partner->pid}&pageType=page_profile&req=searchForm'>
                        <img 
                            src='admin/img/people/ph_20/{$partner->pid}.png' 
                            id='treePerson'  
                            style='box-shadow: 5px 10px 18px #06635a;'
                            onerror='this.onerror=null; this.src="admin/img/man.png";'  
                            value='{$partner->pid}' 
                            alt=''
                        >
                    </a>
                    HTML;
                }

                $html .="</summary>";
            }
            else{
                $html = "<li>";
                if($person->pid == $select){
                    $html .= "<mark>" . $person->firstName . "</mark> ";
                }
                else{
                    $html .=  $person->firstName;
                }
                if ($person->psid != 0) {
                    $partner = $this->DatabaseManager->getPersonByPid($person->psid);
            
                    if ($partner) {
                        if($partner->fpid != "0"){
                            $html .= "& <a id='partnerWithFather'  href='?req=searchForm&display_type=".$request['display_type']."&personName=".$person->firstName."&select=".$partner->pid."&pid=".$partner->fpid."'>" . $partner->firstName . " " . $partner->lastName."</a>";
                        }
                        else{
                            $html .=  "& ".$partner->firstName . " " . $partner->lastName;
                        }
                    }

                }
                $html .= "<br/>";
            }
            if($person->psid == "0"){
                $html .= <<<HTML
                <a href='?pid={$person->pid}&pageType=page_profile&req=searchForm'>
                    <img 
                        src='admin/img/people/ph_20/{$person->pid}.png' 
                        id='treePerson'  
                        onerror='this.onerror=null; this.src="admin/img/man.png";'  
                        value='{$person->pid}' 
                        style='box-shadow: 5px 10px 18px #06635a;'
                        alt=''
                    >
                </a>
                HTML;
            
            }

            if (!empty($list)) {
                $html .= "<ul>";
                foreach ($list as $child) {
                    $html .= $this->build($child->pid,$select,$request);
                }
                $html .= "</ul></details>";
            }
    
            $html .= "</li>"; // Close details and li here
            
            return $html;
        }
        return ""; // Return empty string if person is not found.
    }
    public function generateTree(string $pid, string $select,array $request):string {
        $tree = "<div id='vertical_tree' style='transform:scale(1.7,1.7);transform:translate(50px,25px);'>
                    <ul id='ul_list'>";
        $tree .= $this->build($pid,$select,$request);
        $tree .= "  </ul>
                 </div>";
        return $tree;
    }

    public function closeTree() {
        $this->DatabaseManager->close();
    }
}
?>
