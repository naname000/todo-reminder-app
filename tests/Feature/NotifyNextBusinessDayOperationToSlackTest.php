<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotifyNextBusinessDayOperationToSlackTest extends TestCase
{
    public function test_test(): void
    {
        // 今日と明日が休日であれば1を返す想定
        $this->travelTo('first saturday of 2019'); // 土曜日に移動
        $this->artisan('app:notify-next-business-day-operation-to-slack')->assertExitCode(1);
    }
}
