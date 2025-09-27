<?php
class PythonBridge {
    private $request;
    private $python_path;
    private $AIBiography;
    private $systemInterpreter;
    private $sqlRAG;

    public function __construct($request) {
        $this->request = $request;
    }

    public function process() {
        $host = $this->request['host'];

        if (array_key_exists("req", $this->request) && $this->request['req'] === "sf-generateAIBiography") {
            $type = $this->request['type'];
            $selectedPerson = $this->request['selectedPerson'] ?? "";
            
            if ($host == "localhost") {
                $this->python_path = "C:\\Users\\16134\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
                $this->AIBiography = "C:\\DEV\\Gracian\\familytree\\AIBiography.py";
                $cmd = sprintf(
                    '"%s" "%s" %s %s 2>&1',
                    $this->python_path,
                    $this->AIBiography,
                    escapeshellarg($type),
                    escapeshellarg($selectedPerson)
                );
            } else {
                $this->python_path       = "/kunden/homepages/3/d1017242952/htdocs/familytree/python_modules";
                $this->systemInterpreter = "/usr/bin/python3";
                $this->AIBiography            = "/kunden/homepages/3/d1017242952/htdocs/familytree/AIBiography.py";

                $cmd = sprintf(
                    'PYTHONPATH=%s:$PYTHONPATH %s  %s %s %s 2>&1',
                    escapeshellarg($this->python_path),
                    $this->systemInterpreter,
                    escapeshellarg($this->AIBiography),
                    escapeshellarg($type),
                    escapeshellarg($selectedPerson)
                );
            }
            $person_biographies = shell_exec($cmd);
            echo "<pre>";
            print_r($person_biographies);
            echo "</pre>";
            
            $person_biographies = json_decode($person_biographies, true);

            
            return $person_biographies;

        } else {
            $question = $this->request['message'];

            if ($host == "localhost") {
                $this->python_path = "C:\\Users\\16134\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
                $this->sqlRAG = "C:\\DEV\\Gracian\\familytree\\chatbox.py";

                $cmd = sprintf(
                    '"%s" "%s" %s %s 2>&1',
                    $this->python_path,
                    $this->sqlRAG,
                    escapeshellarg($question),
                    escapeshellarg($host)
                );
            } else {
                $question = escapeshellarg($question);

                $this->python_path       = "/kunden/homepages/3/d1017242952/htdocs/familytree/python_modules";
                $this->systemInterpreter = "/usr/bin/python3";
                $this->sqlRAG            = "/kunden/homepages/3/d1017242952/htdocs/familytree/chatbox.py";

                $cmd = sprintf(
                    'PYTHONPATH=%s:$PYTHONPATH %s %s %s %s 2>&1',
                    escapeshellarg($this->python_path),
                    escapeshellarg($this->systemInterpreter),
                    escapeshellarg($this->sqlRAG),
                    $question,
                    $host
                );
            }

            $output = shell_exec($cmd);
            return $output;
        }
    }
}
