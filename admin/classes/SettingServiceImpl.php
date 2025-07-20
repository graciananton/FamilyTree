<?php
class SettingServiceImpl implements SettingService{
    private DatabaseManager $DatabaseManager;
    public function __construct($DatabaseManager) { 
        $this->DatabaseManager = $DatabaseManager;
    }
    public function getRecords(string $criterion,string $param):array{
        $records = $this->DatabaseManager->getRecords($criterion,$param);
        return $records;
    }
    public function updateRecord(object $Setting):bool{
        $response = $this->DatabaseManager->updateRecord($Setting);
        return $response;
    }
    public function getSettingValueByName(string $name){
        $response = $this->DatabaseManager->getSettingValueByName($name);
        return $response;
    }
}