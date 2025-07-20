<?php 
class ActiveUser{
    private $request;
    private $DatabaseManager;
    private $role;
    private $UAPID;
    private $active;
    private $history = [];
    private $user;
    private $name;
    private $hour;
    private $username;
    private $password;
    public function __construct($request){
         $this->request = $request;
         $this->DatabaseManager = new DatabaseManager('');
    }
    public function verify(){

        $this->user = $this->DatabaseManager->checkUserLoginInfo($this->request);

        $this->user = new user($this->user);
        if(is_object($this->user)){
            $this->store();
            $this->DatabaseManager->addDateToActiveUser();
            return true;
        }
        else{
            return false;
        }
    }
    
    public function store(){
        $_SESSION['password'] = $this->request['password'];
        $_SESSION['username'] = $this->request['username'];
        $_SESSION['active'] = "1";
        $this->UAPID = $this->request['password'];
        $this->active = "1";
        $this->username = $this->request['username'];
        $this->password = $this->request['password'];
        if($this->user->getrole() == "admin"){
            $this->role = "admin"; 
            $this->name = $this->user->getfirstName(). " " .$this->user->getlastName();
            $_SESSION['role'] = "admin";    
            $this->DatabaseManager->updateActive($this->active,$this->UAPID); 


        }
        else{
            $this->role = "user";            
            $_SESSION['role'] = "user"; 
            $this->name = $this->user->getfirstName(). " " .$this->user->getlastName();

            $this->DatabaseManager->updateActive($this->active,$this->UAPID); 
        }

    }
    public function setLogout(){
        $this->active = "0";
        $this->UAPID = $this->request['password'];
        $this->DatabaseManager->updateActive($this->active,$this->UAPID);
        $this->DatabaseManager->addLogoutDateToActiveUser($this->UAPID);
    }
    public function setHour($hour){
        $this->hour = $hour;
    }
    public function getHour(){
        return $this->hour;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getUsername(){
        return $this->username;
    }
    public function getName(){
        return $this->name;
    }
    public function getRole(){
        return $this->role;
    }
    public function getUAPID(){
        return $this->UAPID;
    }
    public function getActive(){
        return $this->active;
    }
}