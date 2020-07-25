<?php

namespace App\Console\Commands;

use Caroler;
use Illuminate\Console\Command;

class CarolerSing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caroler:sing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiates the Caroler Discord bot.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Caroler::outputWriter($this->getOutput())->sing();

        return 0;
    }
}
