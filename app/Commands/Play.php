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
                $this->createCharacter();
                break;
            case 1:
                $this->selectCharacter();
                break;
        }
    }

    public function createCharacter()
    {
        $hero = new Hero;
        $this->info('Create your hero!');
        $hero->name = $this->ask('What is your hero\'s name?');
        $hero->attack_name = $this->ask('What is your hero\'s attack called?');
        $hero->heal_name = $this->ask('What is your hero\'s healing power called?');

        $hero->save();

        $this->startMenu(' - Hero saved!');
    }

    public function selectCharacter()
    {
        $heroes = Hero::get();
        $options = $heroes->map(function($hero) {
            return $hero->name . " ({$hero->level})";
        })->all();

        $selection = $this->menu('Select your character', $options)->open();

        $this->hero = $heroes[$selection];

        $this->info("You selected {$this->hero->name}!");
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
