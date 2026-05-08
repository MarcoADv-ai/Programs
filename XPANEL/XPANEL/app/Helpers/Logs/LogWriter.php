<?php
namespace App\Helpers\Logs;
class LogWriter implements LogType
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function setLogType(LogType $type)
    {
        $this->type = $type;
    }

    public function write($message)
    {
        $this->type->write($message);
    }

    public function writeInTable($data = [])
    {
        $this->type->writeInTable($data);
    }
}