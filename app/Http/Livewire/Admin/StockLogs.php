<?php

namespace App\Http\Livewire\Admin;

use App\Models\Stock;
use App\Models\StockLog;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class StockLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $stock_filter = '';
    public $action_filter = '';
    public $date_from = '';
    public $date_to = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'stock_filter' => ['except' => ''],
        'action_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStockFilter()
    {
        $this->resetPage();
    }

    public function updatedActionFilter()
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
        $this->reset(['search', 'stock_filter', 'action_filter', 'date_from', 'date_to']);
        $this->resetPage();
    }

    public function logStockAction($stockId, $action, $details = '')
    {
        $stock = Stock::find($stockId);
        if (!$stock) {
            return;
        }

        $stock->logAction($action, $details);
    }

    public function exportLogs()
    {
        $logs = StockLog::with(['stock', 'performedBy'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('stock', function($stockQuery) {
                        $stockQuery->where('item_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('details', 'like', '%' . $this->search . '%')
                    ->orWhereHas('performedBy', function($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->stock_filter, function($query) {
                $query->where('stock_id', $this->stock_filter);
            })
            ->when($this->action_filter, function($query) {
                $query->where('action', $this->action_filter);
            })
            ->when($this->date_from, function($query) {
                $query->whereDate('created_at', '>=', $this->date_from);
            })
            ->when($this->date_to, function($query) {
                $query->whereDate('created_at', '<=', $this->date_to);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'stock_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Date', 'Stock Name', 'Action', 'Details', 'Quantity Before', 
                'Quantity After', 'Performed By'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->stock->item_name ?? 'Unknown Stock',
                    $log->action,
                    $log->details,
                    $log->quantity_before,
                    $log->quantity_after,
                    $log->performedBy->name ?? 'System'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getFilteredLogs()
    {
        $query = StockLog::with(['stock' => function($query) {
            $query->select('id', 'item_name', 'image');
        }, 'performedBy'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('stock', function($stockQuery) {
                    $stockQuery->where('item_name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('details', 'like', '%' . $this->search . '%')
                ->orWhereHas('performedBy', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->stock_filter) {
            $query->where('stock_id', $this->stock_filter);
        }

        if ($this->action_filter) {
            $query->where('action', $this->action_filter);
        }

        if ($this->date_from) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        return $query->paginate(20);
    }

    public function render()
    {
        $logs = $this->getFilteredLogs();
        $stocks = Stock::where('is_active', true)->get();
        
        return view('livewire.admin.stock-logs', [
            'logs' => $logs,
            'stocks' => $stocks,
            'totalLogs' => StockLog::count(),
            'todayLogs' => StockLog::today()->count(),
            'releasesToday' => StockLog::today()->byAction('release')->count(),
            'expiresToday' => StockLog::today()->byAction('expire')->count(),
        ]);
    }
} 