<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ByFromYahooAuctionService;

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

    protected $byFromYahooAuctionService;

    /**
     * Create a new command instance.
     *
     * @param ByFromYahooAuctionService $byFromYahooAuctionService
     * @return void
     */
    public function __construct(ByFromYahooAuctionService $byFromYahooAuctionService)
    {
        parent::__construct();
        $this->byFromYahooAuctionService = $byFromYahooAuctionService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->byFromYahooAuctionService->byFromYahooAuction();
    }
}
