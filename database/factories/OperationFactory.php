<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operation>
 */
class OperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 3日前から3日後のランダムな日時
            'scheduled_at' => $this->faker->dateTimeBetween('-3 days', '+3 days'),
            'content' => "以下は作業に関する内容です。文章はダミーです。\n------\n" . preg_replace("/。/", "。\n\n", $this->faker->realText),
            'notified' => false,
        ];
    }

    public function todayOperation(): Factory
    {
        $tz = 'Asia/Tokyo';
        $start = Carbon::today($tz)->now()->setTimezone('UTC');
        $end = Carbon::today($tz)->endOfDay()->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end) {
            return [
                'scheduled_at' => $this->faker->dateTimeBetween($start, $end, 'UTC'),
            ];
        });
    }

    public function tomorrowOperation(): Factory
    {
        $tz = 'Asia/Tokyo';
        $start = Carbon::tomorrow($tz)->now()->setTimezone('UTC');
        $end = Carbon::tomorrow($tz)->endOfDay()->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end) {
            return [
                'scheduled_at' => $this->faker->dateTimeBetween($start, $end, 'UTC'),
            ];
        });
    }

    /**
     * 今から10分後の間でランダムな時間の予定を作成
     * @return Factory
     */
    public function tenMinutesFromNowOperation(): Factory {
        $MINUTES = "+10 minute";
        return $this->state(function (array $attributes) use ($MINUTES) {
           return [
             'scheduled_at' => $this->faker->dateTimeBetween('now', $MINUTES),
           ];
        });
    }

    /**
     * 昨日の予定を作成
     * 通知済みとする
     */
    public function yesterdayOperation(): Factory
    {
        $tz = 'Asia/Tokyo';
        $start = Carbon::yesterday($tz)->startOfDay()->setTimezone('UTC');
        $end = Carbon::yesterday($tz)->endOfDay()->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end) {
            return [
                'scheduled_at' => $this->faker->dateTimeBetween($start, $end, 'UTC'),
                'notified' => true,
            ];
        });
    }
}
