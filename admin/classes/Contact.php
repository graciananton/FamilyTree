<?php
class Contact{
    private $request;
    public function __construct($request){
        $this->request = $request;
    }   
    public function process(){
        $to = "basil_anton@yahoo.ca";
        $subject = "Error in Family Tree";
        $txt = "errros";



        $email = $this->request['email'];

        $headers = "From: $email";

        if(mail($to,$subject,$txt,$headers)){
            echo "Mailed";
        }
        else{
            echo "Not mailed";
        }
    }
}