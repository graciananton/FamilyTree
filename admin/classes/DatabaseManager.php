<?php
class DatabaseManager{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $request;
    private $con;
    private $aPerson;
    private $list;
    private $queryBuilder;
    private $activeUser;
    public function __construct($object){
        $list='';
        $this->connect();
        if(is_object($object) && get_class($object) !== "Setting" && get_class($object) != "user" && get_class($object) != "ActiveUser" && get_class($object) != "Statistics"){
                $this->aPerson = $object;
                $reflection = new ReflectionClass('Person');
                $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                $list = array();
                for($i=0;$i<count($publicMethods);$i++){
                    $method = $publicMethods[$i];
                    $name = $method->name;
                    
                    if(strpos($name, 'get') !== false){
                        $result = call_user_func([$object, $name]);
                        $name = str_replace('get','',$name);
                        $name = lcfirst($name);
                        $list[$name]=$result;
            
                    }
                } 
                $this->list = $list;
        }
        else if(is_object($object) && get_class($object) == "Statistics"){
            $reflection = new ReflectionClass("Statistics");
            $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            $list = array();
            for($i=0;$i<count($publicMethods);$i++){
                    $method = $publicMethods[$i];
                    $name = $method->name;
                    if(strpos($name,"get") !== false){
                        $result = call_user_func([$object,$name]);
                        $name = str_replace('get','',$name);
                        $list[$name] = $result;
                    }
            }
            $this->list = $list;

        }
        else if(is_object($object) && get_class($object) == 'user'){
                $reflection = new ReflectionClass('user');
                $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                $list = array();
                for($i=0;$i<count($publicMethods);$i++){
                    $method = $publicMethods[$i];
                    $name = $method->name;
                    if(strpos($name,"get") !== false){
                        $result = call_user_func([$object,$name]);
                        $name = str_replace('get','',$name);
                        $list[$name] = $result;
                    }
                }
                
                $this->list = $list;
        }  
        
        $this->queryBuilder = new queryBuilder($object,$list); 
    }
    private function close(){
        if($this->con){
            $this->con->close();
        }
    }
    private function connect(){
        Config::init();
        $this->servername = Config::getServerName();
        $this->username = Config::getUserName();
        $this->password = Config::getPassWord();
        $this->dbname = Config::getDbName();
        $this->con = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if(!$this->con){
            //echo "Invalid Connection obj1";   
        }
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        } else {
        }
    }
    public function findNumberOfIndividuals(){
        $query = $this->queryBuilder->buildQuery("selectPersons","","");
        $result = mysqli_query($this->con,$query);
        return $result->num_rows;
    }
    public function findNumberOfFamilies(){
        $query = $this->queryBuilder->buildQuery("numberOfFamilies","","");
        $result = mysqli_query($this->con,$query);
        return $result->num_rows;
    }
    public function updateUser($user){
        $this->list['modifiedDateTime'] = date("F y j");
        $query = $this->queryBuilder->buildQuery('updateUser',"",$this->list);
        $command = mysqli_query($this->con,$query);
        if($command){return true;}else{return false;}
    }
    public function getPersonsByLetter($param,$criterion){
        $query = $this->queryBuilder->buildQuery("getPersonsByLetter", $criterion, $param);
        $result = $this->con->query($query); // OO style
        $persons = array();
        while($row = mysqli_fetch_object($result)){
            $persons[] = $row;
        }
        return $persons;
    }
    public function checkUserLoginInfo($request){
        $query = $this->queryBuilder->buildQuery("checkUserLoginInfo", "", $request);
        $result = mysqli_query($this->con, $query);
        $user = mysqli_fetch_assoc($result);
        return $user;
    }
    public function getPersonCategoryInfoByDescendants($category,$request,$descendants){
        $personList = array();
        $query = $this->queryBuilder->buildQuery("and_like_category","",$request);
        $result = mysqli_query($this->con,$query);
        $persons = array();
        while($row = mysqli_fetch_object($result)){
            $persons[] = $row;
        }
        for($i=0;$i<count($persons);$i++){
            $person = $persons[$i];
            $ppid = $person->pid;
            for($j=0;$j<count($descendants);$j++){
                $descendant = $descendants[$j];
                $dpid = $descendant->pid;
                if(trim($ppid) == trim($dpid)){
                    array_push($personList,$descendant);
                }
            }
        }
        return $personList;
    }
    public function getPersonCategoryInfo($category,$request){
        $query = $this->queryBuilder->buildQuery("and_like_category","",$request);
        $result = mysqli_query($this->con,$query);
        $persons = array();
        while($row = mysqli_fetch_object($result)){
            $persons[] = $row;
        }
        return $persons;
    }
    public function saveHistory($req,$activeUser){
        $query = $this->queryBuilder->buildQuery("saveHistory", "", "");
        $stmt = $this->con->prepare($query);
        $UAPID = $activeUser->getUAPID();

        if ($req == "sf_insert_person_details") {
            $lastInsertedPid = $this->con->insert_id;
            $history = "i:" . $lastInsertedPid . "," . date("F j y") . "|";
            $stmt->bind_param("si", $history, $UAPID);

        }
        else if($req == "sf_update_person_details"){
            $updatedPid = $this->aPerson->getPid();
            $history = "ep: ". $updatedPid . ",". date("F j y"). "|";
            $stmt->bind_param("si",$history,$UAPID);
        }
        else if($req == "sf_insert_person_relationship"){
                $Pid = $this->aPerson->getPid();
                $history = "ir: ". $Pid .",". date("F j y") ."|";
                $stmt->bind_param("si",$history,$UAPID);

        }
        else if($req == "sf_edit_person_relationship"){
            $Pid = $this->list['pid'];
            $history = 'er: '. $Pid . ",". date("F j y") ."|";
            $stmt->bind_param("si",$history,$UAPID);

        }
        $stmt->execute();

    }
    public function addModifiedDate($user){
        $query = $this->queryBuilder->buildQuery("addModifiedDate",'',$user->getPid());
        $result = mysqli_query($this->con,$query);
    }
    public function addLogoutDateToActiveUser($UAPID){
        $query = $this->queryBuilder->buildQuery('addLogoutDateToActiveUser','',$UAPID);
        $result = mysqli_query($this->con,$query);
    }
    public function addDateToActiveUser(){
        $query =  $this->queryBuilder->buildQuery('addDateToActiveUser',"","");
        $result = mysqli_query($this->con,$query);
    }
    public function getDescendants($pid) {
        $descendants = [];
        $person = $this->getPersonByPid($pid);
        if (!empty($person)) {
            $descendants[] = $person;
    
            // Add the partner if exists
            if ($person->psid != 0) {
                $partner = $this->getPersonByPid($person->psid);
                if (!empty($partner)) {
                    $descendants[] = $partner;
                }
            }
    
            $list = $this->getChildrenList($pid, $person->gender);
            if (!empty($list)) {
                foreach ($list as $child) {
                    $descendants = array_merge($descendants, $this->getDescendants($child->pid));
                }
            }
        }

        return $descendants;
    }
    public function getOrphanChildren(){
        $orphanChildren = [];
        $query = $this->queryBuilder->buildQuery('getOrphanChildren','','');
        $result = mysqli_query($this->con,$query);
        $persons = array();
        while ($row = mysqli_fetch_object($result)) {
            $persons[] = $row;
        }
        return $persons;
    }
    public function selectRelationsWithPid($param){
        $query = $this->queryBuilder->buildQuery("selectRelationsWithPid",'',$param);
        $result = $this->con->query($query);

        $relations = [];
        while($row = $result->fetch_assoc()){
            $relations[] = $row;
        }
        return $relations;
    }
    public function getPersonAndRelationship($param){
        $query = $this->queryBuilder->buildQuery('selectPersonAndRelationship','pid',$param);
        $result = $this->con->query($query);
        $person = $result->fetch_assoc();
        $result->free_result();
        return $person;
    }
    public function getPerson($criterion,$param){
        if($criterion == "pid"){
            $query = $this->queryBuilder->buildQuery('wherePerson','pid',$param);
            $result = $this->con->query($query);
            $person = $result->fetch_assoc();
            $result->free_result();    
            return $person;
        }
        else if($criterion == "gender"){
            $query = $this->queryBuilder->buildQuery('wherePerson','gender',$param);
            $result = mysqli_query($this->con,$query);
            $persons = array();
            while ($row = mysqli_fetch_object($result)) {
                $persons[] = $row;
            }
            return $persons;
        }
        else if($criterion == "and_like"){
            $query = $this->queryBuilder->buildQuery('and_like','',$param);
            $result = mysqli_query($this->con,$query);
            $persons = array();
            while($row = mysqli_fetch_object($result)){
                $persons[] = $row;
            }
            return $persons;
        }

    }
    public function save(){
        if(isset($this->list['pid']) && !empty($this->list['pid'])){
            
            $query = $this->queryBuilder->buildQuery('editPerson','','');
            $command = mysqli_query($this->con,$query);
            
            if($command){
                return $this->list['pid'];
            }
            else{
                return false;
            }
        }
        else if (isset($this->list) && !empty($this->list)){
            $query = $this->queryBuilder->buildQuery('insertPerson','',''); 
            $command = mysqli_query($this->con,$query);
            if($command){
                return $this->con->insert_id;
            }
            else{
                return false;
            }
        }
    }
    public function updateCreatedDate(){

        $query = $this->queryBuilder->buildQuery('updateCreatedDate','pid',$this->list['pid']);
        $command = mysqli_query($this->con,$query);
    }
    public function getDates(){
        $query = $this->queryBuilder->buildQuery("dates","","");
        $result = mysqli_query($this->con,$query);
        $persons = array();
        while($row = mysqli_fetch_assoc($result)){
                $persons[] = $row;
        }
        return $persons;

    }
    public function updateModifiedDate(){
        $query = $this->queryBuilder->buildQuery('updateModifiedDate','pid',$this->list['pid']);
        $command = mysqli_query($this->con,$query);
    }
    public function saveRelationship(){
        if($this->list['req'] == "sf_edit_person_relationship"){
            $query = $this->queryBuilder->buildQuery('editRelation','pid',$this->list['pid']);
        }
        else{
            $query = $this->queryBuilder->buildQuery('insertRelation','','');

        }
        $command = mysqli_query($this->con,$query);
        if($command){
            return true;
        }
        else{
            return false;
        }
    }
    public function getUser($criterion,$param){
        $query = $this->queryBuilder->buildQuery("getUser",$criterion,$param);
        $result = mysqli_query($this->con,$query);
        $user = mysqli_fetch_assoc($result);
        return $user;
    }
    public function updateActive($active,$UAPID){
        $query = $this->queryBuilder->buildQuery('updateActive',$UAPID,$active);
        $command = mysqli_query($this->con,$query);        
    }
    public function saveUser(){
        $query = $this->queryBuilder->buildQuery('sf-userTable','','');
        $command = mysqli_query($this->con,$query);
        if($command){
            return true;
        }
        else{return false;}
    }
    public function getUserInfo(){
        $query = $this->queryBuilder->buildQuery('getUserInfo','','');
        $result = mysqli_query($this->con,$query);
        $users = array();
        while($row = mysqli_fetch_object($result)){
            $users[] = $row;
        }
        return $users;
    }
    public function getUserInfoBy($criterion,$param){
        if($criterion == "pid"){
            $query = $this->queryBuilder->buildQuery("getUserInfoByPid",$criterion,$param);
            $result = mysqli_query($this->con,$query);
            $user = mysqli_fetch_assoc($result);
        }
    
        return $user;
    }
    public function deleteUser($Pid){
        $query = $this->queryBuilder->buildQuery("deleteUser","pid",$Pid);
        $command = mysqli_query($this->con,$query);
        if($command){
            return true;
        }
        else{
            return false;
        }
    }

    public function deletePerson(){
        $param = $this->list['pid'];

        $query1 = $this->queryBuilder->buildQuery('deleteRelationByPid', 'pid', $this->list['pid']);
        $command0 = mysqli_query($this->con, $query1);  

        $sql1 = "UPDATE relation SET fpid = 0 WHERE fpid = $param";
        $sql2 = "UPDATE relation SET mid  = 0 WHERE mid  = $param";
        $sql3 = "UPDATE relation SET psid = 0 WHERE psid = $param"; 
        $command1 = mysqli_query($this->con,$sql1);
        $command2 = mysqli_query($this->con,$sql2);
        $command3 = mysqli_query($this->con,$sql3);

        if($command0 && $command1 && $command2 && $command3){
            $query2 = $this->queryBuilder->buildQuery('deletePerson', 'pid', $param);
            
            $command4 = mysqli_query($this->con,$query2);
            if($command4){
                return true;
            }
            else{
                return false;
            }
        }


    }
    public function getPage($title){
        $query = $this->queryBuilder->buildQuery('selectPage','title',$title);
        $result = mysqli_query($this->con,$query);
        $page = mysqli_fetch_assoc($result);
        return $page;
    }
    public function getPersons(){
        $query = $this->queryBuilder->buildQuery('selectPersons','','');
        $result = mysqli_query($this->con,$query);
        $persons = array();
        while ($row = mysqli_fetch_object($result)) {
            $persons[] = $row;
        }
        return $persons;
    }
    public function updateRecord($Setting){
        
        $query = $this->queryBuilder->buildQuery('updateRecord','',$Setting);
        $result = mysqli_query($this->con,$query);
        if($result){
            return true;
        }
        else{
          return false;  
        }
    }
    public function getPersonsAndRelationships(){
        
        $query = $this->queryBuilder->buildQuery('selectPersonsAndRelationships','','');
        $result = mysqli_query($this->con,$query);
        $persons = array();
        while($row = mysqli_fetch_object($result)) {
            $persons[] = $row;
        }
        return $persons;
    }
    public function getPersonByPid($param){
        $query = $this->queryBuilder->buildQuery('getPersonByPid','',$param);
        $result = mysqli_query($this->con,$query);        
        $row = mysqli_fetch_object($result);
        return $row;
    }
    public function getSettingValueByName(string $name):array{
        $query = $this->queryBuilder->buildQuery('getSettingValueByName','name',$name);
        $result = mysqli_query($this->con,$query);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    public function getPersonWithoutRelationship($param){
        $query = $this->queryBuilder->buildQuery('getPersonWithoutRelationship','',$param);
        $result = mysqli_query($this->con,$query);
        $row = mysqli_fetch_object($result);
        return $row;
    }
    public function getChildrenList($pid,$gender){
        $query = $this->queryBuilder->buildQuery('childrenList',$gender,$pid);
        $result = mysqli_query($this->con,$query);        
        $persons = array();
        while($row = mysqli_fetch_object($result)) {
            $persons[] = $row;
        }
        return $persons;
    }
    public function getRelationship($criterion,$param){
        ///$criterion = 'pid'
        ////$param = 13
        
        if($criterion == ""){
            $query = $this->queryBuilder->buildQuery('selectRelation','','');
            $result = mysqli_query($this->con,$query);
            $persons = array();
            while($row = mysqli_fetch_object($result)){
                $persons[] = $row;
            }
            return $persons;

        }
        if($criterion == "pid"){
            $query = $this->queryBuilder->buildQuery('whereRelation','pid',$param);
            $result = $this->con->query($query);
            $relation = $result->fetch_object();
            $result->free_result();
            $relations = array();
            foreach($relation as $key=>$value){
                $query = $this->queryBuilder->buildQuery("wherePerson",'pid',$relation->$key);
                $result = mysqli_query($this->con, $query);
                while($row = mysqli_fetch_object($result)){
                    $row->type = $key;
                    array_push($relations,$row);
                    
                }
            }

            return $relations;
        }
    }
    public function insert(object $records):bool{
        if(get_class($records) == "Setting"){
            
        }
    }
    public function getRecords($criterion,$param):array{
        if($criterion == ""){
            $query = $this->queryBuilder->buildQuery('selectRecords','','');
            $result = mysqli_query($this->con,$query);
            $records = array();
            while($row = mysqli_fetch_assoc($result)){
                $records[] = $row;
            }
            return $records;
        }
        else if($criterion == "pid"){
            $query = $this->queryBuilder->buildQuery("selectRecordByPid",'pid',$param);
            $result = mysqli_query($this->con,$query);
            $row = mysqli_fetch_assoc($result);
            return $row;
        }
    }


}




