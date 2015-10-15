<?php

namespace Wprsrv\Tests;

use PHPUnit_Framework_TestCase;
use Wprsrv\Logger;

class LoggerTest extends PHPUnit_Framework_TestCase
{
    protected $logFile = __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'test.log';

    public function setUp()
    {
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile));
        }

        $f = fopen($this->logFile, 'wb');
        fwrite($f, '');
        fclose($f);
    }

    public function tearDown()
    {
        unlink($this->logFile);
    }

    public function testLoggerLogMethods()
    {
        $logger = new Logger(['log_file' => $this->logFile]);

        $methods = [
            'emergency',
            'critical',
            'alert',
            'error',
            'warning',
            'notice',
            'info',
            'debug'
        ];

        foreach ($methods as $method) {
            $logger->{$method}('Testing method {method}', ['method' =>  $method]);
            $logger->{$method}('Testing method with no context and no given context');
            $logger->{$method}('Testing method with no context but given context', ['hello' => 'world']);
            $logger->log($method, 'Testing generic log method with {method}', ['method' => $method]);

            $fileContents = file_get_contents($this->logFile);

            $ptrns = [
                '%Testing method ' . $method . '%',
                '%method with ' . $method . '%',
                '%no given context%',
                '%but given context%'
            ];

            foreach ($ptrns as $ptrn) {
                $this->assertRegExp($ptrn, $fileContents);
            }
        }
    }
}
