<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
	public function hero()
	{
		return $this->belongsTo(Hero::class);
	}

	public function enemy()
	{
		return $this->belongsTo(Enemy::class);
	}

	public function actions()
	{
		return $this->hasMany(Action::class);
	}
}
