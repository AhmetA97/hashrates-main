<?php

namespace App\Console\Commands;

use App\Services\HashService;
use Illuminate\Console\Command;

class GetHashrates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hashrates:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets new hashrates from pool.btc.com';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hash = new HashService();
        echo "Getting workers:\n";
        echo $hash->addWorkersDataFromPool();
        echo "\nCheck every workers yesterday:";
        $hash->checkEveryWorkersYesterday();
        echo "\nDone!\n";
    }
}
