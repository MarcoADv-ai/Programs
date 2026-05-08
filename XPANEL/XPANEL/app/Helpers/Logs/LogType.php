<?php
namespace App\Helpers\Logs;

interface LogType
{
    public function write($message);
    public function writeInTable($data = []);
}