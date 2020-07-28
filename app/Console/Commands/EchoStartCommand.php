<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EchoStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'echo:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'by this command run laravel-echo-server';

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
        exec('laravel-echo-server start');
    }
}
