<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use PHPUnit\Framework\TestCase;

class AppServiceProviderTest extends TestCase
{
    /** @test */
    public function itLoadsEnvironmentVariables()
    {
        $provider = new AppServiceProvider;
        $provider->boot();

        $this->assertNotEmpty($_ENV['API_ENDPOINT_SEASONS']);
        // Add more assertions here for other environment variables if needed.
    }

    /** @test */
    public function itSetsDefaultTimezone()
    {
        $provider = new AppServiceProvider;
        $provider->boot();

        $this->assertEquals('UTC', date_default_timezone_get());
    }
}
