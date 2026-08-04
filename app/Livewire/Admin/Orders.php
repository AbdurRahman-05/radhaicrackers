<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Stock;

class Orders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filters (both snake_case and camelCase for template compatibility)
    public $search = '';
    public $status_filter = 'all';
    public $statusFilter = 'all';
    public $payment_filter = 'all';
    public $paymentFilter = 'all';
    public $date_from = '';
    public $dateFrom = '';
    public $date_to = '';
    public $dateTo = '';
    public $selected_year = '2026';
    public $selectedYear = '2026';
    public $delivery_type_filter = 'all';
    public $deliveryTypeFilter = 'all';

    // Inline receive amount editing
    public $editingReceiveAmountId = null;
    public $receiveAmountInput = '';

    // Single Window Split-View Edit Modal
    public $showEditModal = false;
    public $editingOrderId = null;
    public $editingOrder = null;
    public $initialStatus = null;
    public $editStatus = 'pending';
    public $editPaymentStatus = 'pending';
    public $editPaidAt = '';
    public $editNotes = '';
    public $editReceiveAmount = '';
    public $editCustomerName = '';
    public $editCustomerMobile = '';
    public $editCustomerEmail = '';
    public $editCustomerState = '';
    public $editCustomerDistrict = '';
    public $editCustomerCity = '';
    public $editDeliveryPoint = '';
    public $editPinCode = '';
    public $editHasGst = false;
    public $editDeliveryType = 'none';
    public $editTransportProvider = '';
    public $editTransportDetails = '';

    // Items being edited in modal
    public $editItems = [];
    public $editingOrderItems = [];
    public $newProductId = '';
    public $newItemSearch = '';
    public $searchItemsList = [];
    public $newItemQty = 1;

    // Reset pagination and sync filter values
    public function updatedSearch() { $this->resetPage(); }
    
    public function updatedStatusFilter($val) { $this->status_filter = $val; $this->resetPage(); }
    public function updatedStatus_filter($val) { $this->statusFilter = $val ?: 'all'; $this->resetPage(); }

    public function updatedPaymentFilter($val) { $this->payment_filter = $val; $this->resetPage(); }
    public function updatedPayment_filter($val) { $this->paymentFilter = $val ?: 'all'; $this->resetPage(); }

    public function updatedDeliveryTypeFilter($val) { $this->delivery_type_filter = $val; $this->resetPage(); }
    public function updatedDelivery_type_filter($val) { $this->deliveryTypeFilter = $val ?: 'all'; $this->resetPage(); }

    public function updatedDateFrom($val) { $this->date_from = $val; $this->resetPage(); }
    public function updatedDate_from($val) { $this->dateFrom = $val; $this->resetPage(); }

    public function updatedDateTo($val) { $this->date_to = $val; $this->resetPage(); }
    public function updatedDate_to($val) { $this->dateTo = $val; $this->resetPage(); }

    public function updatedSelectedYear($val) { $this->selected_year = $val; $this->resetPage(); }
    public function updatedSelected_year($val) { $this->selectedYear = $val; $this->resetPage(); }

    public function updatedNewItemSearch()
    {
        $this->fetchSearchResults();
    }

    public function fetchSearchResults()
    {
        if (trim($this->newItemSearch) !== '') {
            $this->searchItemsList = Stock::where('is_active', true)
                ->where('item_name', 'like', '%' . trim($this->newItemSearch) . '%')
                ->take(15)
                ->get()
                ->toArray();
        } else {
            $this->searchItemsList = Stock::where('is_active', true)
                ->orderBy('item_name')
                ->take(15)
                ->get()
                ->toArray();
        }
    }

    public function selectNewItem($stockId)
    {
        $stock = Stock::find($stockId);
        if ($stock) {
            $this->newProductId = $stock->id;
            $this->newItemSearch = $stock->item_name;
            $this->searchItemsList = [];
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->status_filter = 'all';
        $this->statusFilter = 'all';
        $this->payment_filter = 'all';
        $this->paymentFilter = 'all';
        $this->date_from = '';
        $this->dateFrom = '';
        $this->date_to = '';
        $this->dateTo = '';
        $this->selected_year = '';
        $this->selectedYear = '';
        $this->delivery_type_filter = 'all';
        $this->deliveryTypeFilter = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $baseQuery = $this->getBaseFilterQuery();

        $totalOrders = (clone $baseQuery)->count();
        $pendingOrders = (clone $baseQuery)->where('status', 'pending')->count();
        $confirmedOrders = (clone $baseQuery)->where('status', 'confirmed')->count();
        $dispatchedOrders = (clone $baseQuery)->where('status', 'dispatched')->count();
        $completedOrders = (clone $baseQuery)->where('status', 'completed')->count();

        $orders = $this->getFilteredOrders();

        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // All active stocks for adding new items in modal
        $allStocks = Stock::where('is_active', true)->orderBy('item_name')->get();

        return view('livewire.admin.orders', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'confirmedOrders' => $confirmedOrders,
            'dispatchedOrders' => $dispatchedOrders,
            'completedOrders' => $completedOrders,
            'availableYears' => $availableYears,
            'available_years' => $availableYears,
            'allStocks' => $allStocks,
        ])->layout('layouts.admin');
    }

    public function getBaseFilterQuery()
    {
        $query = Order::query();

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_city', 'like', "%{$search}%")
                  ->orWhere('customer_district', 'like', "%{$search}%")
                  ->orWhere('customer_state', 'like', "%{$search}%")
                  ->orWhere('delivery_point', 'like', "%{$search}%");
            });
        }

        $payment = !empty($this->payment_filter) && $this->payment_filter !== 'all' ? $this->payment_filter : ($this->paymentFilter !== 'all' ? $this->paymentFilter : null);
        if ($payment) {
            $query->where('payment_status', $payment);
        }

        $deliveryType = !empty($this->delivery_type_filter) && $this->delivery_type_filter !== 'all' ? $this->delivery_type_filter : ($this->deliveryTypeFilter !== 'all' ? $this->deliveryTypeFilter : null);
        if ($deliveryType) {
            $query->where('delivery_type', $deliveryType);
        }

        $dateFrom = !empty($this->date_from) ? $this->date_from : $this->dateFrom;
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        $dateTo = !empty($this->date_to) ? $this->date_to : $this->dateTo;
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $selectedYear = !empty($this->selected_year) ? $this->selected_year : $this->selectedYear;
        if (!empty($selectedYear)) {
            $query->whereYear('created_at', $selectedYear);
        }

        return $query;
    }

    public function getFilteredOrders()
    {
        $query = $this->getBaseFilterQuery()->with(['user']);

        $status = !empty($this->status_filter) && $this->status_filter !== 'all' ? $this->status_filter : ($this->statusFilter !== 'all' ? $this->statusFilter : null);
        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function openEditModal($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $this->editingOrderId = $orderId;
        $this->editingOrder = $order;
        $this->initialStatus = strtolower($order->status);
        $this->editStatus = strtolower($order->status);
        $this->editPaymentStatus = strtolower($order->payment_status);
        $this->editPaidAt = $order->paid_at ? $order->paid_at->format('Y-m-d\TH:i') : '';
        $this->editNotes = $order->notes ?? '';
        $this->editReceiveAmount = $order->receive_amount ?? '';
        $this->editCustomerName = $order->customer_name ?? '';
        $this->editCustomerMobile = $order->customer_mobile ?? '';
        $this->editCustomerEmail = $order->customer_email ?? '';
        $this->editCustomerState = $order->customer_state ?? '';
        $this->editCustomerDistrict = $order->customer_district ?? '';
        $this->editCustomerCity = $order->customer_city ?? '';
        $this->editDeliveryPoint = $order->delivery_point ?? '';
        $this->editPinCode = $order->pin_code ?? '';
        $this->editHasGst = (bool)$order->has_gst;
        $this->editDeliveryType = $order->delivery_type ?? 'none';
        $this->editTransportProvider = $order->transport_provider ?? '';
        $this->editTransportDetails = $order->transport_details ?? '';

        // Format items for modal editing safely whether array or collection
        $this->editItems = [];
        $rawItems = $order->items_json ?: (is_array($order->items) ? $order->items : []);
        if ($rawItems && (is_array($rawItems) || is_object($rawItems))) {
            foreach ($rawItems as $item) {
                $productId = is_array($item) ? ($item['product_id'] ?? null) : ($item->product_id ?? null);
                $productName = is_array($item) ? ($item['product_name'] ?? null) : ($item->product_name ?? null);
                $qty = (int)(is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1));
                $price = (float)(is_array($item) ? ($item['rate'] ?? $item['price'] ?? 0) : ($item->price ?? 0));
                $id = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);

                $stock = $productId ? Stock::find($productId) : null;
                $origPrice = (float)($stock->original_price ?? ($price > 0 ? $price / 0.255 : 0));

                $this->editItems[] = [
                    'id' => $id,
                    'product_id' => $productId,
                    'product_name' => $productName ?: ($stock->item_name ?? 'Product #' . $productId),
                    'rate' => $price,
                    'price' => $price,
                    'original_price' => $origPrice,
                    'discount_percentage' => (float)($stock->discount_percentage ?? 70),
                    'special_discount_percentage' => (float)($stock->special_discount_percentage ?? 15),
                    'quantity' => $qty,
                    'total' => $price * $qty,
                ];
            }
        }
        $this->editingOrderItems = &$this->editItems;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingOrder = null;
        $this->editingOrderId = null;
        $this->editItems = [];
        $this->editingOrderItems = [];
        $this->newProductId = '';
        $this->newItemSearch = '';
        $this->searchItemsList = [];
        $this->newItemQty = 1;
    }

    public function updateItemQty($index, $newQty)
    {
        if (isset($this->editItems[$index])) {
            $qty = max(1, (int)$newQty);
            $this->editItems[$index]['quantity'] = $qty;
            $this->editItems[$index]['total'] = $this->editItems[$index]['rate'] * $qty;
        }
    }

    public function removeItem($index)
    {
        if (isset($this->editItems[$index])) {
            array_splice($this->editItems, $index, 1);
        }
    }

    public function addNewItem()
    {
        if (empty($this->newProductId) && !empty($this->newItemSearch)) {
            $stock = Stock::where('is_active', true)
                ->where('item_name', 'like', '%' . trim($this->newItemSearch) . '%')
                ->first();
            if ($stock) {
                $this->newProductId = $stock->id;
            }
        }

        if (empty($this->newProductId)) return;

        $stock = Stock::find($this->newProductId);
        if (!$stock) return;

        // Check if already in editItems
        foreach ($this->editItems as $idx => $item) {
            if ($item['product_id'] == $stock->id) {
                $this->editItems[$idx]['quantity'] += (int)$this->newItemQty;
                $this->editItems[$idx]['total'] = $this->editItems[$idx]['rate'] * $this->editItems[$idx]['quantity'];
                $this->newProductId = '';
                $this->newItemSearch = '';
                $this->searchItemsList = [];
                $this->newItemQty = 1;
                return;
            }
        }

        $rate = (float)($stock->price ?? 0);
        $originalPrice = (float)($stock->original_price ?? $rate);
        $qty = max(1, (int)$this->newItemQty);

        $this->editItems[] = [
            'id' => null,
            'product_id' => $stock->id,
            'product_name' => $stock->item_name,
            'rate' => $rate,
            'price' => $rate,
            'original_price' => $originalPrice,
            'discount_percentage' => (float)($stock->discount_percentage ?? 70),
            'special_discount_percentage' => (float)($stock->special_discount_percentage ?? 15),
            'quantity' => $qty,
            'total' => $rate * $qty,
        ];

        $this->newProductId = '';
        $this->newItemSearch = '';
        $this->searchItemsList = [];
        $this->newItemQty = 1;
    }

    // Recalculate totals in real time for modal
    public function recalculateTotals()
    {
        $subtotal = 0;
        $discount70 = 0;
        $discount15 = 0;

        foreach ($this->editItems as $item) {
            $origPrice = (float)($item['original_price'] ?? ($item['rate'] / 0.255));
            $qty = (int)$item['quantity'];
            $lineSubtotal = $origPrice * $qty;

            $subtotal += $lineSubtotal;
            $lineDisc70 = round($lineSubtotal * 0.70, 2);
            $discount70 += $lineDisc70;

            $after70 = $lineSubtotal - $lineDisc70;
            $lineDisc15 = round($after70 * 0.15, 2);
            $discount15 += $lineDisc15;
        }

        $after15 = $subtotal - $discount70 - $discount15;
        $packingCharge = round($after15 * 0.05, 2);
        
        $couponDiscount = 0;
        if ($this->editingOrder && $this->editingOrder->coupon_discount) {
            $couponDiscount = (float)$this->editingOrder->coupon_discount;
        }

        $taxableAmount = max(0, $after15 + $packingCharge - $couponDiscount);
        $gstAmount = $this->editHasGst ? round($taxableAmount * 0.18, 2) : 0;
        $finalTotal = round($taxableAmount + $gstAmount);

        return [
            'subtotal' => $subtotal,
            'discount_70_percent' => $discount70,
            'amount_after_70_discount' => $subtotal - $discount70,
            'special_discount_15_percent' => $discount15,
            'amount_after_15_discount' => $after15,
            'packing_charge_5_percent' => $packingCharge,
            'coupon_discount' => $couponDiscount,
            'gst_amount' => $gstAmount,
            'total' => $finalTotal,
        ];
    }

    public function saveOrder()
    {
        if (!$this->editingOrderId) return;

        $order = Order::find($this->editingOrderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $this->validate([
            'editStatus' => 'required|in:pending,confirmed,dispatched,completed,cancelled',
            'editPaymentStatus' => 'required|in:pending,paid,failed',
            'editCustomerName' => 'required|string|max:255',
            'editCustomerMobile' => 'required|string|max:20',
        ]);

        try {
            $oldStatus = strtolower($order->status);
            $oldPaymentStatus = strtolower($order->payment_status);
            $oldReceiveAmount = $order->receive_amount;
            $oldNotes = $order->notes;

            $totals = $this->recalculateTotals();

            $newItemsJson = [];
            foreach ($this->editItems as $item) {
                $origPrice = (float)($item['original_price'] ?? ($item['rate'] / 0.255));
                $qty = (int)$item['quantity'];
                $rate = (float)$item['rate'];

                $newItemsJson[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'content' => '',
                    'rate' => $rate,
                    'original_price' => $origPrice,
                    'discount_percentage' => $item['discount_percentage'],
                    'special_discount_percentage' => $item['special_discount_percentage'],
                    'quantity' => $qty,
                    'total' => $rate * $qty
                ];
            }

            $provider = ($this->editDeliveryType === 'delivery') ? $this->editTransportProvider : '';
            $details = ($this->editDeliveryType === 'delivery') ? $this->editTransportDetails : '';

            $paidAtValue = null;
            if ($this->editPaymentStatus === 'paid') {
                if (!empty($this->editPaidAt)) {
                    $paidAtValue = \Carbon\Carbon::parse($this->editPaidAt);
                } else {
                    $paidAtValue = $order->paid_at ? $order->paid_at : \Carbon\Carbon::now();
                }
            }

            // Update the order
            $updateData = [
                'status' => $this->editStatus,
                'payment_status' => $this->editPaymentStatus,
                'paid_at' => $paidAtValue,
                'notes' => $this->editNotes,
                'receive_amount' => (is_numeric($this->editReceiveAmount) && $this->editReceiveAmount !== '') ? (float)$this->editReceiveAmount : 0,
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

            // Recalculate product stock ordered_counts dynamically based on confirmed/edited order items
            Stock::recalculateOrderedCounts();

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

            // Keep updated order visible in list by resetting filters to 'all'
            $this->clearFilters();

            // Automatically send WhatsApp notification if payment status CHANGED to paid or confirmed
            if (in_array($this->editPaymentStatus, ['paid', 'confirmed']) && $oldPaymentStatus !== $this->editPaymentStatus) {
                $this->sendWhatsAppPaidBill($order->id);
            }

            // Automatically send WhatsApp notification if order status CHANGED to dispatched
            if ($this->editStatus === 'dispatched' && $oldStatus !== $this->editStatus) {
                $this->sendWhatsAppDispatched($order->id);
            }

            $this->closeEditModal();
            session()->flash('success', "Order #{$order->id} updated successfully!");
            
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            \Log::error('Error saving order: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    private function getFormattedItemsList($order)
    {
        $rawItems = $order->items_json ?: (is_array($order->items) ? $order->items : []);
        $itemsList = [];
        if ($rawItems && (is_array($rawItems) || is_object($rawItems))) {
            foreach ($rawItems as $item) {
                $pName = is_array($item) ? ($item['product_name'] ?? '') : ($item->product_name ?? '');
                $pQty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
                $pPrice = (float)(is_array($item) ? ($item['rate'] ?? $item['price'] ?? 0) : ($item->price ?? 0));
                if ($pName) {
                    $itemsList[] = "• {$pName} - Qty: {$pQty} - ₹" . number_format($pPrice, 2);
                }
            }
        }
        return implode("\n", $itemsList);
    }

    public function sendWhatsAppPaidBill($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $phone = $this->editCustomerMobile ?: ($order->customer_mobile ?: ($order->user->phone ?? ''));
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        if (!$phone) {
            session()->flash('error', 'Customer mobile number not available.');
            return;
        }

        $customerName = $this->editCustomerName ?: $order->customer_name;
        $orderValue = '₹' . number_format($order->total_amount ?: $order->total, 2);
        $invoiceUrl = route('user.orders.invoice_pdf', $order->id);

        try {
            $smsService = new \App\Services\SMSService();
            $res = $smsService->sendWhatsApp($phone, '', 'payment_paid', [
                'customer_name' => $customerName,
                'order_id' => (string)$order->id,
                'order_value' => $orderValue,
                'invoice_url' => $invoiceUrl,
            ]);

            \Log::info("WhatsApp Payment Paid notification triggered for Order #{$order->id} to {$phone}");
            session()->flash('success', "WhatsApp Paid Invoice notification sent automatically to {$customerName} (+91{$phone})!");
        } catch (\Exception $e) {
            \Log::error("WhatsApp Payment Paid error for Order #{$order->id}: " . $e->getMessage());
            session()->flash('error', "Error sending WhatsApp notification: " . $e->getMessage());
        }
    }

    public function sendWhatsAppDispatched($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $phone = $this->editCustomerMobile ?: ($order->customer_mobile ?: ($order->user->phone ?? ''));
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        if (!$phone) {
            session()->flash('error', 'Customer mobile number not available.');
            return;
        }

        $customerName = $this->editCustomerName ?: $order->customer_name;
        $provider = $this->editTransportProvider ?: ($order->transport_provider ?: 'Lorry Transport');
        $details = $this->editTransportDetails ?: ($order->transport_details ?: 'Assigned');
        $deliveryPoint = $this->editDeliveryPoint ?: ($order->delivery_point ?: ($this->editCustomerCity ?: $order->customer_city));

        try {
            $smsService = new \App\Services\SMSService();
            $res = $smsService->sendWhatsApp($phone, '', 'order_dispatched', [
                'customer_name' => $customerName,
                'order_id' => (string)$order->id,
                'order_value' => '₹' . number_format($order->total_amount ?: ($order->total ?: 0), 2),
                'transport_provider' => $provider,
                'transport_details' => $details,
                'delivery_point' => $deliveryPoint,
                'delivery_type' => $this->editDeliveryType ?: $order->delivery_type,
            ]);

            \Log::info("WhatsApp Dispatched notification triggered for Order #{$order->id} to {$phone}");
            session()->flash('success', "WhatsApp Out for Delivery notification sent automatically to {$customerName} (+91{$phone})!");
        } catch (\Exception $e) {
            \Log::error("WhatsApp Dispatched error for Order #{$order->id}: " . $e->getMessage());
            session()->flash('error', "Error sending WhatsApp notification: " . $e->getMessage());
        }
    }

    public $confirmingOrderDeletion = false;
    public $orderIdToDelete = null;

    public function confirmDeleteOrder($orderId)
    {
        $this->orderIdToDelete = $orderId;
        $this->confirmingOrderDeletion = true;
    }

    public function cancelDeleteOrder()
    {
        $this->confirmingOrderDeletion = false;
        $this->orderIdToDelete = null;
    }

    public function deleteOrder()
    {
        if (!$this->orderIdToDelete) return;

        $order = Order::find($this->orderIdToDelete);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            $this->cancelDeleteOrder();
            return;
        }

        try {
            $orderId = $order->id;
            try { $order->logs()->delete(); } catch (\Exception $e) {}
            try { 
                if (method_exists($order, 'items') && $order->items() instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                    $order->items()->delete();
                }
            } catch (\Exception $e) {}
            
            $order->delete();

            // Recalculate product stock ordered_counts after deleting order
            Stock::recalculateOrderedCounts();

            $this->cancelDeleteOrder();
            session()->flash('success', "Order #{$orderId} deleted successfully.");
        } catch (\Exception $e) {
            \Log::error('Error deleting order: ' . $e->getMessage());
            session()->flash('error', 'Error deleting order: ' . $e->getMessage());
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
        $this->receiveAmountInput = '';
    }

    public function cancelEditReceiveAmount()
    {
        $this->editingReceiveAmountId = null;
        $this->receiveAmountInput = '';
    }
}
