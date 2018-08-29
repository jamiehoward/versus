<?php

use App\Models\Hero;
use Illuminate\Database\Seeder;

class HeroesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::table('heroes')->truncate();

        factory(Hero::class, 5)->create();
    }
}
