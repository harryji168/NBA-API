<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;
use App\Helpers\Storage;

class StorageTest extends TestCase
{
    private $baseStoragePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseStoragePath = '/var/www/app/Helpers/../../storage/';
    }

    public function testPath()
    {
        $path = Storage::path('logs/nba_api.log');
 
        $expectedPath = $this->baseStoragePath.'logs/nba_api.log';

        $this->assertSame($expectedPath, $path);
        $this->assertDirectoryExists('/var/www/storage/logs');
    }
}
