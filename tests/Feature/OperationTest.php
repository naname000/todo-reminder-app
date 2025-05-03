<?php

namespace Tests\Feature;

use App\Models\Operation;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Yasumi\Yasumi;

class OperationTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * 今日の予定を生成、今日の予定を取得するテスト
     * @return void
     */
    public function test_getTodayOperation(): void
    {
        Operation::factory()->todayOperation()->create();
        $operations = Operation::getTodayOperations();
        $this->assertEquals(1, $operations->count());
    }
    
    /**
     * 明日の予定を生成、明日の予定を取得するテスト
     * @return void
     */
    public function test_getTomorrowOperation(): void
    {
        Operation::factory()->tomorrowOperation()->create();
        $operations = Operation::getTomorrowOperations();
        $this->assertEquals(1, $operations->count());
    }
    
    /**
     * 祝日の前日にタイムトラベルして、祝日の前日から見て次の営業日の予定を取得するテスト
     * @return void
     */
    public function test_getNextBusinessDayOperation(): void
    {
        $holidays = Yasumi::create('Japan', now()->year, 'ja_JP')->getHolidays();
        // 祝日(月曜)(成人式)
        $holiday = CarbonImmutable::make($holidays['comingOfAgeDay']);
        // 祝日の前日
        $previousDay = $holiday->modify('-1 day');
        // 祝日の後日
        $nextDay = $holiday->modify('+1 day');
        // 祝日の前日にタイムトラベルする
        $this->travelTo($previousDay);
        
        // 祝日の後日の予定を作成
        Operation::factory()->create([
            'scheduled_at' => $nextDay
        ]);
        
        $operations = Operation::getNextBusinessDayOperations();
        
        $this->assertEquals(1, $operations->count());
    }
    
    /**
     * 祝日の前日にタイムトラベルして、祝日の前日から見て次の営業日を取得するテスト
     * @return void
     */
    public function test_calculateNextBusinessDay(): void
    {
        $holidays = Yasumi::create('Japan', now()->year, 'ja_JP')->getHolidays();
        // 祝日(月曜)(成人式)
        $holiday = CarbonImmutable::make($holidays['comingOfAgeDay']);
        
        // 先週の金曜日
        $previousDay = $holiday->modify('-3 day');
        // 祝日の後日
        $nextDay = $holiday->modify('+1 day');
        // 祝日の前日にタイムトラベルする
        $this->travelTo($previousDay);
        $nextBusinessDay = Operation::calculateNextBusinessDay();
        $this->assertEquals($nextDay, $nextBusinessDay);
        
        // 2023年の憲法記念日(水曜日)
        $holiday = CarbonImmutable::make('2023-05-03');
        // 祝日の前日
        $previousDay = $holiday->modify('-1 day');
        // 2023年の憲法記念日の次の平日(月曜日)
        $nextDay = CarbonImmutable::make('2023-05-08');
        
        // 祝日の前日にタイムトラベルする
        $this->travelTo($previousDay);
        $nextBusinessDay = Operation::calculateNextBusinessDay();
        $this->assertEquals($nextDay, $nextBusinessDay);
    }
    
    /**
     * 今から10分後の間でランダムな時間の予定を作成、10分後の予定を取得するテスト
     * @return void
     */
    public function test_getTenMinutesFromNowOperation(): void {
        Operation::factory()->tenMinutesFromNowOperation()->create();
        $operations = Operation::getTenMinutesFromNowOperation();
        $this->assertEquals(1, $operations->count());
    }
}
