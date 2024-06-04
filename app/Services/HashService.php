<?php

namespace App\Services;

use App\Models\Worker;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Http;

class HashService
{
    /**
     * Calculates hashrate for every day between two dates
     *
     * @return array
     */
    public function calculateHash($rq): array
    {
        // get workers with hashrates
        $workers = Worker::with(['hashrates' => function ($query) use ($rq){
            $query->whereBetween('date', [$rq->start_date, $rq->end_date]);
        }])->get();

        // prepare array for result
        $result['dates'] = [];
        foreach ($workers as $worker){
            // set worker info for result
            $result['workers'][$worker->id] = [
                'worker_id' => $worker->worker_id,
                'worker_name' => $worker->worker_name,
                'model_name' => $worker->model_name,
                'price_sum' => 0,
                'hashrates' => []
            ];

            // create workers hashrates array for later usage
            $workersArray[$worker->id] = $worker->hashrates->mapWithKeys(function ($item){
                return [$item['date'] => $item];
            })->toArray();
        }

        // get dates between two given dates as carbon object
        $dates = CarbonPeriod::create($rq->start_date, $rq->end_date);

        // for every date
        foreach ($dates as $date){
            $date = $date->format('Y-m-d');
            $isHashrateAvailableForThisDate = false;
            $workersHashratesAndPriceForThisDate = [];

            // for every worker
            foreach ($workers as $worker){
                // check if worker has hashrate for this day
                if (isset($workersArray[$worker->id][$date])){
                    $isHashrateAvailableForThisDate = true;

                    //array of hashrate and price calculated by given formula
                    $hashrateAndPrice = [
                        'hashrate' => $workersArray[$worker->id][$date]['hashrate'],
                        'price' => intval(($rq->tariff * $rq->consumption * 24 / 13.5)
                            * intval($workersArray[$worker->id][$date]['hashrate']))
                    ];

                    // add hashrates and prices to array
                    $workersHashratesAndPriceForThisDate[$worker->id] = $hashrateAndPrice;
                }
                // if worker don't have hashrate for this date add empty value to result
                else {
                    $workersHashratesAndPriceForThisDate[$worker->id] = ['hashrate'=>'','price'=>''];
                }
            }

            // if hashrate available for this day,
            if ($isHashrateAvailableForThisDate){
                // add date to results
                $result['dates'][] = $date;

                // add hashrates to result for every worker
                foreach ($workers as $worker){
                    $result['workers'][$worker->id]['hashrates'][] = $workersHashratesAndPriceForThisDate[$worker->id];
                    $result['workers'][$worker->id]['price_sum'] += intval($workersHashratesAndPriceForThisDate[$worker->id]['price']);
                }
            }
        }

        return $result;
    }



    /**
     * Get data from pool
     *
     */
    public function getDataFromPool($url, $params): array
    {
        $authParams = [
            'access_key' => 'r_dZQDQ9FStM9lZ',
            'puid' => 441535,
        ];

        $options = [];
        // use proxy ?
        if (env('USE_PROXY'))
            $options['proxy'] = env('PROXY');

        $response = Http::withOptions($options)->get($url, array_merge($authParams, $params));

        if(!$response->successful())
            return ['error' => true];

        if ($response['err_no'] != 0)
            return ['error' => true];

        return ['error' => false, 'data' => $response['data']];
    }


    /**
     * Adds workers' new data from pool to db
     *
     */
    public function addWorkersDataFromPool()
    {
        $url = 'https://pool.btc.com/v1/worker';
        $params = ['status' => 'all',];

        // get new worker's data from pool
        $data = $this->getDataFromPool($url, $params);

        if ($data['error'])
            return 'Error occurred while getting data from the pool';

        // for every data worker
        foreach ($data['data']['data'] as $item){
            // check if worker exist in DB, if not, add to DB, returns worker
            $worker = Worker::firstOrCreate([
                'worker_id' => $item['worker_id']
            ],[
                'worker_name' => $item['worker_name']
            ]);

            // for that worker, create today's hashrate  // don't need to check if exists, cuz it works once every day
            $worker->hashrates()->create([
                'worker_name' => $item['worker_name'],
                'hashrate' => $item['shares_1d'],
                'reject' => $item['reject_percent_1d'],
                'date' => now()->format('Y-m-d'),
            ]);
        }

        return 'Successful';
    }


    /**
     * Checks for every workers' previous day's hashrate and update
     *
     */
    public function checkEveryWorkersYesterday()
    {
        // for every worker
        foreach (Worker::all() as $worker){
            // get hashrate for yesterday
            $yesterday = now()->subDay(1);
            $params = [
                'dimension' => '1d',
                'start_ts' => $yesterday->unix(),
                'count' => 1,
            ];
            $url = "https://pool.btc.com/v1/worker/{$worker->worker_id}/share-history";
            $data = $this->getDataFromPool($url, $params);

            if ($data['error'])
                continue;

            // update yesterday's hashrate
            $worker->hashrates()
                ->where('date', $yesterday->format('Y-m-d'))
                ->update([
                    'hashrate' => $data['data']['tickers'][0][1]
                ]);
        }
    }
}
