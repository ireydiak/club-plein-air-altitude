<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class migrate_and_seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables, re-run all migrations and seed the database with records';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Migrating...');
        Artisan::call('migrate:fresh');
        $this->info('Seeding...');
        Artisan::call('db:seed');
        $this->info('Migration and seeding successfully completed');
    }
}
