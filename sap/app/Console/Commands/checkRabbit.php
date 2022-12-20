<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\AMQLib;

class checkRabbit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:rabbit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get content from RabbitMq';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lib = new AMQLib;
        $lib->consume();
    }
}
