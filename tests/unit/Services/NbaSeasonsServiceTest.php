<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Unit\Services;

use App\Helpers\Storage;
use App\Services\NbaApiRequester;
use App\Services\NbaSeasonsService;
use PHPUnit\Framework\TestCase;
use Exception;

class NbaSeasonsServiceTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        $this->service = new NbaSeasonsService();
    }

    public function tearDown(): void
    {
        $this->service = null;
    }

    /** @test */
    public function itCanGetSeasons()
    {
        // Create a mock for the NBA API Requester
        $requester = $this->createMock(NbaApiRequester::class);
        $requester->method('request')->willReturn(json_encode([
            'response' => ['2021', '2022', '2023'],
        ]));

        // Create a new instance of NbaSeasonsService
        $seasonsService = new NbaSeasonsService();

        // Create a reflection of the NbaSeasonsService class
        $reflector = new \ReflectionClass(NbaSeasonsService::class);

        // Get the property 'requester' from the reflection
        $requesterProperty = $reflector->getProperty('requester');

        // Set the visibility of the 'requester' property to accessible
        $requesterProperty->setAccessible(true);

        // Assign the mock requester to the 'requester' property of the NbaSeasonsService instance
        $requesterProperty->setValue($seasonsService, $requester);

        // Get seasons
        $seasons = $seasonsService->getSeasons();

        // Assert
        $this->assertEquals(['2021', '2022', '2023'], $seasons);
    }

    /** @test */
    public function itThrowsExceptionWhenRateLimitIsExceeded()
    {
        // Create mock for NbaApiRequester to simulate a rate limit exceeded error.
        $apiRequester = $this->createMock(NbaApiRequester::class);
        $apiRequester->method('request')
            ->will($this->throwException(new Exception('Rate limit exceeded: rate limit exceeded')));

        // Using reflection to access the protected property $requester
        $reflection = new \ReflectionClass(NbaSeasonsService::class);
        $requester = $reflection->getProperty('requester');
        $requester->setAccessible(true);

        // Substitute mocked NbaApiRequester in NbaSeasonsService.
        $seasonsService = new NbaSeasonsService();
        $requester->setValue($seasonsService, $apiRequester);

        // Prepare for exception.
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error fetching data from the API.');

        // Call the method we're testing.
        $seasonsService->getSeasons();
    }

    /** @test */
    public function itUsesCacheWhenAvailable()
    {
        // Create cache
        $filepath = Storage::path('json/seasons.json');
        $data = ['response' => ['2022', '2023']];
        file_put_contents($filepath, json_encode($data));

        // Set USE_CACHE environment variable to true
        $_ENV['USE_CACHE'] = 'true';

        // Call the method we're testing.
        $seasons = $this->service->getSeasons();

        // Delete cache
        unlink($filepath);

        // Assert seasons data.
        $this->assertEquals(['2022', '2023'], $seasons);
    }
}
