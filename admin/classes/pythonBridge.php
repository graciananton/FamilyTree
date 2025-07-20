<?php
class pythonBridge{
    private $request;


    private $python_path = "C:\\Users\\16134\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
    private $sql_RAG = "C:\\DEV\\Gracian\\familyTree\\chatbox.py";

    //private $sql_RAG = "/kunden/homepages/3/d1017242952/htdocs/familyTree/chatbox.py";

    public function __construct($request){
        $this->request = $request;
    }
    public function process(){

        $question = escapeshellarg($this->request['message']);

        $sql_RAG = $this->sql_RAG;

        $cmd = "\"$this->python_path\" \"$this->sql_RAG\" $question 2>&1";

        //$cmd =  "python3 /kunden/homepages/3/d1017242952/htdocs/familyTree/chatbox.py $question 2>&1";

        $output = shell_exec($cmd);

        $output = json_decode($output,true);
        

        if($output == ""){
            $output = "I am sorry, I do not have enough information to answer that.";
        }

        echo $output;
    }
}