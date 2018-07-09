<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CheckProductEbayService;

class CheckProductEbayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_product_ebay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check on their products sold on ebay have buyers';

    protected $productEbayService;

    /**
     * Create a new command instance.
     *
     * @param CheckProductEbayService $productEbayService
     * @return void
     */
    public function __construct(CheckProductEbayService $productEbayService)
    {
        parent::__construct();
        $this->productEbayService = $productEbayService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->productEbayService->checkOnTheirProductsSoldOnEbayHaveBuyers();
    }
}
