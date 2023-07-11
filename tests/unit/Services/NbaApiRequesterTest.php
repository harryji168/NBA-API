<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use phpmock\phpunit\PHPMock;
use App\Services\NbaApiRequester;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Helpers\Storage;

class NbaApiRequesterTest extends TestCase
{
    use PHPMock;

    private $url = 'http://example.com';
    private $headers = ['Accept' => 'application/json'];

    public function testRequest()
    {
        // Get the namespace of the class we are testing
        $namespace = substr(NbaApiRequester::class, 0, strrpos(NbaApiRequester::class, '\\'));

        // Mock the cURL functions
        $curlInitMock = $this->getFunctionMock($namespace, 'curl_init');
        $curlInitMock->expects($this->once())->willReturn('curl_handle');

        $curlSetoptArrayMock = $this->getFunctionMock($namespace, 'curl_setopt_array');
        $curlSetoptArrayMock->expects($this->once());

        $curlExecMock = $this->getFunctionMock($namespace, 'curl_exec');
        $curlExecMock->expects($this->once())->willReturn('response');

        $curlCloseMock = $this->getFunctionMock($namespace, 'curl_close');
        $curlCloseMock->expects($this->once());

        $curlErrorMock = $this->getFunctionMock($namespace, 'curl_error');
        $curlErrorMock->expects($this->never());

        // Mock the Logger class and its methods
        $mockLogger = $this->createMock(Logger::class);
        $mockLogger->expects($this->never())->method('error');

        // Instantiate the object and run the method
        $nbaApiRequester = new NbaApiRequester($this->url, $this->headers);
        $nbaApiRequesterReflection = new \ReflectionClass($nbaApiRequester);
        $loggerProperty = $nbaApiRequesterReflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $loggerProperty->setValue($nbaApiRequester, $mockLogger);

        $response = $nbaApiRequester->request();

        $this->assertEquals('response', $response);
    }
}
