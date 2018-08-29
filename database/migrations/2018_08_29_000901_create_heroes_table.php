<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('level')->default(1);
            $table->integer('current_hp')->default(5);
            $table->integer('max_hp')->default(5);
            $table->string('attack_name');
            $table->integer('attack_points')->default(1);
            $table->string('heal_name');
            $table->integer('heal_points')->default(1);
            $table->integer('victories')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heroes');
    }
}
