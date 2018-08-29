<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enemy extends Model
{
    public $currentHP;
    public $currentHeal;
    public $currentAttack;
    public $maxHP;

    public function setStats(Hero $hero)
    {
        $this->maxHP = ceil($this->hp_multiplier * $hero->hp);
    	$this->currentHP = $this->getMaxHP();
    	$this->currentAttack = ceil($this->attack_multiplier * $hero->attack_points);
    	$this->currentHeal = ceil($this->heal_multiplier * $hero->heal_points);
    }

    public function getInfoLine()
    {
        return "{$this->name} (HP: {$this->currentHP} / ATK: {$this->currentAttack} / HL: {$this->currentHeal})";
    }

    public function getMultiplierLabel()
    {
    	return "(HP: {$this->hp_multiplier} / ATK: {$this->attack_multiplier} / HL: {$this->heal_multiplier})";
    }

    public function getMaxHP()
    {
        return $this->maxHP;
    }

    public function getActionDecision()
    {
        // Always attack if at full health
        if ($this->currentHP == $this->getMaxHP()) {
            return 'attack';
        }

        // Always heal if getting badly beaten
        if ($this->getMaxHP() - $this->currentHP >= $this->currentHeal) {
            return 'heal';
        }

        // Else, weight heavily for an attack
        return collect(['attack', 'attack', 'attack', 'heal'])->random();
    }
}
