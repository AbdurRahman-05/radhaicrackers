<?php

namespace App\Console\Commands;

use App\Services\PDFService;
use Illuminate\Console\Command;

class TestPdfGeneration extends Command
{
    protected $signature = 'pdf:test';
    protected $description = 'Test PDF generation functionality';

    public function handle()
    {
        $this->info('Testing PDF generation...');

        try {
            $pdfService = new PDFService();

            // Test stock report
            $this->info('Testing stock report generation...');
            $stocks = \App\Models\Stock::orderBy('created_at', 'desc')->get();
            $pdf = $pdfService->generateStockReport($stocks);
            $this->info('✓ Stock report generated successfully');

            // Test order report
            $this->info('Testing order report generation...');
            $orders = \App\Models\Order::with(['user', 'items'])
                ->orderBy('created_at', 'desc')
                ->get();
            $pdf = $pdfService->generateOrderReport($orders);
            $this->info('✓ Order report generated successfully');

            // Test payment report
            $this->info('Testing payment report generation...');
            $payments = \App\Models\Payment::with(['order.user'])
                ->orderBy('created_at', 'desc')
                ->get();
            $pdf = $pdfService->generatePaymentReport($payments);
            $this->info('✓ Payment report generated successfully');

            $this->info('All PDF generation tests passed!');

        } catch (\Exception $e) {
            $this->error('PDF generation test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
} 