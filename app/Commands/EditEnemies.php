<?php

namespace App\Commands;

use App\Models\Enemy;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class EditEnemies extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'edit:enemies';

    public $enemy;

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit the list of enemies';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->selectEnemy();
    }

    public function createEnemy()
    {
        $enemy = new Enemy;
        $enemy->name = $this->ask('What is the enemy\'s name?');
        $enemy->hp_multiplier = $this->ask('What is the enemy\'s HP multiplier?', 1);
        $enemy->attack_name = $this->ask('What is the enemy\'s attack called?', 'Attack');
        $enemy->attack_multiplier = $this->ask('What is the enemy\'s attack multiplier?', 1);
        $enemy->heal_name = $this->ask('What is the enemy\'s healing power called?', 'Heal');
        $enemy->heal_multiplier = $this->ask('What is the enemy\'s healing multiplier?',1 );

        $enemy->save();

        $this->selectEnemy();
    }

    public function selectEnemy()
    {
        $enemies = Enemy::get();
        $options = $enemies->map(function($enemy) {
            return "{$enemy->name} " . $enemy->getMultiplierLabel();
        })->all();

        $options[] = '[Create a new enemy]';

        $selection = $this->menu('Select your enemy', $options)
        ->open();

        if ($selection == count($options)-1) {
            $this->createEnemy();
        } elseif (!is_null($selection)) { 
            $this->enemy = $enemies[$selection];

            $this->editEnemyMenu();
        } else {
            exit;
        }
    }

    public function editEnemyMenu()
    {
        $selection = $this->menu("Viewing {$this->enemy->name}", [
            "Change enemy name",
            "Change hp multiplier ({$this->enemy->hp_multiplier})",
            "Change name of attack ({$this->enemy->attack_name})",
            "Change attack multiplier ({$this->enemy->attack_multiplier})",
            "Change name of healing ({$this->enemy->heal_name})",
            "Change healing multiplier ({$this->enemy->heal_multiplier})",
            "Delete enemy",
            'Back'
        ])
        ->disableDefaultItems()
        ->open();

        switch ($selection) {
            case 0:
                $this->enemy->name = $this->ask("What do want you enemy's name to be?");
                break;
            case 1:
                $this->enemy->hp_multiplier = $this->ask("What do want you enemy's hp_multiplier to be?");
                break;
            case 2:
                $this->enemy->attack_name = $this->ask("What do want you enemy's attack to be called?");
                break;
            case 3:
                $this->enemy->attack_multiplier = $this->ask("What do want you enemy's attack_multiplier to be?");
                break;
            case 4:
                $this->enemy->heal_name = $this->ask("What do want you enemy's healing power to be called?");
                break;
            case 5:
                $this->enemy->heal_multiplier = $this->ask("What do want you enemy's heal_multiplier to be?");
                break;
            case 6:
                if ($this->confirm("Are you sure that you want to delete this enemy?")) {
                    $this->enemy->delete();
                    $this->selectEnemy();
                }
            default:
                $this->selectEnemy();
        }

        $this->enemy->save();
        $this->editEnemyMenu();
    }

    public function combatZone()
    {
        $this->enemy = Enemy::get()->random();

        $this->hero->currentHP = $this->hero->hp;
        $this->enemy->setStats($this->hero);

        $this->info("You are now fighting {$this->enemy->name}!");

        // Keep the battle going until someone dies
        while ($this->hero->currentHP > 0 && $this->enemy->currentHP > 0) {

            $this->info($this->hero->getInfoLine());
            $this->info($this->enemy->getInfoLine());
            $action = $this->choice("Use {$this->hero->attack_name} or {$this->hero->heal_name}?", ['attack', 'heal'], 0);

            $actionName = $action . "_name";

            $this->info("You will use {$this->hero->$actionName}");

            // Decide on the enemy's action
            $enemyAction = $this->enemy->getActionDecision();

            if ($enemyAction == 'attack') {
                $this->info("{$this->enemy->name} will attack with {$this->enemy->attack_name}");
            } else {
                $this->info("{$this->enemy->name} will heal with {$this->enemy->heal_name}");
            }

            $this->confirm('Ready to roll?', true);

            $roll = rand(1,6);

            $this->info("The roll was a $roll");

            // Hero was successful
            if ($roll > 3) {
                if ($action == 'attack') {
                    $this->info("Your attack was successful! [-{$this->hero->attack_points}]");
                    $this->enemy->currentHP -= $this->hero->attack_points;
                } else {
                    $this->info("Your healing was successful! [+{$this->hero->heal_points}]");
                    $this->hero->currentHP += $this->hero->heal_points;

                    // Don't let the hero go above their max
                    if ($this->hero->currentHP > $this->hero->hp) {
                        $this->hero->resetHP();
                    }
                }
            // The hero failed
            } else {
                if ($enemyAction == 'attack') {
                    $this->error("{$this->enemy->name}'s attack was successful! [-{$this->enemy->currentAttack}]");
                    $this->hero->currentHP -= $this->enemy->currentAttack;
                } else {
                    $this->error("{$this->enemy->name}'s healing was successful! [-{$this->enemy->currentHeal}]");
                    $this->enemy->currentHP += $this->enemy->currentHeal;

                    // Don't let the enemy go above their max
                    if ($this->enemy->currentHP > $this->enemy->getMaxHP()) {
                        $this->enemy->currentHP = $this->enemy->getMaxHP();
                    }
                }
            }

            $this->line('------------------------------------------------------------------');
            $this->line('------------------------------------------------------------------');
        }

        $this->completeBattle();
    }

    public function completeBattle()
    {

        if ($this->hero->currentHP > 0) {
            $this->hero->resetHP();
            $this->hero->increaseVictories();

            if ($this->hero->canLevelUp()) {
                $selection = $this->menu('You won!', [
                    'Level up!'
                ])
                ->disableDefaultItems()
                ->open();
                $this->levelUp();
            } else {
                $selection = $this->menu('You won!', [])->open();
                $this->actionMenu();
            }
        } else {
            $this->hero->resetHP();
            $this->hero->decreaseVictories();
            $selection = $this->menu('You lost!', [
                'Keep playing'
            ])
            ->addStaticItem('Penalty: -1 XP point')
            ->open();

            if ($selection == 0) {
                $this->actionMenu();
            }
        }
    }

    public function levelUp()
    {
        $selection = $this->menu("Level up! {$this->hero->getInfoLine()}", [
            'Add 1 point to HP',
            "Add 1 point to {$this->hero->attack_name} (ATK)",
            "Add 1 point to {$this->hero->heal_name} (HL)",
        ])
        ->disableDefaultItems()
        ->open();

        switch ($selection) {
            case 0:
                $this->hero->hp++;
                break;
            case 1:
                $this->hero->attack_points++;
                break;
            case 2:
                $this->hero->heal_points++;
        }

        $this->hero->level++;
        $this->hero->victories = 0;
        $this->hero->save();

        $this->actionMenu();
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
