<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;

class TestStockSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:stock-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the stock management system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Stock Management System...');
        
        // Test 1: Check if stocks exist
        $totalStocks = Stock::count();
        $this->info("Total stocks in database: {$totalStocks}");
        
        // Test 2: Check active stocks
        $activeStocks = Stock::where('is_active', true)->count();
        $this->info("Active stocks: {$activeStocks}");
        
        // Test 3: Check available stocks (quantity > 0)
        $availableStocks = Stock::where('quantity', '>', 0)->count();
        $this->info("Available stocks (quantity > 0): {$availableStocks}");
        
        // Test 4: Check categories
        $categories = Stock::select('category')->distinct()->pluck('category');
        $this->info("Available categories: " . $categories->implode(', '));
        
        // Test 5: Show sample stock
        $sampleStock = Stock::first();
        if ($sampleStock) {
            $this->info("\nSample Stock:");
            $this->info("- Name: {$sampleStock->item_name}");
            $this->info("- Category: {$sampleStock->category}");
            $this->info("- Price: ₹{$sampleStock->price}");
            $this->info("- Quantity: {$sampleStock->quantity}");
            $this->info("- Active: " . ($sampleStock->is_active ? 'Yes' : 'No'));
        }
        
        // Test 6: Check field compatibility
        $this->info("\nField Compatibility Check:");
        $requiredFields = ['item_name', 'quantity', 'price', 'category', 'is_active'];
        foreach ($requiredFields as $field) {
            $hasField = Stock::whereNotNull($field)->exists();
            $this->info("- {$field}: " . ($hasField ? '✓' : '✗'));
        }
        
        $this->info("\nStock system test completed!");
    }
}
