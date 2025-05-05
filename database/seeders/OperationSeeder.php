<?php

namespace Database\Seeders;

use App\Models\Operation;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // デフォルト（ランダム）
        //Operation::factory()->count(4)->create();

        // 昨日の予定
        Operation::factory()->count(2)->yesterdayOperation()->create();

        // 今日予定のやつ
        Operation::factory()->count(20)->todayOperation()->create();

        // 明日予定のやつ
        Operation::factory()->count(20)->tomorrowOperation()->create();
    }
}
