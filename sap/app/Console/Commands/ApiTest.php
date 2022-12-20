<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\ApiTests;

class ApiTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api {userId} {idStore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Api test with create order and add queue.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lib = new ApiTests;
        $lib->add_order($this->argument('userId'), $this->argument('idStore'));
    }
}
