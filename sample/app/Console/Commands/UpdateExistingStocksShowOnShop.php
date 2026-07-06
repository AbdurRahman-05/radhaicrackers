<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;

class UpdateExistingStocksShowOnShop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:update-show-on-shop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all existing stocks to have show_on_shop set to true';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating existing stocks to show on shop by default...');
        
        $updatedCount = Stock::where('show_on_shop', false)->update(['show_on_shop' => true]);
        
        $this->info("Updated {$updatedCount} stocks to show on shop by default.");
        
        return Command::SUCCESS;
    }
}
