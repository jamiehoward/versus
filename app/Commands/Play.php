<?php

namespace App\Commands;

use App\Models\Hero;
use App\Models\Enemy;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Play extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'play';

    public $hero;
    public $enemy;

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Begin the game!';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Welcome to Versus!');
        $this->startMenu();
    }

    public function startMenu($message = null)
    {
        $selection = $this->menu('Start menu' . $message, [
            'Select existing hero',
            'Create a new hero',
        ])->open();

        switch ($selection) {
            case 0:
                $this->selectHero();
                break;
            case 1:
                $this->createHero();
                break;
        }
    }

    public function createHero()
    {
        $hero = new Hero;
        $this->info('Create your hero!');
        $hero->name = $this->ask('What is your hero\'s name?');
        $hero->attack_name = $this->ask('What is your hero\'s attack called?');
        $hero->heal_name = $this->ask('What is your hero\'s healing power called?');

        $hero->save();

        $this->startMenu(' - Hero saved!');
    }

    public function selectHero()
    {
        $heroes = Hero::get();
        $options = $heroes->map(function($hero) {
            return $hero->name . " ({$hero->level})";
        })->all();

        $selection = $this->menu('Select your hero', $options)->open();

        $this->hero = $heroes[$selection];

        $this->actionMenu();

        exit();
    }

    public function actionMenu()
    {
        if (!$this->hero) {
            $this->selectHero();
        }

        $this->hero->resetHP();
        $selection = $this->menu("Playing as {$this->hero->getInfoLine()}", [
            'Enter combat zone',
            'Edit hero',
        ])->open();

        switch ($selection) {
            case 0:
                $this->combatZone();
                break;
            case 1:
                $this->editHero();
                break;
            default:
                exit();
        }

        exit();
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
            $this->info("{$this->enemy->name} will attack with {$this->enemy->attack_name}");

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
                $this->error("{$this->enemy->name}'s attack was successful! [-{$this->enemy->currentAttack}]");
                $this->hero->currentHP -= $this->enemy->currentAttack;
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
