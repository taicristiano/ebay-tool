<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BuyFromYahooAuctionService;

class BuyFromYahooAuctionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:buy_from_yahoo_auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buy from yahoo auction';

    protected $buyFromYahooAuctionService;

    /**
     * Create a new command instance.
     *
     * @param BuyFromYahooAuctionService $buyFromYahooAuctionService
     * @return void
     */
    public function __construct(BuyFromYahooAuctionService $buyFromYahooAuctionService)
    {
        parent::__construct();
        $this->buyFromYahooAuctionService = $buyFromYahooAuctionService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->buyFromYahooAuctionService->buyFromYahooAuction();
    }
}
