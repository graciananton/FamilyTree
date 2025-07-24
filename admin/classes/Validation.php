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
            foreach($this->request as $key=>$value){
                if(preg_match('/^[A-Za-z0-9_\-\+\s]+$/', $key) && preg_match('/^[A-Za-z0-9_\-\+\s]+$/', $value)){
                    // don't return anything
                }
                else{
                    return false;
                }
            }
            return true;
        }
        else{
            return false;
        }
    }
}

