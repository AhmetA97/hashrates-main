<?php

namespace Database\Seeders;

use App\Models\Hashrate;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerAndHashrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create workers
        $workers = Worker::factory(2)->create();

        // for every day in last 30 days
        foreach (range(30, 1) as $day){
            $date = Carbon::now()->subDays($day)->format('Y-m-d');

            // for every worker
            foreach ($workers as $worker) {
                // create a hashrate
                Hashrate::factory()->create([
                    'worker_id' => $worker->worker_id,
                    'worker_name' => $worker->worker_name,
                    'date' => $date
                ]);
            }
        }
    }
}
