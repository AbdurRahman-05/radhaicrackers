<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreateSampleStockLogs extends Command
{
    protected $signature = 'stock:create-sample-logs';
    protected $description = 'Create sample stock logs for testing';

    public function handle()
    {
        $stocks = Stock::all();
        $users = User::where('is_admin', true)->get();

        if ($stocks->isEmpty()) {
            $this->error('No stocks found. Please create some stocks first.');
            return 1;
        }

        if ($users->isEmpty()) {
            $this->error('No admin users found. Please create an admin user first.');
            return 1;
        }

        $actions = ['release', 'expire', 'reset', 'manual'];
        $details = [
            'release' => ['Auto-release triggered', 'Manual release', 'Batch release'],
            'expire' => ['Auto-expire triggered', 'Manual expire', 'Stock expired'],
            'reset' => ['Stock reset', 'Quantity reset', 'Manual reset'],
            'manual' => ['Manual adjustment', 'Admin update', 'Quantity correction']
        ];

        $this->info('Creating sample stock logs...');

        for ($i = 0; $i < 50; $i++) {
            $stock = $stocks->random();
            $user = $users->random();
            $action = $actions[array_rand($actions)];
            $detail = $details[$action][array_rand($details[$action])];
            
            // Create log with random date in the last 30 days
            $randomDate = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            StockLog::create([
                'stock_id' => $stock->id,
                'action' => $action,
                'details' => $detail,
                'quantity_before' => rand(0, 100),
                'quantity_after' => rand(0, 100),
                'performed_by' => $user->id,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }

        $this->info('Sample stock logs created successfully!');
        $this->info('Total logs: ' . StockLog::count());
        $this->info('Today\'s logs: ' . StockLog::today()->count());
        
        return 0;
    }
} 