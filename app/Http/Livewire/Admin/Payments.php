<?php

namespace App\Http\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $date_from = '';
    public $date_to = '';
    public $selectedPayments = [];
    public $selected_year = '';
    public $available_years = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'selected_year' => ['except' => ''],
    ];

    public function mount()
    {
        if (!$this->selected_year) {
            $this->selected_year = date('Y');
        }

        // Get unique years from payments
        $paymentYears = Payment::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (!in_array(date('Y'), $paymentYears)) {
            array_unshift($paymentYears, (int)date('Y'));
        }

        $this->available_years = $paymentYears;
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'status_filter', 'date_from', 'date_to']);
        $this->selected_year = date('Y');
        $this->resetPage();
    }

    public function verifyPayment($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            session()->flash('error', 'Payment not found.');
            return;
        }

        $payment->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        // Update associated order payment status
        if ($payment->order) {
            $payment->order->update(['payment_status' => 'paid']);
        }

        session()->flash('success', 'Payment verified successfully!');
    }

    public function rejectPayment($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            session()->flash('error', 'Payment not found.');
            return;
        }

        $payment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        session()->flash('success', 'Payment rejected successfully!');
    }

    public function bulkVerify()
    {
        if (empty($this->selectedPayments)) {
            session()->flash('error', 'Please select payments to verify.');
            return;
        }

        Payment::whereIn('id', $this->selectedPayments)
            ->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);

        // Update associated orders
        $payments = Payment::whereIn('id', $this->selectedPayments)->get();
        foreach ($payments as $payment) {
            if ($payment->order) {
                $payment->order->update(['payment_status' => 'paid']);
            }
        }

        $this->selectedPayments = [];
        session()->flash('success', 'Selected payments verified successfully!');
    }

    public function exportPayments()
    {
        $payments = $this->getFilteredPayments();
        
        $filename = 'payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $csvData = [];
        
        // CSV headers
        $csvData[] = [
            'Payment ID', 'Order ID', 'Customer', 'Phone', 'Amount', 
            'UPI ID', 'Transaction ID', 'Status', 'Created Date', 'Verified Date'
        ];

        foreach ($payments as $payment) {
            $csvData[] = [
                $payment->id,
                $payment->order_id,
                $payment->order->user->name ?? 'N/A',
                $payment->order->user->phone ?? 'N/A',
                '₹' . number_format($payment->amount, 2),
                $payment->upi_id,
                $payment->transaction_id,
                $payment->status,
                $payment->created_at->format('Y-m-d H:i:s'),
                $payment->verified_at ? $payment->verified_at->format('Y-m-d H:i:s') : 'N/A'
            ];
        }

        // Convert to CSV string
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        // Store CSV content in session for download
        session(['export_csv_content' => $csvContent, 'export_csv_filename' => $filename]);
        
        // Dispatch download event
        $this->dispatch('download-csv');
        
        session()->flash('success', 'Export ready! Download will start automatically.');
    }

    private function getFilteredPayments()
    {
        $query = Payment::with(['order.user'])
            ->orderBy('created_at', 'desc');

        if ($this->selected_year) {
            $query->whereYear('created_at', $this->selected_year);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('upi_id', 'like', '%' . $this->search . '%')
                  ->orWhere('transaction_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('order.user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('phone', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        return $query->get();
    }

    public function render()
    {
        $payments = $this->getFilteredPayments();
        
        $statsQuery = Payment::query();
        if ($this->selected_year) {
            $statsQuery->whereYear('created_at', $this->selected_year);
        }
        
        return view('livewire.admin.payments', [
            'payments' => $payments,
            'totalPayments' => (clone $statsQuery)->count(),
            'verifiedPayments' => (clone $statsQuery)->where('status', 'verified')->count(),
            'pendingPayments' => (clone $statsQuery)->where('status', 'pending')->count(),
            'rejectedPayments' => (clone $statsQuery)->where('status', 'rejected')->count(),
            'totalAmount' => (clone $statsQuery)->where('status', 'verified')->sum('amount'),
        ]);
    }
} 