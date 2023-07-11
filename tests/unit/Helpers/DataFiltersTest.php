<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Unit\Helpers;

use App\Helpers\DataFilters;
use PHPUnit\Framework\TestCase;

class DataFiltersTest extends TestCase
{
    /** @test */
    public function itCanFilterGamesBySearchAndDate()
    {
        $games = [
            [
                'teams' => [
                    'visitors' => ['name' => 'Chicago Bulls'],
                    'home' => ['name' => 'Los Angeles Lakers']
                ],
                'status' => ['long' => 'Completed'],
                'date' => ['start' => '2023-07-01']
            ],
            [
                'teams' => [
                    'visitors' => ['name' => 'Boston Celtics'],
                    'home' => ['name' => 'Brooklyn Nets']
                ],
                'status' => ['long' => 'In Progress'],
                'date' => ['start' => '2023-07-02']
            ]
        ];

        $search = 'Chicago Bulls';
        $date = '2023-07-01';

        $filteredGames = DataFilters::getFilteredGames($games, $search, $date);

        $this->assertCount(1, $filteredGames);
        $this->assertEquals('Chicago Bulls', $filteredGames[0]['teams']['visitors']['name']);
        $this->assertEquals('Los Angeles Lakers', $filteredGames[0]['teams']['home']['name']);
        $this->assertEquals('Completed', $filteredGames[0]['status']['long']);
        $this->assertEquals('2023-07-01', $filteredGames[0]['date']['start']);
    }
}
