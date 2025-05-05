<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GuestUserSeeder::class,
            OperationSeeder::class,
        ]);
    }
}
