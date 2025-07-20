<?php
abstract class View{
    protected $req;
    protected $request;
    protected $object;
    protected $DatabaseManager;
    protected SettingService $SettingService;
    protected HomeService $HomeService;
    protected static $MENU_NAME='Menu';
    protected ImageHandler $ImageHandler;
    public function __construct($request,$object){
        $this->object = $object;
        $this->request = $request;
        if(is_array($this->request)){
            $this->req = $this->request['req'];
        }
        $this->DatabaseManager = new DatabaseManager($object);
        $this->SettingService = new SettingServiceImpl($this->DatabaseManager);
        $this->ImageHandler = new ImageHandler('','');
    }
    protected function renderTemplate(string $template):void{
        include "templates/$template.php";
    }
    abstract public function render();// an abstract function is simply declared by is replaced by the drive class's function
}