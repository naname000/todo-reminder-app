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
        // UTCで生成
        $scheduled_at   = $this->faker->dateTimeBetween('-3 days', '+3 days', 'UTC');
        // JSTに変換して過去か判定
        $tz             = 'Asia/Tokyo';
        $scheduled_jst  = Carbon::parse($scheduled_at)->setTimezone($tz);
        $isPast         = $scheduled_jst->lt(now($tz));

        return [
            'scheduled_at' => $scheduled_at,
            'content'      => "以下は作業に関する内容です。文章はダミーです。\n------\n"
                              . preg_replace("/。/", "。\n\n", $this->faker->realText),
            'notified'     => $isPast,
        ];
    }

    /**
     * 今日の予定をJST基準で生成（UTC保存）
     */
    public function todayOperation(): Factory
    {
        $tz    = 'Asia/Tokyo';
        $start = Carbon::today($tz)->setTimezone('UTC');
        $end   = Carbon::today($tz)->endOfDay()->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end, $tz) {
            $scheduled = $this->faker->dateTimeBetween($start, $end, 'UTC');
            $isPast    = Carbon::instance($scheduled)
                            ->setTimezone($tz)
                            ->lt(now($tz));

            return [
                'scheduled_at' => $scheduled,
                'notified'     => $isPast,
            ];
        });
    }

    /**
     * 明日の予定をJST基準で生成（未通知）
     */
    public function tomorrowOperation(): Factory
    {
        $tz    = 'Asia/Tokyo';
        $start = Carbon::tomorrow($tz)->setTimezone('UTC');
        $end   = Carbon::tomorrow($tz)->endOfDay()->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end) {
            return [
                'scheduled_at' => $this->faker->dateTimeBetween($start, $end, 'UTC'),
                'notified'     => false,
            ];
        });
    }

    /**
     * 今から10分後の予定をJST基準で生成（未通知）
     */
    public function tenMinutesFromNowOperation(): Factory
    {
        $tz    = 'Asia/Tokyo';
        $start = now($tz)->setTimezone('UTC');
        $end   = now($tz)->addMinutes(10)->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end) {
            $scheduled = $this->faker->dateTimeBetween($start, $end, 'UTC');
            return [
                'scheduled_at' => $scheduled,
                'notified'     => false,
            ];
        });
    }

    /**
     * 昨日の予定をJST基準で生成（通知済み）
     */
    public function yesterdayOperation(): Factory
    {
        $tz    = 'Asia/Tokyo';
        $start = Carbon::yesterday($tz)->startOfDay()->setTimezone('UTC');
        $end   = Carbon::yesterday($tz)->endOfDay()->setTimezone('UTC');

        return $this->state(function (array $attributes) use ($start, $end) {
            return [
                'scheduled_at' => $this->faker->dateTimeBetween($start, $end, 'UTC'),
                'notified'     => true,
            ];
        });
    }
}
