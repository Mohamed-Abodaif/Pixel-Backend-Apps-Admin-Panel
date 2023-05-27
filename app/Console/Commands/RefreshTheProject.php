<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshTheProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It Refresh The Project And Make It Ready To Use Without Execution Any Other Command';

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
        Artisan::call("migrate:fresh");
        Artisan::call("db:seed");
        Artisan::call("passport:install");

        Artisan::call("storage:link");
        Artisan::call("optimize:clear");
//        Artisan::call("config:cache");
//        Artisan::call("routes:cache");
        $this->info("Project Has Been Refreshed");

    }
}
