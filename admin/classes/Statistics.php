<?php
class Statistics{
    private $DatabaseManager;
    private $numberOfIndividuals;
    private $numberOfFamilies;
    private $hour;
    public function __construct(){
        $this->DatabaseManager = new DatabaseManager('');
    }
    public function findNumberOfIndividuals(){
        $this->numberOfIndividuals = $this->DatabaseManager->findNumberOfIndividuals();
        return $this->numberOfIndividuals;
    }   
    public function findNumberOfFamilies(){
        $this->numberOfFamilies = ($this->DatabaseManager->findNumberOfFamilies())/2;
        return $this->numberOfFamilies;
    }
    public function getNumberOfIndividuals(){
        return $this->numberOfIndividuals;
    }
    public function getNumberOfFamilies(){
        return $this->numberOfFamilies;
    }
    public function getLatestDate(){
        $dates = $this->DatabaseManager->getDates();
        $combinedDates = [];
        for($i=0;$i<count($dates);$i++){
            $date = $dates[$i];
            if(preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date['createdDate'])){
                array_push($combinedDates,$date['createdDate']);
            }
            if(preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date['modifiedDate'])){
                array_push($combinedDates,$date['modifiedDate']);

            }
        }
        $latestDate = str_replace("/","-",max($combinedDates));
        return $latestDate;

    }
    public function findHour(){
        date_default_timezone_set("America/Toronto");
        $this->hour = (int) date("H");

    }
    public function getHour(){
        return (int) $this->hour;
    }
}