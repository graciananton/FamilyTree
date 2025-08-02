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
    public function setFooter(){
        ?>
            <div class='container-fluid' style='position:relative;bottom:0;box-shadow: 0 -5px 10px -5px #7F4444;'>
                <div class='row' id='footer'>
                    <p style="text-align: center; font-size: 14px; color: black;padding-top:15px;">
                        © <?php echo date("Y"); ?> Family Tree. All rights reserved. Please view our 
                        <a href="index.php?req=searchForm#termsofuse" style='text-decoration:underline;color:#7F4444;'>Terms of Use</a> 
                      & <a href="index.php?req=searchForm#privacynotice" style='color:#7F4444;text-decoration:underline;'>Privacy Notice</a>
                    </p>
                </div>
            </div>
        <?php
    }

    abstract public function render();// an abstract function is simply declared by is replaced by the drive class's function
}