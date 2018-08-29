<?php

use App\Models\Enemy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnemiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('enemies')->truncate();

        $defaultEnemies = [
            [
                'name' => '',
                'hp_multiplier' => '',
                'attack_name' => '',
                'attack_multiplier' => '',
                'heal_name' => '',
                'heal_multiplier' => '',
            ]
        ];

        factory(Enemy::class, 25)->create();
    }
}
