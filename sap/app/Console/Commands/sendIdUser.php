<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\AMQLib;

class sendIdUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send assembly order accepted to RabbitMQ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lib = new AMQLib;
        $lib->publishTest();
    }
}
