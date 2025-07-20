<?php
class Validation{
    private $request;
    public function __construct(array $request){
        $this->request = $request;
    }
    public function validate(){
        if
        (
           is_array($this->request) && 
           array_key_exists("req",$this->request) && 
           isset($this->request['req'])
        )
        {
            return true;
        }
        else{
            return false;
        }
    }
}

