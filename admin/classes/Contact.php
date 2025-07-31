
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Contact{
    private $request;
    public function __construct($request){
        $this->request = $request;
    }   
    public function send(){

        $to = "gracian.anton@gmail.com";
        $subject = "Error in Family Tree";

        $txt = $this->request['message'];

        $email = $this->request['email'];

        $headers = "From: $email";
        
        if(mail($to,$subject,$txt,$headers)){
            return true;
        }
        else{
            return false;
        }
    }
}