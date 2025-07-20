<?php
interface Builder{
    public function generateTree(string $pid, string $select, array $request):string;
}