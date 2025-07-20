<?php
interface SettingService {
    public function getRecords(string $criterion, string $param):array;
    public function updateRecord(object $Setting):bool;
    public function getSettingValueByName(string $name);
}
