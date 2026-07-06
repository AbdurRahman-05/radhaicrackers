<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;

class ExpireCouponsWithReachedLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:expire-reached-limits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire coupons that have reached their usage limits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for coupons that have reached their usage limits...');
        
        // Find coupons that have reached their usage limits but are still active
        $couponsToExpire = Coupon::where('is_active', true)
            ->whereNotNull('usage_limit')
            ->whereRaw('used_count >= usage_limit')
            ->where('usage_limit', '>', 0)
            ->get();
        
        if ($couponsToExpire->isEmpty()) {
            $this->info('No coupons found that need to be expired.');
            return;
        }
        
        $this->info("Found {$couponsToExpire->count()} coupons that have reached their usage limits:");
        
        $expiredCount = 0;
        foreach ($couponsToExpire as $coupon) {
            $this->line("  - {$coupon->code}: {$coupon->used_count}/{$coupon->usage_limit} (Limit Reached)");
            
            // Expire the coupon
            $coupon->update([
                'is_active' => false,
                'expires_at' => now()
            ]);
            
            $expiredCount++;
            
            $this->info("    ✓ Expired coupon: {$coupon->code}");
        }
        
        $this->info("\nSuccessfully expired {$expiredCount} coupons.");
        
        // Also check for any coupons that should be reactivated (if usage was reset)
        $this->info("\nChecking for coupons that should be reactivated...");
        
        $couponsToReactivate = Coupon::where('is_active', false)
            ->whereNotNull('usage_limit')
            ->whereRaw('used_count < usage_limit')
            ->where('usage_limit', '>', 0)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();
        
        if ($couponsToReactivate->isNotEmpty()) {
            $this->info("Found {$couponsToReactivate->count()} coupons that can be reactivated:");
            
            foreach ($couponsToReactivate as $coupon) {
                $this->line("  - {$coupon->code}: {$coupon->used_count}/{$coupon->usage_limit}");
                
                $coupon->update(['is_active' => true]);
                $this->info("    ✓ Reactivated coupon: {$coupon->code}");
            }
        } else {
            $this->info("No coupons found that need reactivation.");
        }
        
        $this->info("\nCoupon expiration check completed!");
    }
}