<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * ゲストユーザー専用ステート
     */
    public function guest(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'ゲスト太郎',
                'email' => 'guest@example.com',
                'password' => Hash::make('guestpassword'),
                'email_verified_at' => now(),
            ];
        });
    }
}
