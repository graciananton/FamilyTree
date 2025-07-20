<?php
class Config{
    private static $config_file = [];
    private static $servername;
    private static $username;
    private static $password;
    private static $dbname; 

    private static $imageLocation;

    public static function getNavLinksMenuUser(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file['navLinksMenuUser']['navLinksMenuOptionsUser'];
    }
    public static function getNavLinksMenuAdmin(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file['navLinksMenuAdmin']['navLinksMenuOptionsAdmin'];
    }

    
    public static function getNavLinksMenuLogin(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file['navLinksMenuLogin']['navLinksMenuOptionsLogin'];
    }
    public static function getImageLocation(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file['app']['uploadLocation'];
    }
    public static function getDefaultImageLocation(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file['app']['uploadDefaultLocation'];
    }
    public static $FORM_SELECT_OPTION_KEY  ="RelationshipForm";
    public static $FORM_SELECT_OPTION_NAME ="selectRelationShipOptions";

    public static $FORM_PERSON = "PersonForm";
    public static $FORM_SELECT_PERSON = "selectPersonForm";

    public static $FORM_RELATION = "RelationForm";
    public static $FORM_SELECT_RELATION = "selectRelationForm";

    public static $TABLE_PERSON = "PersonTableThs";
    public static $TABLE_SELECT_PERSON = "selectPersonTableThs";

    public static $TABLE_RELATION = "RelationTableThs";
    public static $TABLE_SELECT_RELATION = "selectRelationTableThs";

    public static $TABLE_USER = "UserForm";
    public static $TABLE_SELECT_USER = "selectUserForm";
    public static $APP_INFO = "app";
    public static function getUserForm(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file[self::$TABLE_USER][self::$TABLE_SELECT_USER];
    }
    public static function getAppInfo(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file[self::$APP_INFO];
    }
    public static function getFormOptions(){
        self::$config_file  = parse_ini_file("config/config_form.php", true);
        return self::$config_file[self::$FORM_SELECT_OPTION_KEY][self::$FORM_SELECT_OPTION_NAME];
    }
    public static function getPersonForm(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file[self::$FORM_PERSON][self::$FORM_SELECT_PERSON];
    }
    public static function getRelationForm(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file[self::$FORM_RELATION][self::$FORM_SELECT_RELATION];
    }
    public static function getPersonTableThs(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file[self::$TABLE_PERSON][self::$TABLE_SELECT_PERSON];
    }
    public static function getRelationTableThs(){
        self::$config_file = parse_ini_file("config/config_form.php",true);
        return self::$config_file[self::$TABLE_RELATION][self::$TABLE_SELECT_RELATION];
    }
    public static function init(){
        self::$config_file  = parse_ini_file("config/config_form.php", true);
        if(isset(self::$config_file)){
            if ($_SERVER['SERVER_NAME'] == "localhost"){
                self::$servername = self::$config_file['localhost']['servername'];
                self::$username = self::$config_file['localhost']['username'];
                self::$password = self::$config_file['localhost']['password'];
                self::$dbname = self::$config_file['localhost']['dbname'];

            }
            else{
                self::$servername = self::$config_file['ionos']['servername'];
                self::$username = self::$config_file['ionos']['username'];
                self::$password = self::$config_file['ionos']['password'];
                self::$dbname = self::$config_file['ionos']['dbname'];

            }
        }
    }

    public static function getServerName(){
        return self::$servername;
    }
    public static function getUserName(){
        return self::$username;
    }
    public static function getPassWord(){
        return self::$password;
    }
    public static function getDbName(){
        return self::$dbname;
    }


}
?>