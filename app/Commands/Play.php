<?php

namespace App\Commands;

use App\Models\Hero;
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
            'Create a new hero',
            'Select existing hero',
        ])->open();

        switch ($selection) {
            case 0:
                $this->createHero();
                break;
            case 1:
                $this->selectHero();
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
    }

    public function actionMenu()
    {
        if (!$this->hero) {
            $this->selectHero();
        }

        $selection = $this->menu("Playing as {$this->hero->name}", [
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
        }
    }

    public function combatZone()
    {
        $enemy = $this->getRandom
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
