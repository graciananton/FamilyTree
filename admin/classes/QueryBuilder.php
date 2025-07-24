<?php 
class queryBuilder{
    private $list;
    private $propertyList;
    public function __construct($aPerson,$propertyList){
        $this->list = $aPerson;
        $this->propertyList = $propertyList;
    }
    public function buildQuery($queryName,$criterion,$param){ 
        if($queryName == "insertPerson"){
            
            $columns = Config::getPersonForm();
            $columns = explode(",",$columns);

            $values = [];
            for($i=0;$i<count($columns);$i++){
                    $values[] = "'".$this->propertyList[$columns[$i]]."'";
            }
            $columns_string = implode(",",$columns);
            $values_string = implode(",",$values);
            $sql = "INSERT INTO person($columns_string) VALUES($values_string)";
        }   
        else if($queryName == "updateRecord"){
            $sql = "UPDATE setting SET 
                name = '" . addslashes($param->getName()) . "', 
                value = '" . addslashes($param->getValue()) . "',
                modifiedDateTime = '".addslashes($param->getmodifiedDateTime())."'
                WHERE pid = " . intval($param->getPid());
       }

       else if($queryName == "getUser"){
            $sql = "SELECT * FROM user WHERE " . $criterion . " = '".$param."'";
       }
       else if($queryName == "getSettingValueByName"){
           $sql = "SELECT * FROM setting WHERE " . $criterion . " = '" . $param . "'";

       }
        else if($queryName == "selectRecordByPid"){
            $sql = "SELECT * FROM setting WHERE ".$criterion. " = ".$param;
        }
        else if($queryName == "getPersonsByLetter"){
            $sql = "SELECT * FROM person WHERE ".$criterion." LIKE '".$param. "%'";
        }
        else if($queryName == "getUserInfoByPid"){
            $sql ="SELECT * FROM user WHERE ".$criterion." = ".$param;
        }
        else if($queryName == "deleteUser"){
            $sql = "DELETE FROM `user` WHERE $criterion = $param";
        }   
        else if($queryName == 'updateUser'){
            $sql = "UPDATE user SET 
                    firstName = '".$param['firstName']."',".
                    "lastName = '".$param['lastName']."',".
                    "phoneNumber = '".$param['phoneNumber']."',".
                    "country = '".$param['country']."',".
                    "city = '".$param['city']. "',".
                    "emailAddress = '".$param['emailAddress']."',".
                    "role = '".$param['role']. "',". 
                    "modifiedDateTime = '".$param['modifiedDateTime']. "',".
                    "UAPID = '".$param['UAPID'] . "' WHERE pid = ".$param['Pid'];

        }
        else if($queryName == "selectRecords"){
            $sql = "SELECT * FROM setting";
        }
        else if($queryName == "and_like"){
            $sql = "SELECT ";
            for ($i = 0; $i < count($param['relationshipList']); $i++) {
                $columns[] = $param['relationshipList'][$i]; 
            }
            $sql .= implode(", ", $columns);
            $sql .= " FROM person WHERE firstName LIKE '" . $param['letter'] . "%'";
        }
        else if($queryName == "saveHistory"){
            $sql = "UPDATE user SET history = CONCAT(history, ?) WHERE UAPID = ?";
        }
        else if($queryName == "addDateToActiveUser"){
            $sql = "UPDATE user SET loginDateTime = '".date("F y j")."' WHERE UAPID = '".$_SESSION['password']."' AND loginDateTime IS NOT NULL";
        }
        else if($queryName == "and_like_category"){
            $sql = "SELECT * FROM person WHERE ".$param['category']." LIKE '".$param['sValue']. "%'";
        }
        else if($queryName == "addModifiedDate"){
            $sql = "UPDATE user SET modifiedDateTime = '" . date("F y j") . "' WHERE pid = '" . $param . "'";
        }
        else if($queryName == "selectPage"){
            $sql = "SELECT * FROM pages WHERE title = '$param'";
        }
        else if($queryName == "editPerson"){
            $columns = Config::getPersonForm();
            $columns = explode(",",$columns);
            $sql = "UPDATE person SET ";
            for($i=0;$i<count($columns);$i++){
                if($i != (count($columns)-1)){
                    $sql .= $columns[$i]."="."'".$this->propertyList[$columns[$i]]."',";
                }
                else{
                    $sql .= " ".$columns[$i]."="."'".$this->propertyList[$columns[$i]]."'";
                }
            }
            $sql .= "WHERE pid = ".$this->propertyList['pid'];
        
        }
        else if($queryName == "updateActive"){
            $sql = "UPDATE user SET active = ".$param. " WHERE UAPID = ".$criterion;
        }
        else if($queryName == "getUserInfo"){
            $sql = "SELECT * FROM user";
        }
        else if($queryName == "checkUserLoginInfo"){
            $sql = "SELECT * FROM user WHERE emailAddress = '".$param['username']."' AND UAPID = '".$param['password']."'";            
        }
        else if($queryName == "addLogoutDateToActiveUser"){
            $sql = "UPDATE user SET logoutDateTime = '".date("F y j"). "' WHERE UAPID = '".$param."'";
        }
        else if($queryName == "sf-userTable"){
            $values = [];
            $columns = Config::getUserForm();
            $columns = explode(",",$columns);
            for($i=0;$i<count($columns);$i++){
                $values[] = "'". $this->propertyList[$columns[$i]]."'";
            }
            $columns = implode(",",$columns);
            $values = implode(",",$values);

            $sql = "INSERT INTO user($columns) VALUES($values)";
        }
        else if($queryName == "insertRelation"){
            $values = [];
            $columns = Config::getRelationForm();
            $columns = explode(",",$columns);
            for($i=0;$i<count($columns);$i++){
                    $values[] = "'". $this->propertyList[$columns[$i]]."'";    
            }
            $columns = implode(",",$columns);
            $values = implode(",",$values);
            $sql = "INSERT INTO relation($columns) VALUES($values)";
        }
        else if($queryName == "editRelation"){
             $values = [];
             $columns = Config::getRelationForm();
             $columns = explode(",",$columns);
             $sql = "UPDATE relation SET ";
             for($i=0;$i<count($columns);$i++){
                 if($i != (count($columns)-1)){
                     $sql .= $columns[$i]."="."'".$this->propertyList[$columns[$i]]."',";
                 }
                 else{
                     $sql .= " ".$columns[$i]."="."'".$this->propertyList[$columns[$i]]."'";
                 }
             }
             $sql .= " WHERE pid = ".$this->propertyList['pid'];
        }
        else if($queryName == "getOrphanChildren"){
            $sql = "SELECT person.*
                    FROM person
                    LEFT JOIN relation ON person.pid = relation.pid
                    WHERE relation.pid IS NULL";
        }



        
        else if($queryName == "deleteRelationByPid"){            
            $sql = "DELETE FROM relation WHERE pid = $param;";

        }
        else if($queryName == "deletePerson"){            
            $sql = "DELETE FROM person WHERE pid = $param;";
        }






        else if($queryName == "selectRelation"){
            $sql = "SELECT * FROM relation";
        }   
        else if($queryName == "whereRelation"){
            $sql = "SELECT * FROM relation WHERE ".$criterion." = ".$param;
        }
        else if($queryName == "selectPersons"){
            $sql = "SELECT * FROM person";
        }
        else if($queryName == "numberOfFamilies"){
            $sql = "SELECT * FROM relation WHERE psid IS NOT NULL AND psid != 0";
        }

        else if($queryName == 'selectRelationsWithPid'){
            $sql = "
            SELECT
                *,
                CASE
                WHEN psid  = $param THEN 'partner'
                WHEN mid   = $param THEN 'mother'
                WHEN fpid  = $param THEN 'father'
                ELSE NULL
                END AS matched_field
            FROM relation
            WHERE 
                psid = $param
                OR mid  = $param
                OR fpid = $param
            ";
        }

        else if($queryName == "selectPersonsAndRelationships"){
            $sql = "SELECT p.pid,p.firstName,p.lastName,p.birthDate,p.gender,p.email,p.phoneNumber,p.address,p.image,p.biography,r.mid,r.fpid,r.psid
                    FROM person p
                    INNER JOIN relation r ON p.pid = r.pid";
        }
        else if($queryName == "selectPersonAndRelationship"){
            $sql = "SELECT * FROM person p INNER JOIN relation r ON  r.pid =".$param." AND p.pid = ".$param;
        }
        else if($queryName == "getPersonByPid"){
            $sql = "SELECT p.pid,p.firstName,p.lastName,p.birthDate,p.gender,p.email,p.phoneNumber,p.address,p.image,p.biography,r.mid,r.fpid,r.psid
            FROM person p
            INNER JOIN relation r ON p.pid = r.pid WHERE p.pid "." = ".$param;

        }
        else if($queryName == "getPersonWithoutRelationship"){
            $sql = "SELECT * FROM person WHERE pid "." = ".$param;
        }
        else if($queryName == "wherePerson"){
            $sql = "SELECT * FROM person WHERE ".$criterion." = ".$param;
        } 

        else if($queryName == "childrenList"){
            if($criterion == "M"){//MALE = TRUE
                $sql = "select * from person p, relation r where r.fpid=".$param." and r.pid = p.pid";

            }else{
                $sql = "select * from person p, relation r where r.mid=".$param." and r.pid = p.pid";
            }
        }      
        return $sql;

    }

}