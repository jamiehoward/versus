<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
	public $fillable = ['name', 'attack_name', 'heal_name', 'attack_points', 'heal_points', 'victories', 'hp'];

    public function getInfoLine()
    {
    	$hp = (isset($this->currentHP)) ? $this->currentHP : $this->hp;
    	return "{$this->name} (LVL: {$this->level} / HP: $hp / ATK: {$this->attack_points} / HL: {$this->heal_points} / XP: {$this->victories})";
    }

    public function battles()
    {
    	return $this->hasMany(Battle::class);
    }

    public function increaseVictories()
    {
    	$this->victories++;
    	$this->save();
    }

    public function decreaseVictories()
    {
    	if ($this->victories > 0) {
	    	$this->victories--;
	    	$this->save();
    	}
    }

    public function resetHP()
    {
    	unset($this->currentHP);
    }

    public function canLevelUp()
    {
    	$victoriesPerLevel = [
    		1 => 2,
    		2 => 3,
    		3 => 5,
    		4 => 7,
    		5 => 10,
     		6 => 14,
    		7 => 19,
    		8 => 25,
    		9 => 30,
    	];

    	return $this->victories >= $victoriesPerLevel[$this->level];
    }
}
