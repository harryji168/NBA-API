<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Unit\Services;

use App\Services\NbaGamesService;
use App\Services\NbaSeasonsService;
use App\Helpers\Storage;
use PHPUnit\Framework\TestCase;
use Exception;

class NbaGamesServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock $_ENV variables used in NbaGamesService.
        $_ENV['API_HOST'] = 'test_api_host';
        $_ENV['API_KEY'] = 'test_api_key';
        $_ENV['API_ENDPOINT_GAMES'] = 'test_api_endpoint_games';
        $_ENV['USE_CACHE'] = 'true';
    }

    /** @test */
    public function testNbaGamesService()
    {
        // Instantiate the NbaGamesService class
        $nbaGamesService = new NbaGamesService();

        // Create a ReflectionClass instance for NbaGamesService
        $reflectedClass = new \ReflectionClass(NbaGamesService::class);

        // Get the 'nbaSeasonsService' property
        $property = $reflectedClass->getProperty('nbaSeasonsService');

        // Make the 'nbaSeasonsService' property accessible
        $property->setAccessible(true);

        // Get the value of the 'nbaSeasonsService' property
        $nbaSeasonsServiceValue = $property->getValue($nbaGamesService);

        // Assert against the $nbaSeasonsServiceValue
        $this->assertInstanceOf(NbaSeasonsService::class, $nbaSeasonsServiceValue);
    }

    /** @test */
    public function itCanFetchGameData()
    {
        // Create mock for NbaSeasonsService to return a known value.
        $seasonsService = $this->createMock(NbaSeasonsService::class);
        $seasonsService->method('getSeasons')->willReturn(['2022', '2023']);

        // Substitute mocked NbaSeasonsService in NbaGamesService.
        $gamesService = new NbaGamesService();

        // Create a ReflectionClass instance for NbaGamesService
        $reflectedClass = new \ReflectionClass(NbaGamesService::class);

        // Get the 'nbaSeasonsService' property
        $property = $reflectedClass->getProperty('nbaSeasonsService');

        // Make the 'nbaSeasonsService' property accessible
        $property->setAccessible(true);

        // Set the value of 'nbaSeasonsService' property
        $property->setValue($gamesService, $seasonsService);

        // Prepare some mocked response data
        $responseData = [
            'response' => [
                ['gameId' => '1', 'teams' => [], 'season' => '2022'],
                ['gameId' => '2', 'teams' => [], 'season' => '2023'],
            ]
        ];

        // Mock filesystem operations.
        $filepath2022 = Storage::path('json/2022-games.json');
        $filepath2023 = Storage::path('json/2023-games.json');
        file_put_contents($filepath2022, json_encode($responseData));
        file_put_contents($filepath2023, json_encode($responseData));

        // Call the method we're testing.
        $games = $gamesService->fetchGameData();

        // Delete mocked files
        unlink($filepath2022);
        unlink($filepath2023);

        // Assert games data.
        $this->assertCount(4, $games);
    }

    /** @test */
    public function itThrowsExceptionWhenUnableToFetchSeasonData()
    {
        // Create a mock for NbaSeasonsService that will return an empty array
        $seasonsService = $this->createMock(NbaSeasonsService::class);
        $seasonsService->method('getSeasons')->willReturn([]);

        // Substitute mocked NbaSeasonsService in NbaGamesService.
        $gamesService = new NbaGamesService();

        // Create a ReflectionClass instance for NbaGamesService
        $reflectedClass = new \ReflectionClass(NbaGamesService::class);

        // Get the 'nbaSeasonsService' property
        $property = $reflectedClass->getProperty('nbaSeasonsService');

        // Make the 'nbaSeasonsService' property accessible
        $property->setAccessible(true);

        // Set the value of 'nbaSeasonsService' property
        $property->setValue($gamesService, $seasonsService);

        // Expect an exception to be thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to fetch seasons data');

        // Call the method we're testing.
        $gamesService->fetchGameData();
    }
}
