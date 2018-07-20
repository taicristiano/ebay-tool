<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UpdateTableChangeItemService;

class UpdateTableChangeItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_table_change_item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update table change item';

    protected $updateTableChangeItemService;

    /**
     * Create a new command instance.
     *
     * @param UpdateTableChangeItemService $updateTableChangeItemService
     * @return void
     */
    public function __construct(UpdateTableChangeItemService $updateTableChangeItemService)
    {
        parent::__construct();
        $this->updateTableChangeItemService = $updateTableChangeItemService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->updateTableChangeItemService->updateTableChangeItem();
    }
}
