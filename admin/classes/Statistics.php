<?php
class Statistics{
    private $DatabaseManager;
    private $numberOfIndividuals;
    private $numberOfFamilies;
    private $greeting;
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
    public function setGreeting(){
        date_default_timezone_set("America/Toronto");
        $hour = (int) date("H");

        if($hour > 5 && $hour <12){
            $this->greeting = "Good Morning";
        }
        else if($hour >=12 && $hour <15){
            $this->greeting = "Good Afternoon";
        }
        else if($hour >=15 && $hour <= 20){
            $this->greeting = "Good Evening";
        }
        else{
            $this->greeting = "Good Night";
        }
        
    }
    public function getGreeting(){
        return  $this->greeting;
    }
}