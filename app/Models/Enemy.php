<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enemy extends Model
{
    public $currentHP;
    public $currentHeal;
    public $currentAttack;

    public function setStats(Hero $hero)
    {
    	$this->currentHP = ceil($this->hp_multiplier * $hero->hp);
    	$this->currentAttack = ceil($this->attack_multiplier * $hero->attack_points);
    	$this->currentHeal = ceil($this->heal_multiplier * $hero->heal_points);
    }

    public function getInfoLine()
    {
    	return "{$this->name} (HP: {$this->currentHP} / ATK: {$this->currentAttack} / HL: {$this->currentHeal})";
    }
}
