<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Currency;
use File;

class CurrencyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency_rate:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // set API Endpoint and API key
        // $endpoint = 'latest';
        // $access_key = '93b347e975ff4811def3f9f691f30cf6';

        // // Initialize CURL:
        // $ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // // Store the data:
        // $json = curl_exec($ch);
        // curl_close($ch);

        // // Decode JSON response:
        // $exchangeRates = json_decode($json, true);
        // $exchangeRates = $exchangeRates['rates'];
        // foreach($exchangeRates as $key => $currency_rate) {
        //     Currency::where('short_code',$key)->update([
        //         'rate' => $currency_rate
        //     ]);
        // }

        $currencies = Currency::get();
        // http://api.exchangeratesapi.io/v1/latest?access_key=615882d18f9992e0de84e5a0d06ad009
        $endpoint = "http://api.exchangeratesapi.io/v1/latest?access_key=615882d18f9992e0de84e5a0d06ad009";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody(), true);

        if($statusCode == 200) {
            $base_euro=$content["rates"];

            /**
                "EUR"=> 1,
                "USD"=> 1.183849,
                "PKR"=> 191.553383,
             */
            $euro_rate = $base_euro["EUR"]/$base_euro["USD"];
            foreach($currencies as $currency) {
                if(array_key_exists($currency->short_code, $base_euro)) {
                    $currency->rate = $euro_rate*$base_euro[$currency->short_code];
                    $currency->updated_at = now();
                    $currency->update();
                }
            }
        }
    }
}
