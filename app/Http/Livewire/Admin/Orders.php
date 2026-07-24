<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderLog;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $payment_filter = '';
    public $date_from = '';
    public $date_to = '';
    public $selectedOrders = [];
    public $selected_year = '';
    public $available_years = [];
    public $editHasGst = false;
    public $editTransportProvider = '';
    public $editTransportDetails = '';
    public $editDeliveryType = '';
    public $delivery_type_filter = '';

    // Modal properties
    public $showEditModal = false;
    public $editingOrderId = null;
    public $editStatus = '';
    public $editPaymentStatus = '';
    public $editNotes = '';
    public $editReceiveAmount = 0;

    // Customer & Delivery Edit properties
    public $editCustomerName = '';
    public $editCustomerMobile = '';
    public $editCustomerEmail = '';
    public $editCustomerState = '';
    public $editCustomerDistrict = '';
    public $editCustomerCity = '';
    public $editDeliveryPoint = '';
    public $editPinCode = '';

    // Order Items editing array
    public $editingOrderItems = [];

    // Add Items properties
    public $newItemSearch = '';
    public $newItemId = null;
    public $newItemQty = 1;
    public $searchItemsList = [];

    public $editingReceiveAmountId = null;
    public $receiveAmountInput = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => ''],
        'payment_filter' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'selected_year' => ['except' => ''],
        'delivery_type_filter' => ['except' => ''],
    ];

    public function mount()
    {
        // Get unique years from orders
        $orderYears = Order::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn($y) => (string)$y)
            ->toArray();

        $currentYear = date('Y');
        $defaultYears = [(string)$currentYear, (string)($currentYear - 1), (string)($currentYear - 2)];
        
        $allYears = array_values(array_unique(array_merge($orderYears, $defaultYears)));
        rsort($allYears);

        $this->available_years = $allYears;
    }



    protected $rules = [
        'editStatus' => 'required|in:pending,confirmed,dispatched,completed,cancelled',
        'editPaymentStatus' => 'required|in:pending,paid,failed',
        'editNotes' => 'nullable|string|max:500',
        'editReceiveAmount' => 'nullable|numeric|min:0',
        'editCustomerName' => 'required|string|max:255',
        'editCustomerMobile' => 'required|digits:10',
        'editCustomerEmail' => 'nullable|email',
        'editCustomerState' => 'required|string',
        'editCustomerDistrict' => 'required|string',
        'editCustomerCity' => 'required|string',
        'editDeliveryPoint' => 'required|string',
        'editPinCode' => 'required|digits:6',
    ];

    protected $messages = [
        'editStatus.required' => 'Order status is required.',
        'editStatus.in' => 'Please select a valid order status.',
        'editPaymentStatus.required' => 'Payment status is required.',
        'editPaymentStatus.in' => 'Please select a valid payment status.',
        'editNotes.max' => 'Notes cannot exceed 500 characters.',
        'editReceiveAmount.numeric' => 'Receive amount must be a number.',
        'editReceiveAmount.min' => 'Receive amount cannot be negative.',
        'editCustomerName.required' => 'Customer name is required.',
        'editCustomerMobile.required' => 'Mobile number is required.',
        'editCustomerMobile.digits' => 'Mobile number must be exactly 10 digits.',
        'editCustomerState.required' => 'State is required.',
        'editCustomerDistrict.required' => 'District is required.',
        'editCustomerCity.required' => 'City is required.',
        'editDeliveryPoint.required' => 'Delivery point is required.',
        'editPinCode.required' => 'Pin code is required.',
        'editPinCode.digits' => 'Pin code must be exactly 6 digits.',
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'status_filter', 'payment_filter', 'date_from', 'date_to', 'selected_year', 'delivery_type_filter'])) {
            $this->resetPage();
        }
    }


    public function clearFilters()
    {
        $this->reset(['search', 'status_filter', 'payment_filter', 'date_from', 'date_to', 'delivery_type_filter']);
        $this->selected_year = '';
        $this->resetPage();
    }


    public function openEditModal($orderId)
    {
        \Log::info('openEditModal called with orderId: ' . $orderId);
        
        $order = Order::find($orderId);
        if ($order) {
            $this->editingOrderId = $orderId;
            $this->editStatus = $order->status ?? 'pending';
            $this->editPaymentStatus = $order->payment_status ?? 'pending';
            $this->editNotes = $order->notes ?? '';
            $this->editReceiveAmount = $order->receive_amount !== null ? $order->receive_amount : (in_array($order->status, ['confirmed','dispatched','completed']) ? $order->total : 0);
            $this->editHasGst = $order->has_gst ? true : false;
            
            // Load customer & delivery properties
            $this->editCustomerName = $order->customer_name ?? '';
            $this->editCustomerMobile = $order->customer_mobile ?? '';
            $this->editCustomerEmail = $order->customer_email ?? '';
            $this->editCustomerState = $order->customer_state ?? '';
            $this->editCustomerDistrict = $order->customer_district ?? '';
            $this->editCustomerCity = $order->customer_city ?? '';
            $this->editDeliveryPoint = $order->delivery_point ?? '';
            $this->editPinCode = $order->pin_code ?? '';
            $this->editTransportProvider = $order->transport_provider ?? '';
            $this->editTransportDetails = $order->transport_details ?? '';
            $this->editDeliveryType = $order->delivery_type ?? 'none';

            // Load items
            $this->editingOrderItems = [];
            $items = is_array($order->items) ? $order->items : ($order->items_json ?? []);
            foreach ($items as $item) {
                if (is_object($item)) {
                    $item = (array)$item;
                }
                $this->editingOrderItems[] = [
                    'product_id' => $item['product_id'] ?? $item['stock_id'] ?? null,
                    'product_name' => $item['product_name'] ?? '',
                    'price' => $item['price'] ?? $item['rate'] ?? 0,
                    'original_price' => $item['original_price'] ?? $item['price'] ?? $item['rate'] ?? 0,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'special_discount_percentage' => $item['special_discount_percentage'] ?? 0,
                    'quantity' => $item['quantity'] ?? 0,
                ];
            }

            $this->showEditModal = true;
            
            \Log::info('Edit modal opened for order: ' . $orderId);
            \Log::info('Current values - Status: ' . $this->editStatus . ', Payment: ' . $this->editPaymentStatus . ', Notes: ' . $this->editNotes . ', Receive Amount: ' . $this->editReceiveAmount);
        } else {
            \Log::error('Order not found with ID: ' . $orderId);
            session()->flash('error', 'Order not found.');
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset([
            'editingOrderId', 'editStatus', 'editPaymentStatus', 'editNotes', 'editReceiveAmount',
            'editCustomerName', 'editCustomerMobile', 'editCustomerEmail', 'editCustomerState',
            'editCustomerDistrict', 'editCustomerCity', 'editDeliveryPoint', 'editPinCode',
            'editingOrderItems', 'editHasGst', 'editTransportProvider', 'editTransportDetails', 'editDeliveryType',
            'newItemSearch', 'newItemId', 'newItemQty', 'searchItemsList'
        ]);
    }

    public function fetchSearchResults()
    {
        $query = \App\Models\Stock::where('is_active', true);
        if ($this->newItemSearch) {
            $query->where('item_name', 'like', '%' . $this->newItemSearch . '%');
        }
        $this->searchItemsList = $query->limit(20)->get()->toArray();
    }

    public function updatedNewItemSearch($value)
    {
        $this->fetchSearchResults();
    }

    public function selectNewItem($stockId)
    {
        $stock = \App\Models\Stock::find($stockId);
        if ($stock) {
            $this->newItemId = $stock->id;
            $this->newItemSearch = $stock->item_name;
            $this->searchItemsList = [];
        }
    }

    public function addNewItem()
    {
        if (!$this->newItemId) {
            session()->flash('modal_error', 'Please select a product first.');
            return;
        }

        $stock = \App\Models\Stock::find($this->newItemId);
        if (!$stock) {
            session()->flash('modal_error', 'Selected product not found.');
            return;
        }

        if ($this->newItemQty <= 0) {
            session()->flash('modal_error', 'Quantity must be at least 1.');
            return;
        }

        // Check if item is already in editingOrderItems
        $foundIndex = null;
        foreach ($this->editingOrderItems as $index => $item) {
            if (($item['product_id'] ?? null) == $stock->id) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex !== null) {
            $this->editingOrderItems[$foundIndex]['quantity'] += (int)$this->newItemQty;
        } else {
            $this->editingOrderItems[] = [
                'product_id' => $stock->id,
                'product_name' => $stock->item_name,
                'price' => (float)$stock->price,
                'original_price' => (float)$stock->original_price,
                'discount_percentage' => (int)$stock->discount_percentage,
                'special_discount_percentage' => (int)$stock->special_discount_percentage,
                'quantity' => (int)$this->newItemQty,
            ];
        }

        // Reset inputs
        $this->newItemSearch = '';
        $this->newItemId = null;
        $this->newItemQty = 1;
        $this->searchItemsList = [];

        session()->flash('modal_success', 'Item added to order list.');
    }

    public function removeItem($index)
    {
        if (isset($this->editingOrderItems[$index])) {
            unset($this->editingOrderItems[$index]);
            $this->editingOrderItems = array_values($this->editingOrderItems);
        }
    }

    public function recalculateTotals()
    {
        $mrpSubtotal = 0;
        foreach ($this->editingOrderItems as $item) {
            $originalPrice = $item['original_price'] ?? $item['price'] ?? 0;
            $mrpSubtotal += $originalPrice * (int)($item['quantity'] ?? 0);
        }
        
        // Calculate discounts
        $discount_70 = round($mrpSubtotal * 0.70, 2);
        $subtotal_after_70 = $mrpSubtotal - $discount_70;
        $discount_15 = round($subtotal_after_70 * 0.15, 2);
        $subtotal_after_15 = $subtotal_after_70 - $discount_15;
        $packing_charge = round($subtotal_after_15 * 0.05, 2);
        $final_total = round($subtotal_after_15 + $packing_charge, 2);
        
        // Handle coupon discount
        $couponDiscount = 0;
        if ($this->editingOrderId) {
            $order = Order::find($this->editingOrderId);
            if ($order && $order->coupon_code) {
                $coupon = \App\Models\Coupon::whereRaw('LOWER(code) = ?', [strtolower($order->coupon_code)])->first();
                if ($coupon && $coupon->isValid() && $final_total >= $coupon->minimum_order_amount) {
                    $couponDiscount = $coupon->calculateDiscount($final_total);
                    $final_total -= $couponDiscount;
                }
            }
        }

        // Calculate GST if checked
        $gstAmount = 0;
        if ($this->editHasGst) {
            $gstAmount = round($final_total * 0.18, 2);
            $final_total += $gstAmount;
        }
        
        return [
            'subtotal' => $mrpSubtotal,
            'discount_70_percent' => $discount_70,
            'amount_after_70_discount' => $subtotal_after_70,
            'special_discount_15_percent' => $discount_15,
            'amount_after_15_discount' => $subtotal_after_15,
            'packing_charge_5_percent' => $packing_charge,
            'coupon_discount' => $couponDiscount,
            'gst_amount' => $gstAmount,
            'total' => $final_total,
        ];
    }

    public function saveOrder()
    {
        try {
            $this->validate();

            $order = Order::find($this->editingOrderId);
            if (!$order) {
                session()->flash('error', 'Order not found.');
                return;
            }

            // Store old values for logging
            $oldStatus = $order->status;

            // Once status is "Confirmed", do not allow it to change back to "Pending"
            if (in_array($oldStatus, ['confirmed', 'dispatched', 'completed']) && $this->editStatus === 'pending') {
                $this->addError('editStatus', 'Status cannot be reverted to Pending once Confirmed, Dispatched or Completed.');
                return;
            }

            $oldPaymentStatus = $order->payment_status;
            $oldNotes = $order->notes;
            $oldReceiveAmount = $order->receive_amount;

            // Recalculate totals
            $totals = $this->recalculateTotals();

            // Update order items in DB
            \App\Models\OrderItem::where('order_id', $order->id)->delete();
            $newItemsJson = [];
            foreach ($this->editingOrderItems as $item) {
                $qty = (int)($item['quantity'] ?? 0);
                if ($qty > 0) {
                    $originalPrice = (float)$item['original_price'];
                    $rate = (float)$item['price'];
                    
                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'stock_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'content' => '',
                        'rate' => $rate,
                        'quantity' => $qty,
                        'price' => $rate,
                        'subtotal' => $originalPrice * $qty,
                        'total' => $rate * $qty
                    ]);

                    $newItemsJson[] = [
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'content' => '',
                        'rate' => $rate,
                        'original_price' => $originalPrice,
                        'discount_percentage' => $item['discount_percentage'],
                        'special_discount_percentage' => $item['special_discount_percentage'],
                        'quantity' => $qty,
                        'total' => $rate * $qty
                    ];
                }
            }

            $provider = ($this->editDeliveryType === 'delivery') ? $this->editTransportProvider : '';
            $details = ($this->editDeliveryType === 'delivery') ? $this->editTransportDetails : '';

            // Update the order
            $updateData = [
                'status' => $this->editStatus,
                'payment_status' => $this->editPaymentStatus,
                'notes' => $this->editNotes,
                'receive_amount' => $this->editReceiveAmount,
                'customer_name' => $this->editCustomerName,
                'customer_mobile' => $this->editCustomerMobile,
                'customer_email' => $this->editCustomerEmail,
                'customer_state' => $this->editCustomerState,
                'customer_district' => $this->editCustomerDistrict,
                'customer_city' => $this->editCustomerCity,
                'delivery_point' => $this->editDeliveryPoint,
                'pin_code' => $this->editPinCode,
                'items_json' => $newItemsJson,
                'subtotal' => $totals['subtotal'],
                'discount_70_percent' => $totals['discount_70_percent'],
                'amount_after_70_discount' => $totals['amount_after_70_discount'],
                'special_discount_15_percent' => $totals['special_discount_15_percent'],
                'amount_after_15_discount' => $totals['amount_after_15_discount'],
                'packing_charge_5_percent' => $totals['packing_charge_5_percent'],
                'coupon_discount' => $totals['coupon_discount'],
                'has_gst' => $this->editHasGst,
                'gst_amount' => $totals['gst_amount'],
                'delivery_type' => $this->editDeliveryType,
                'transport_provider' => $provider,
                'transport_details' => $details,
                'total' => $totals['total'],
                'total_amount' => $totals['total'],
                'final_amount' => $totals['total'],
            ];

            $order->update($updateData);

            // Log status changes
            if ($oldStatus !== $this->editStatus) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => in_array($this->editStatus, ['pending','confirmed','dispatched','completed','cancelled']) ? $this->editStatus : 'updated',
                    'previous_status' => $oldStatus,
                    'changed_by' => auth()->id(),
                    'notes' => "Status changed from {$oldStatus} to {$this->editStatus}",
                    'payment_status' => null,
                ]);
            }

            if ($oldPaymentStatus !== $this->editPaymentStatus) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => 'updated',
                    'previous_status' => $oldPaymentStatus,
                    'changed_by' => auth()->id(),
                    'notes' => "Payment status changed from {$oldPaymentStatus} to {$this->editPaymentStatus}",
                    'payment_status' => $this->editPaymentStatus,
                ]);
            }

            if ($oldReceiveAmount != $this->editReceiveAmount) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => 'updated',
                    'previous_status' => 'receive_amount_updated',
                    'changed_by' => auth()->id(),
                    'notes' => "Receive amount changed from {$oldReceiveAmount} to {$this->editReceiveAmount}",
                    'payment_status' => null,
                ]);
            }

            if ($oldNotes !== $this->editNotes) {
                OrderLog::create([
                    'order_id' => $order->id,
                    'status' => 'updated',
                    'previous_status' => 'notes_updated',
                    'changed_by' => auth()->id(),
                    'notes' => "Notes updated: " . ($this->editNotes ?: 'Notes cleared'),
                    'payment_status' => null,
                ]);
            }

            $this->closeEditModal();
            session()->flash('success', 'Order updated successfully!');
            
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            \Log::error('Error saving order: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function editReceiveAmount($orderId, $currentAmount)
    {
        $this->editingReceiveAmountId = $orderId;
        $this->receiveAmountInput = $currentAmount;
    }

    public function saveReceiveAmount($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->receive_amount = $this->receiveAmountInput;
            $order->save();
            session()->flash('success', 'Receive amount updated.');
        }
        $this->editingReceiveAmountId = null;
        $this->receiveAmountInput = null;
    }

    public function cancelEditReceiveAmount()
    {
        $this->editingReceiveAmountId = null;
        $this->receiveAmountInput = null;
    }

    public function exportOrders()
    {
        $orders = $this->getFilteredOrders();
        
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $csvData = [];
        
        // CSV headers
        $csvData[] = [
            'Order ID', 'Customer', 'Phone', 'Status', 'Payment Status', 
            'Total Amount', 'Receive Amount', 'Created Date', 'Items',
            'Name', 'Mobile', 'Email', 'State', 'District', 'City', 'Delivery Point', 'Pin Code', 'Coupon', 'Verify Code'
        ];

        foreach ($orders as $order) {
            $items = collect($order->items)->map(function($item) {
                if (is_array($item)) {
                    $item = (object)$item;
                }
                return $item->product_name . ' (x' . $item->quantity . ')';
            })->implode(', ');

            $csvData[] = [
                $order->id,
                $order->user->name ?? $order->customer_name ?? 'Guest',
                $order->user->phone ?? $order->customer_mobile ?? '-',
                $order->status,
                $order->payment_status,
                number_format($order->total, 2),
                $order->receive_amount !== null ? number_format($order->receive_amount, 2) : '',
                $order->created_at->format('Y-m-d H:i:s'),
                $items,
                $order->customer_name,
                $order->customer_mobile,
                $order->customer_email,
                $order->customer_state,
                $order->customer_district,
                $order->customer_city,
                $order->delivery_point,
                $order->pin_code,
                $order->coupon_code,
                $order->verify_code,
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

    public function sendWhatsAppSummary($orderId)
    {
        $order = Order::with(['user', 'items'])->find($orderId);
        
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $items = $order->items->map(function($item) {
            return "• {$item->product_name} - Qty: {$item->quantity} - ₹" . number_format($item->price, 2);
        })->implode("\n");

        $message = "🛒 *Order Summary*\n\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Customer: {$order->user->name}\n";
        $message .= "Phone: {$order->user->phone}\n";
        $message .= "Status: {$order->status}\n";
        $message .= "Payment: {$order->payment_status}\n\n";
        $message .= "*Items:*\n{$items}\n\n";
        $message .= "Total Amount: ₹" . number_format($order->total, 2) . "\n";
        $message .= "Order Date: " . $order->created_at->format('d/m/Y H:i');

        $whatsappLink = "https://wa.me/{$order->user->phone}?text=" . urlencode($message);
        
        session()->flash('whatsapp_link', $whatsappLink);
        session()->flash('success', 'WhatsApp link generated successfully!');
    }

    public function downloadOrderPdf($orderId)
    {
        $order = Order::with(['user', 'items'])->find($orderId);
        
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        // Redirect to the download route
        return redirect()->route('admin.orders.download_pdf', $orderId);
    }

    public function downloadOrderInvoicePdf($orderId)
    {
        $order = Order::with(['user', 'items'])->find($orderId);
        
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        // Redirect to the invoice download route
        return redirect()->route('admin.orders.download_invoice_pdf', $orderId);
    }

    public function downloadAllOrdersInvoicePdf()
    {
        $orders = $this->getFilteredOrders();
        
        if ($orders->isEmpty()) {
            session()->flash('error', 'No orders found to download.');
            return;
        }

        // Redirect to the bulk invoice download route
        return redirect()->route('admin.orders.download_all_invoice_pdf');
    }

    private function getFilteredOrdersQuery($ignoreStatusFilter = false)
    {
        $query = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->selected_year)) {
            $query->whereYear('created_at', $this->selected_year);
        }

        if (!empty(trim($this->search))) {
            $searchTerm = trim($this->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_mobile', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_district', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_state', 'like', '%' . $searchTerm . '%')
                  ->orWhere('delivery_point', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if (!$ignoreStatusFilter && !empty($this->status_filter)) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($this->status_filter)]);
        }

        if (!empty($this->payment_filter)) {
            $query->whereRaw('LOWER(payment_status) = ?', [strtolower($this->payment_filter)]);
        }

        if (!empty($this->date_from)) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if (!empty($this->date_to)) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        if (!empty($this->delivery_type_filter)) {
            if ($this->delivery_type_filter === 'none') {
                $query->where(function($q) {
                    $q->whereNull('delivery_type')
                      ->orWhere('delivery_type', 'none')
                      ->orWhere('delivery_type', '');
                });
            } else {
                $query->whereRaw('LOWER(delivery_type) = ?', [strtolower($this->delivery_type_filter)]);
            }
        }

        return $query;
    }

    private function getFilteredOrders()
    {
        return $this->getFilteredOrdersQuery()->get();
    }

    public function render()
    {
        $orders = $this->getFilteredOrdersQuery()->paginate(20);
        
        \Log::info('Orders component rendering', [
            'showEditModal' => $this->showEditModal,
            'editingOrderId' => $this->editingOrderId,
            'ordersCount' => $orders->count(),
            'search' => $this->search,
            'status_filter' => $this->status_filter,
            'payment_filter' => $this->payment_filter,
            'selected_year' => $this->selected_year,
            'delivery_type_filter' => $this->delivery_type_filter,
        ]);
        
        $baseStatsQuery = $this->getFilteredOrdersQuery(true);

        // Pre-calculate full active stocks serial mapping to match price list catalog serials
        $allActiveCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
        $allActiveStocks = \App\Models\Stock::where('is_active', true)->get()->groupBy('category');
        $catalogSnoMap = [];
        $snoCounter = 0;
        foreach ($allActiveCats as $cat) {
            $catStocks = $allActiveStocks->get($cat->name) ?? $allActiveStocks->get($cat->id) ?? collect();
            foreach ($catStocks->sortBy('order_within_category') as $stockItem) {
                $snoCounter++;
                $catalogSnoMap[$stockItem->id] = $snoCounter;
            }
        }
        
        return view('livewire.admin.orders', [
            'orders' => $orders,
            'editingOrder' => $this->editingOrderId ? Order::with(['user', 'items'])->find($this->editingOrderId) : null,
            'totalOrders' => (clone $baseStatsQuery)->when(!empty($this->status_filter), function($q) {
                $q->whereRaw('LOWER(status) = ?', [strtolower($this->status_filter)]);
            })->count(),
            'pendingOrders' => (clone $baseStatsQuery)->whereRaw('LOWER(status) = ?', ['pending'])->count(),
            'confirmedOrders' => (clone $baseStatsQuery)->whereRaw('LOWER(status) = ?', ['confirmed'])->count(),
            'dispatchedOrders' => (clone $baseStatsQuery)->whereRaw('LOWER(status) = ?', ['dispatched'])->count(),
            'completedOrders' => (clone $baseStatsQuery)->whereRaw('LOWER(status) = ?', ['completed'])->count(),
            'catalogSnoMap' => $catalogSnoMap,
        ]);
    }
}