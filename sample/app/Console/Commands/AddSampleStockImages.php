<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AddSampleStockImages extends Command
{
    protected $signature = 'stock:add-sample-images';
    protected $description = 'Add sample images to stocks for testing';

    public function handle()
    {
        $stocks = Stock::whereNull('image')->get();

        if ($stocks->isEmpty()) {
            $this->info('All stocks already have images or no stocks found.');
            return 0;
        }

        $this->info('Adding sample images to stocks...');

        // Sample image paths (these should exist in public/images/shop/)
        $sampleImages = [
            'shop/4-gold-lakshmi-5pcs-pocket-500x500-1-300x300.webp',
            'shop/New-Project-2025-06-03T153421.966-300x300.jpg',
            'shop/New-Project-2025-06-03T161559.928-300x300.jpg',
            'shop/New-Project-2025-06-03T163605.980-300x300.jpg',
            'shop/New-Project-2025-06-03T163911.021-300x300.jpg',
            'shop/New-Project-2025-06-03T172230.069-300x300.jpg',
            'shop/New-Project-2025-06-03T174715.204-300x300.jpg',
            'shop/New-Project-2025-06-04T181137.771-300x300.jpg',
            'shop/New-Project-2025-06-04T181316.308-300x300.jpg',
            'shop/New-Project-2025-06-04T181533.348-300x300.jpg',
        ];

        $imageIndex = 0;
        foreach ($stocks as $stock) {
            // Check if sample image exists
            $sampleImage = $sampleImages[$imageIndex % count($sampleImages)];
            $sourcePath = public_path('images/' . $sampleImage);
            
            if (file_exists($sourcePath)) {
                // Copy to storage
                $destinationPath = 'stocks/' . basename($sampleImage);
                Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));
                
                // Update stock with image path
                $stock->update(['image' => $destinationPath]);
                
                $this->info("Added image to stock: {$stock->item_name}");
            } else {
                $this->warn("Sample image not found: {$sampleImage}");
            }
            
            $imageIndex++;
        }

        $this->info('Sample images added successfully!');
        $this->info('Stocks with images: ' . Stock::whereNotNull('image')->count());
        
        return 0;
    }
} 