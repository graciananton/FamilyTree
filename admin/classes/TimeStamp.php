<?php
class TimeStamp{
    private string $createdDateTime;
    private string $modifiedDateTime;
    public function __construct(){
        $this->modifiedDateTime = date('Y-m-d');
    }
    public function getcreatedDateTime(): string{
        return $this->createdDateTime;
    }
    public function getmodifiedDateTime(): string{
        return $this->modifiedDateTime;
    }
    public function setcreatedDateTime(string $createdDateTime){
        $this->createdDateTime = $createdDateTime;
    }
    public function setmodifiedDateTime(string $modifiedDateTime){
        $this->modifiedDateTime = $modifiedDateTime;
    }
}