<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\MtbExchangeRate;
use Goutte\Client;

class ExchangeRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:exchange_rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call api https://www.gaitameonline.com/rateaj/getrate and insert into table mtb_exchange_rate';

    protected $exchangeRate;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MtbExchangeRate $exchangeRate)
    {
        parent::__construct();
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $url = 'https://www.gaitameonline.com/rateaj/getrate';
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler->filter('p')->each(function ($node) {
                $data = json_decode($node->text());
                foreach ($data->quotes as $key => $value) {
                    if ($value->currencyPairCode == 'USDJPY') {
                        $dataExchangeRate['exchange_date'] = date('Y-m-d H:i:s');
                        $dataExchangeRate['rate'] = $value->ask;
                        $exchangeRate = $this->exchangeRate;
                        $exchangeRate->fill($dataExchangeRate);
                        $exchangeRate->save();
                    }
                }
            });
            Log::info('Exchange rate command success');
        } catch (Exception $e) {
            Log::info('Exchange rate command error');
        }
    }
}
