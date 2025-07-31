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
                if($key != "visit_counter" && $key != "chtl_cus_7996758795"){
                    if(preg_match('/^[A-Za-z0-9_\-\+\s@]+$/', $key) && preg_match('/^[A-Za-z0-9_\-\+\s@]+$/', $value)){
                    // don't return anything
                    }
                    else{
                        return false;
                    }
                }
            }

            return true;
        }
        else{
            return false;
        }
    }
    public function validateEmail(){
        $errors = "";
        $recaptchaSecret = Config::getRecaptchaSecretKey();
        
        $recaptchaResponse = $this->request['g-recaptcha-response'];

        $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
        
        $response = file_get_contents($verifyURL . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
            
        echo $response.'<br/>';
        $responseData = json_decode($response);

        if (!$responseData->success) {
            $errors = "Recaptcha not clicked, ";
        }
        else{
            $errors = "";
        }
        foreach($this->request as $key=>$value){
            if(!preg_match('/[\$\%\^\*\<\>]/', $key) && !preg_match('/[\$\%\^\*\<\>]/', $value)){

            }
            else{
                $errors = $errors. ucfirst($key)." field contains errors, ";
            }
        }
        return $errors;
    }
}

