<?php

/*
 * This source file is part of NBA API.
 * Copyright (c) 2023 Harry Ji.
 * All rights reserved.
 */

namespace Tests\Unit\Helpers;

use App\Helpers\Dates;
use PHPUnit\Framework\TestCase;

class DatesTest extends TestCase
{
    /** @test */
    public function itCanSanitizeDate()
    {
        $inputDate = '01/20/2023';
        $sanitizedDate = Dates::sanitizeDate($inputDate);
        $this->assertEquals('2023-01-20', $sanitizedDate);

        $invalidDate = 'Invalid date';
        $sanitizedInvalidDate = Dates::sanitizeDate($invalidDate);
        $this->assertNull($sanitizedInvalidDate);
    }

    /** @test */
    public function itCanSanitizeDateTime()
    {
        $inputDateTime = '2023-01-20 15:30:00';
        $sanitizedDateTime = Dates::sanitizeDateTime($inputDateTime);
        $this->assertEquals('2023<br>01/20<br>15:30', $sanitizedDateTime);

        $invalidDateTime = 'Invalid date time';
        $sanitizedInvalidDateTime = Dates::sanitizeDateTime($invalidDateTime);
        $this->assertNull($sanitizedInvalidDateTime);
    }
}
