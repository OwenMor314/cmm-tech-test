<?php

namespace App\Service;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    private $name, $logFileName, $level, $logger;

    public function __construct($name, $logFileName, $level) 
    {
        $this->name = $name;
        $this->logFileName = $logFileName;
        $this->level = $level;
        $this->logger = $this->setUp();
    }

    private function setUp(): Logger {
        $log = new Logger($this->name);
        $log->pushHandler(new StreamHandler(__DIR__.'/../../logs/'.$this->logFileName, $this->level));
        return $log;
    }

    public function info(string $message, array $context = []):void {
        $this->logger->info($message, $context);
    }
}
