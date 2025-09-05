
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



        $email = $this->request['email'];

        $headers = "From: $email";

        $to      = 'gracian.anton@gmail.com'; // use a real mailbox you can read
        $subject = 'Error in Family Tree';
        $body    = $this->request['message']."\n\n\n".$email;
        $headers = "From: noreply@gracian.ca\r\n".
                "Reply-To: noreply@gracian.ca\r\n".
                "MIME-Version: 1.0\r\n".
                "Content-Type: text/plain; charset=UTF-8\r\n";

        
        $ok = mail($to, $subject, $body, $headers, '-fnoreply@gracian.ca');

            /*if(mail($to,$subject,$txt,$headers)){
                echo "true";
            }
            else{
                echo "false";
            }*/
    }
}