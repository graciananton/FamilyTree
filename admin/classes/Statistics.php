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
    public function findHour(){
        date_default_timezone_set("America/Toronto");
        $this->hour = (int) date("H");

    }
    public function getHour(){
        return (int) $this->hour;
    }
}