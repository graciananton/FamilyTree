<?php
class Setting extends TimeStamp{
    private int $pid;
    private string $name;
    private string $value;
    private int $userId;

    public function __construct(array $record){
        parent::__construct();
        foreach($record as $key => $value) {
            $method = "set" . ucfirst($key);
            if(method_exists($this, $method)) {
                $this->$method($value);  //
            }
        }

    }
    // Get functions 
    public function getPid(): int{
        return $this->pid;
    }
    public function getName(): string{
        return $this->name;
    }
    public function getValue(): string{
        return $this->value;
    }
    public function getUserId(): int{
        return $this->userId;
    }
    // Set functions 
    public function setPid(string $pid):void {
        $this->pid = $pid;
    }
    public function setName(string $name): void{
        $this->name = $name;
    }
    public function setValue(string $value): void{
        $this->value = $value;
    }
    public function setUserId(int $userId): void{
        $this->userId = $userId;
    }


}