<?php
class user{
    private $firstName;
    private $lastName;
    private $phoneNumber;
    private $country;
    private $city;
    private $emailAddress;
    private $role;
    private $UAPID;
    private $createdDateTime;
    private $modifiedDateTime;
    private $loginDateTime;
    private $logoutDateTime;
    private $request;
    private $pid;
    private $history;
    public function __construct($request){
        $this->request = $request;
        $this->setUser();
    }

    public function setUser(){
        $this->firstName = $this->request['firstName'];
        $this->lastName = $this->request['lastName'];
        $this->phoneNumber = $this->request['phoneNumber'];
        $this->country = $this->request['country'];
        $this->city = $this->request['city'];
        $this->emailAddress = $this->request['emailAddress'];
        $this->role = $this->request['role'];
        $this->UAPID = $this->request['UAPID'];
        $this->createdDateTime = date("F j Y");
        if(array_key_exists("history",$this->request)){
            $this->history = $this->request['history'];
        }
        if(array_key_exists('pid',$this->request)){
            $this->pid = $this->request['pid'];
        }
    }
    public function getPid(){
        return $this->pid;
    }
    public function getHistory(){
        return $this->history;
    }
    public function getfirstName(){
        return $this->firstName;
    }
    public function getlastName(){
        return $this->lastName;
    }
    public function getphoneNumber(){
        return $this->phoneNumber;
    }
    public function getcountry(){
        return $this->country;
    }
    public function getcity(){
        return $this->city;
    }
    public function getemailAddress(){
        return $this->emailAddress;
    }
    public function getrole(){
        return $this->role;
    }
    public function getUAPID(){
        return $this->UAPID;
    }
    public function getcreatedDateTime(){
        return $this->createdDateTime;
    }
    public function getmodifiedDateTime(){
        return $this->modifiedDateTime;
    }
    public function getloginDateTime(){
        return $this->loginDateTime;
    }
    public function getlogoutDateTime(){
        return $this->logoutDateTime;
    }
}