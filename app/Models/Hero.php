<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    public function getInfoLine()
    {
    	return "{$this->name} (HP: {$this->currentHP} / ATK: {$this->attack_points} / HL: {$this->heal_points})";
    }

    public function battles()
    {
    	return $this->hasMany(Battle::class);
    }
}
