<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SaveSoldItemService;

class SaveSoldItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:save_sold_item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save sold item';

    protected $saveSoldItemService;

    /**
     * Create a new command instance.
     *
     * @param SaveSoldItemService $saveSoldItemService
     * @return void
     */
    public function __construct(SaveSoldItemService $saveSoldItemService)
    {
        parent::__construct();
        $this->saveSoldItemService = $saveSoldItemService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->saveSoldItemService->saveSoldItem();
    }
}
