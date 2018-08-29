<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
	public function hero()
	{
		return $this->belongsTo(Hero::class);
	}

	public function enemy()
	{
		return $this->belongsTo(Enemy::class);
	}
}
