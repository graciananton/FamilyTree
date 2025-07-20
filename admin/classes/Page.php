<?php
class Page{
    private $title;
    private $content;
    public function __construct($page){
        foreach($page as $key=>$value){
            $method = "set".ucfirst($key);
            $this->$method($value);
        }
    }
    
    public function getTitle(){
        return $this->title;
    }
    public function getContent(){
        return $this->content;
    }
    public function setTitle($title){
        $this->title = $title;
    }
    public function setContent($content){
        $this->content = $content;
    }
}