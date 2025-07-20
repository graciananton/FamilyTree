<?php
class Person {
    private $firstName = '';
    private $lastName='';
    private $birthDate='';
    private $gender='';
    private $email='';
    private $phoneNumber='';
    private $address='';
    private $image='';
    private $biography='';
    private $deathDate='';
    private $role = '';
    
    private $request = '';



   //second form submittion
    private $fpid='';//not found -1;
    private $mid='';//not found -1;
    private $psid='';//not found -1;
    private $person='';//not found -1;

    private $pid='';
    private $req ='';
    public function __construct(array $request){
        $this->request = $request;
        $this->setPerson();
        $this->setRelationships();
        if(array_key_exists('req',$this->request) && $this->request['req'] == "sf-delete_person"){
            $this->pid = $request['pid'];
        }
    } 
    private function setPerson(){
        if(array_key_exists('firstName',$this->request)){
            $this->firstName = $this->request['firstName'];
            $this->lastName = $this->request['lastName'];
            $this->birthDate = $this->request['birthDate'];
            $this->gender = $this->request['gender'];
            $this->email = $this->request['email'];
            $this->phoneNumber = $this->request['phoneNumber'];
            $this->address = $this->request['address'];
            if(is_array($this->image)){
                $this->image = $this->request['image']['name'];
            }
            $this->biography = $this->request['biography'];
            if(array_key_exists('deathDate',$this->request)){
                $this->deathDate = $this->request['deathDate'];
            }
            if(array_key_exists('pid',$this->request)){
                $this->pid = $this->request['pid'];
            }   
            if(isset($this->request['image'])){
                $this->image = $this->request['image'];
            }

        } 
        else if(array_key_exists("pid",$this->request)){

            $this->pid = $this->request['pid'];
        } 
    }
    public function showCurrentTime(){
        $today = date('Y-m-d');
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        return $today;
    }
    public function setRelationships(){
        if(array_key_exists("mid",$this->request) || array_key_exists("psid",$this->request)){
            $this->pid = $this->request['pid'];
            $this->psid = $this->request['psid'];
            $this->fpid = $this->request['fpid'];
            $this->mid = $this->request['mid'];
            if(array_key_exists("req",$this->request)){
                $this->req = $this->request['req'];
            }
        }
    }
    public function getReq(){
        return $this->req;
    }
    public function getFirstName(){
        return $this->firstName;
    }
    public function getLastName(){
        return $this->lastName;
    }
    public function getpsid(){
        return $this->psid;
    }
    public function getfpid(){
        return $this->fpid;
    }
    public function getmid(){
        return $this->mid;
    }
    public function getBirthDate(){
        return $this->birthDate;
    }
    public function getGender(){
        return $this->gender;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getImage(){
        return $this->image;
    }
    public function getBiography(){
        return $this->biography;
    }
    public function getDeathDate(){
        return $this->deathDate;
    }
    public function getPid(){
        return $this->pid;
    }
}