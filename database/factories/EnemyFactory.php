<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Enemy::class, function (Faker $faker) {
	$attacks = collect(['Ambush','Bane','Barrage','Blight','Conflagrate','Consume','Decay','Detonate','Devastate','Dispel','Dread','Exorcise','Fervor','Flare','Flay','Fluke','Gnaw','Imbue','Innervate','Judge','Missile','Omen','Prowl','Random','Riptide','Rupture','Rush','Smash','Smolder','Strangle','Weaken','Whack','Whirlwind', 'Burn', 'Freezeray', 'Saw', 'Drill', 'Destroy', 'Annihilate', 'Ruin']);

	$heals = collect(['Armor Up','Bluff','Clench','Clone','Dash','Daze','Exempt','Fade','Grace','Hug','Intervene','Mislead','Piety','Reincarnate','Shift','Shout','Heal','Repair','Mend','Clean','Walk it off', 'Rest', 'Hide', 'Restore', 'Regenerate', 'Cloak', 'Salve', 'Ointment', 'Annoint', 'Rejuvenate', 'Quench', 'Bathe']);

    // Harder
    // return [
    //     'name' => $faker->name,
    //     'hp_multiplier' => rand(0,2) + rand(0,10)/10,
    //     'attack_name' => $attacks->random(),
    //     'attack_multiplier' => rand(0,2) + rand(0,10)/10,
    //     'heal_name' => $heals->random(),
    //     'heal_multiplier' => rand(0,2) + rand(0,10)/10
    // ];

    // Easy
    return [
        'name' => $faker->name,
        'hp_multiplier' => rand(1,10)/10,
        'attack_name' => $attacks->random(),
        'attack_multiplier' => rand(1,10)/10,
        'heal_name' => $heals->random(),
        'heal_multiplier' => rand(1,10)/10
    ];
});
