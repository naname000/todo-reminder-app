<?php

namespace Tests\Feature;

use App\Services\HolidayService;
use Tests\TestCase;

class HolidayServiceTest extends TestCase
{
    
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $holiday_service = app()->make(HolidayService::class);
        $day = now('Asia/Tokyo')->setDate(2021, 12, 29);
        $this->assertTrue($holiday_service->isHoliday($day), '12月29日は休日です');
        $day = now('Asia/Tokyo')->setDate(2021, 12, 28);
        $this->assertNotTrue($holiday_service->isHoliday($day), '12月28日は休日ではありません');
    }
}
