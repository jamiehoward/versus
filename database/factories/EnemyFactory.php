<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Enemy::class, function (Faker $faker) {
	$attacks = collect(['Ambush','Bane','Barrage','Blight','Conflagrate','Consume','Decay','Detonate','Devastate','Dispel','Dread','Exorcise','Fervor','Flare','Flay','Fluke','Gnaw','Imbue','Innervate','Judge','Missile','Omen','Prowl','Random','Riptide','Rupture','Rush','Smash','Smolder','Strangle','Weaken','Whack','Whirlwind', 'Burn', 'Freezeray', 'Saw', 'Drill', 'Destroy', 'Annihilate', 'Ruin']);

	$heals = collect(['Armor Up','Bluff','Clench','Clone','Dash','Daze','Exempt','Fade','Grace','Hug','Intervene','Mislead','Piety','Reincarnate','Shift','Shout','Heal','Repair','Mend','Clean','Walk it off', 'Rest', 'Hide', 'Restore', 'Regenerate', 'Cloak', 'Salve', 'Ointment', 'Annoint', 'Rejuvenate', 'Quench', 'Bathe']);

    return [
        'name' => $faker->name,
        'hp_multiplier' => 1,
        'attack_name' => $attacks->random(),
        'attack_multiplier' => 1,
        'heal_name' => $heals->random(),
        'heal_multiplier' => 1
    ];
});
