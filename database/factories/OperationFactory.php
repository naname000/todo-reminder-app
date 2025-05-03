<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'content' => preg_replace("/。/", "。\n\n", $this->faker->realText),
            'notified' => false,
        ];
    }
    
    public function todayOperation(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
              'scheduled_at' => $this->faker->dateTimeBetween('now', '0 days'),
            ];
        });
    }
    
    public function tomorrowOperation(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
              'scheduled_at' => $this->faker->dateTimeBetween('1 days', '1 days'),
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
}
