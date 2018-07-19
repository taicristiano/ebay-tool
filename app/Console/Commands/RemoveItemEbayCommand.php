<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RemoveItemEbayService;

class RemoveItemEbayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remove_item_ebay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove item ebay';

    protected $removeItemEbayService;

    /**
     * Create a new command instance.
     *
     * @param RemoveItemEbayService $removeItemEbayService
     * @return void
     */
    public function __construct(RemoveItemEbayService $removeItemEbayService)
    {
        parent::__construct();
        $this->removeItemEbayService = $removeItemEbayService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->removeItemEbayService->removeItemEbay();
    }
}
