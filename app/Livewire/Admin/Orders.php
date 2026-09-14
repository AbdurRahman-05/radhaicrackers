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
    public $selected_year = '';
    public $selectedYear = '';
    public $delivery_type_filter = 'all';
    public $deliveryTypeFilter = 'all';

    public function mount()
    {
        if (empty($this->selected_year) && empty($this->selectedYear)) {
            $currentYear = (string)date('Y');
            $this->selected_year = $currentYear;
            $this->selectedYear = $currentYear;
        }
    }

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
    public $showSearchDropdown = false;

    // Modal notification messages (using component properties instead of session flash
    // because session flash is unreliable with Livewire AJAX requests on production)
    public $modalMessage = '';
    public $modalMessageType = '';

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

    public function updatedSelectedYear($val) { $this->selected_year = $val; $this->selectedYear = $val; $this->resetPage(); }
    public function updatedSelected_year($val) { $this->selected_year = $val; $this->selectedYear = $val; $this->resetPage(); }

    public function updatedNewItemSearch()
    {
        $term = trim($this->newItemSearch);
        if ($term !== '') {
            $stocks = Stock::select(['id', 'item_name', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'quantity', 'ordered_count', 'category'])
                ->where('is_active', true)
                ->where(function($q) use ($term) {
                    $q->where('item_name', 'like', '%' . $term . '%')
                      ->orWhere('id', $term);
                })
                ->take(20)
                ->get();

            $this->searchItemsList = $stocks->map(function($s) {
                return [
                    'id' => (int)$s->id,
                    'item_name' => (string)$s->item_name,
                    'price' => (float)$s->price,
                    'original_price' => (float)($s->original_price ?: 0),
                    'discount_percentage' => (float)($s->discount_percentage ?? 70),
                    'special_discount_percentage' => (float)($s->special_discount_percentage ?? 15),
                    'quantity' => (int)$s->quantity,
                    'ordered_count' => (int)$s->ordered_count,
                    'category' => (string)$s->category,
                ];
            })->all();
            $this->showSearchDropdown = true;
        } else {
            $this->searchItemsList = [];
            $this->showSearchDropdown = false;
        }
    }

    public function fetchSearchResults()
    {
        $term = trim($this->newItemSearch);
        $query = Stock::select(['id', 'item_name', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'quantity', 'ordered_count', 'category'])
            ->where('is_active', true);

        if ($term !== '') {
            $query->where(function($q) use ($term) {
                $q->where('item_name', 'like', '%' . $term . '%')
                  ->orWhere('id', $term);
            });
        } else {
            $query->orderBy('item_name');
        }

        $stocks = $query->take(20)->get();

        $this->searchItemsList = $stocks->map(function($s) {
            return [
                'id' => (int)$s->id,
                'item_name' => (string)$s->item_name,
                'price' => (float)$s->price,
                'original_price' => (float)($s->original_price ?: 0),
                'discount_percentage' => (float)($s->discount_percentage ?? 70),
                'special_discount_percentage' => (float)($s->special_discount_percentage ?? 15),
                'quantity' => (int)$s->quantity,
                'ordered_count' => (int)$s->ordered_count,
                'category' => (string)$s->category,
            ];
        })->all();

        $this->showSearchDropdown = true;
    }

    public function selectNewItem($stockId)
    {
        // Use select() to avoid triggering image_url accessor file I/O
        $stock = Stock::select(['id', 'item_name', 'price'])->find($stockId);
        if ($stock) {
            $this->newProductId = $stock->id;
            $this->newItemSearch = $stock->item_name;
            $this->searchItemsList = [];
            $this->showSearchDropdown = false;
            $this->modalMessage = '';
            $this->modalMessageType = '';
        }
    }

    public function closeSearchDropdown()
    {
        $this->showSearchDropdown = false;
        $this->searchItemsList = [];
    }

    public function updatedEditItems($value, $key)
    {
        if (str_contains($key, 'quantity')) {
            $parts = explode('.', $key);
            $index = (int)$parts[0];
            if (isset($this->editItems[$index])) {
                $qty = max(1, (int)$value);
                $this->editItems[$index]['quantity'] = $qty;
                $rate = (float)($this->editItems[$index]['rate'] ?? $this->editItems[$index]['price'] ?? 0);
                $this->editItems[$index]['total'] = $rate * $qty;
                $this->editingOrderItems = $this->editItems;
            }
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
        $currentYear = (string)date('Y');
        $this->selected_year = $currentYear;
        $this->selectedYear = $currentYear;
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


        // Today's Actions Breakdown
        $todayDate = \Carbon\Carbon::today();

        $todayOrders = Order::with('user')->whereDate('created_at', $todayDate)->latest()->get();
        $todayOrdersCount = $todayOrders->count();
        $todayOrdersRevenue = $todayOrders->sum(function($o) {
            return (float)($o->total_amount ?: ($o->total ?: 0));
        });

        $todayLogs = \App\Models\OrderLog::with(['order.user', 'order'])->whereDate('created_at', $todayDate)->latest()->get();

        $todayPayments = \App\Models\Payment::with(['order.user', 'order'])
            ->where(function($q) use ($todayDate) {
                $q->whereDate('created_at', $todayDate)
                  ->orWhereDate('verified_at', $todayDate);
            })
            ->latest()
            ->get();
        $todayPaymentsVerifiedCount = $todayPayments->where('status', 'verified')->count();
        $todayPaymentsVerifiedAmount = $todayPayments->where('status', 'verified')->sum('amount');

        $todayUsers = \App\Models\User::whereDate('created_at', $todayDate)->latest()->get();
        $todayUsersCount = $todayUsers->count();

        $todayGstBills = \App\Models\GstBill::whereDate('created_at', $todayDate)->latest()->get();
        $todayGstBillsCount = $todayGstBills->count();
        $todayGstBillsAmount = $todayGstBills->sum('grand_total');

        $todayTimeline = collect();

        foreach ($todayOrders as $orderItem) {
            $customerName = $orderItem->customer_name ?: ($orderItem->user->name ?? 'Guest Customer');
            $customerMobile = $orderItem->customer_mobile ?: ($orderItem->user->phone ?? '');
            $amount = '₹' . number_format($orderItem->total_amount ?: $orderItem->total, 2);
            
            $todayTimeline->push([
                'timestamp' => $orderItem->created_at,
                'time' => $orderItem->created_at->format('h:i A'),
                'type' => 'order_created',
                'badge' => '🛍️ New Order',
                'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'icon' => 'fas fa-shopping-cart text-emerald-600',
                'title' => "Order #{$orderItem->id} placed by {$customerName}",
                'subtitle' => "Mobile: " . ($customerMobile ?: 'N/A') . " • Total: {$amount}",
                'status_badge' => strtoupper($orderItem->status),
                'status_color' => $orderItem->status === 'completed' ? 'bg-green-500 text-white' : ($orderItem->status === 'confirmed' ? 'bg-blue-500 text-white' : ($orderItem->status === 'dispatched' ? 'bg-purple-500 text-white' : 'bg-yellow-500 text-white')),
                'link' => route('admin.orders', ['search' => $orderItem->id]),
            ]);
        }

        foreach ($todayLogs as $log) {
            $orderId = $log->order_id;
            $customerName = $log->order ? ($log->order->customer_name ?: ($log->order->user->name ?? 'Customer')) : 'Customer';
            
            $todayTimeline->push([
                'timestamp' => $log->created_at,
                'time' => $log->created_at->format('h:i A'),
                'type' => 'order_log',
                'badge' => '🔄 Status Update',
                'badge_color' => 'bg-blue-100 text-blue-800 border-blue-300',
                'icon' => 'fas fa-sync-alt text-blue-600',
                'title' => "Order #{$orderId} ({$customerName}) status changed",
                'subtitle' => $log->notes ?: "Status updated to " . ucfirst($log->status),
                'status_badge' => strtoupper($log->status ?: 'UPDATED'),
                'status_color' => 'bg-gray-700 text-white',
                'link' => route('admin.orders', ['search' => $orderId]),
            ]);
        }

        foreach ($todayPayments as $payment) {
            $orderId = $payment->order_id;
            $amt = '₹' . number_format($payment->amount, 2);
            $isVerified = $payment->status === 'verified';
            
            $todayTimeline->push([
                'timestamp' => $payment->verified_at ?: $payment->created_at,
                'time' => ($payment->verified_at ?: $payment->created_at)->format('h:i A'),
                'type' => 'payment',
                'badge' => $isVerified ? '✅ Payment Verified' : '💳 Payment Submitted',
                'badge_color' => $isVerified ? 'bg-green-100 text-green-800 border-green-300' : 'bg-amber-100 text-amber-800 border-amber-300',
                'icon' => $isVerified ? 'fas fa-check-circle text-green-600' : 'fas fa-credit-card text-amber-600',
                'title' => "Payment of {$amt} for Order #{$orderId}",
                'subtitle' => "UPI / Txn: " . ($payment->transaction_id ?: ($payment->upi_id ?: 'N/A')) . ($payment->notes ? " • Notes: {$payment->notes}" : ""),
                'status_badge' => strtoupper($payment->status),
                'status_color' => $isVerified ? 'bg-green-600 text-white' : 'bg-amber-600 text-white',
                'link' => route('admin.payments'),
            ]);
        }

        foreach ($todayUsers as $u) {
            $todayTimeline->push([
                'timestamp' => $u->created_at,
                'time' => $u->created_at->format('h:i A'),
                'type' => 'user_registered',
                'badge' => '👤 User Registered',
                'badge_color' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                'icon' => 'fas fa-user-plus text-indigo-600',
                'title' => "New customer registration: {$u->name}",
                'subtitle' => "Phone: " . ($u->phone ?: 'N/A') . " • Email: " . ($u->email ?: 'N/A'),
                'status_badge' => 'REGISTERED',
                'status_color' => 'bg-indigo-600 text-white',
                'link' => route('admin.users'),
            ]);
        }

        foreach ($todayGstBills as $gb) {
            $amt = '₹' . number_format($gb->grand_total, 2);
            $todayTimeline->push([
                'timestamp' => $gb->created_at,
                'time' => $gb->created_at->format('h:i A'),
                'type' => 'gst_bill',
                'badge' => '🧾 GST Bill Created',
                'badge_color' => 'bg-purple-100 text-purple-800 border-purple-300',
                'icon' => 'fas fa-file-invoice-dollar text-purple-600',
                'title' => "GST Bill #{$gb->bill_number} generated for {$gb->customer_name}",
                'subtitle' => "Grand Total: {$amt} • GST / Aadhaar: " . ($gb->customer_gstin ?: 'N/A'),
                'status_badge' => 'GENERATED',
                'status_color' => 'bg-purple-600 text-white',
                'link' => route('admin.gst-bills.index', ['search' => $gb->bill_number]),
            ]);
        }

        $todayTimeline = $todayTimeline->sortByDesc('timestamp')->values();

        $todayBreakdown = [
            'orders_count' => $todayOrdersCount,
            'orders_revenue' => $todayOrdersRevenue,
            'payments_count' => $todayPaymentsVerifiedCount,
            'payments_amount' => $todayPaymentsVerifiedAmount,
            'users_count' => $todayUsersCount,
            'gst_bills_count' => $todayGstBillsCount,
            'gst_bills_amount' => $todayGstBillsAmount,
            'timeline' => $todayTimeline,
        ];

        // Pre-calculate active stocks catalog serial mapping to match price list catalog serials
        $allActiveCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
        $allActiveStocks = Stock::select(['id', 'category', 'order_within_category'])->where('is_active', true)->get()->groupBy('category');
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
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'confirmedOrders' => $confirmedOrders,
            'dispatchedOrders' => $dispatchedOrders,
            'completedOrders' => $completedOrders,
            'availableYears' => $availableYears,
            'available_years' => $availableYears,
            'todayBreakdown' => $todayBreakdown,
            'catalogSnoMap' => $catalogSnoMap,
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

        $selectedYear = ($this->selected_year !== null && $this->selected_year !== '') ? $this->selected_year : (($this->selectedYear !== null && $this->selectedYear !== '') ? $this->selectedYear : '');
        if (!empty($selectedYear) && $selectedYear !== 'all') {
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
        $this->editCustomerName = $order->customer_name ?: ($order->user->name ?? '');
        $this->editCustomerMobile = $order->customer_mobile ?: ($order->user->phone ?? '');
        $this->editCustomerEmail = $order->customer_email ?: ($order->user->email ?? '');
        $this->editCustomerState = $order->customer_state ?? '';
        $this->editCustomerDistrict = $order->customer_district ?? '';
        $this->editCustomerCity = $order->customer_city ?? '';
        $this->editDeliveryPoint = $order->delivery_point ?? '';
        $this->editPinCode = $order->pin_code ?? '';
        $this->editHasGst = (bool)$order->has_gst;
        $this->editDeliveryType = $order->delivery_type ?? 'none';
        $this->editTransportProvider = $order->transport_provider ?? '';
        $this->editTransportDetails = $order->transport_details ?? '';

        $this->newProductId = '';
        $this->newItemSearch = '';
        $this->searchItemsList = [];
        $this->showSearchDropdown = false;
        $this->newItemQty = 1;
        $this->modalMessage = '';
        $this->modalMessageType = '';

        // Format items for modal editing safely whether array, json, or DB collection
        $this->editItems = [];
        $rawItems = $order->items_json;
        if (empty($rawItems)) {
            $dbItems = \App\Models\OrderItem::where('order_id', $order->id)->get();
            if ($dbItems->isNotEmpty()) {
                $rawItems = $dbItems->toArray();
            } elseif (!empty($order->items_json)) {
                $rawItems = $order->items_json;
            } else {
                $rawItems = [];
            }
        }

        if ($rawItems && (is_array($rawItems) || is_object($rawItems))) {
            $productIds = [];
            foreach ($rawItems as $item) {
                $pId = is_array($item) ? ($item['product_id'] ?? $item['stock_id'] ?? null) : ($item->product_id ?? $item->stock_id ?? null);
                if ($pId) $productIds[] = $pId;
            }
            $stocksMap = !empty($productIds)
                ? Stock::select(['id', 'item_name', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage'])
                    ->whereIn('id', array_unique($productIds))
                    ->get()
                    ->keyBy('id')
                : collect();

            foreach ($rawItems as $item) {
                $productId = is_array($item) ? ($item['product_id'] ?? $item['stock_id'] ?? null) : ($item->product_id ?? $item->stock_id ?? null);
                $productName = is_array($item) ? ($item['product_name'] ?? null) : ($item->product_name ?? null);
                $qty = (int)(is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1));
                $price = (float)(is_array($item) ? ($item['rate'] ?? $item['price'] ?? 0) : ($item->price ?? $item->rate ?? 0));
                $id = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);
                $isLuckySpinGift = is_array($item) ? (!empty($item['is_lucky_spin_gift'])) : (!empty($item->is_lucky_spin_gift));

                $isCombo = (is_array($item) && !empty($item['is_combo'])) 
                    || (is_object($item) && !empty($item->is_combo)) 
                    || ($productId >= 999000 && $productId <= 999999) 
                    || str_contains(strtoupper($productName ?? ''), 'COMBO');

                $stock = $productId ? $stocksMap->get($productId) : null;

                if ($isCombo) {
                    $comboPrice = $price;
                    // Detect if combo price was corrupted (e.g. 11764.71 instead of 3000)
                    if ($comboPrice <= 0 || ($comboPrice > 10000 && str_contains(strtoupper($productName ?? ''), '3K'))) {
                        if (str_contains(strtoupper($productName ?? ''), '3K')) $comboPrice = 3000;
                        elseif (str_contains(strtoupper($productName ?? ''), '5K')) $comboPrice = 5000;
                        elseif (str_contains(strtoupper($productName ?? ''), '8K')) $comboPrice = 8000;
                        elseif (str_contains(strtoupper($productName ?? ''), '10K')) $comboPrice = 10000;
                    }
                    $price = $comboPrice;
                    $origPrice = $comboPrice;
                } else {
                    $origPrice = (!empty($stock->original_price) && (float)$stock->original_price > 0)
                        ? (float)$stock->original_price
                        : ((!empty($item['original_price']) && (float)$item['original_price'] > 0)
                            ? (float)$item['original_price']
                            : ($price > 0 ? round($price / 0.255, 2) : 0));
                }

                $this->editItems[] = [
                    'id' => $id,
                    'product_id' => $productId,
                    'stock_id' => $productId,
                    'product_name' => $productName ?: ($stock->item_name ?? 'Product #' . $productId),
                    'rate' => $price,
                    'price' => $price,
                    'original_price' => $origPrice,
                    'discount_percentage' => $isCombo ? 0 : (float)($stock->discount_percentage ?? 70),
                    'special_discount_percentage' => $isCombo ? 0 : (float)($stock->special_discount_percentage ?? 15),
                    'quantity' => max(1, $qty),
                    'total' => $price * max(1, $qty),
                    'is_lucky_spin_gift' => $isLuckySpinGift,
                    'is_combo' => $isCombo,
                ];
            }
        }
        $this->editingOrderItems = $this->editItems;

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
        $this->showSearchDropdown = false;
        $this->newItemQty = 1;
        $this->modalMessage = '';
        $this->modalMessageType = '';
    }

    public function updateItemQty($index, $newQty = null)
    {
        if (isset($this->editItems[$index])) {
            $qty = ($newQty !== null && is_numeric($newQty)) ? max(1, (int)$newQty) : max(1, (int)($this->editItems[$index]['quantity'] ?? 1));
            $this->editItems[$index]['quantity'] = $qty;
            $rate = (float)($this->editItems[$index]['rate'] ?? $this->editItems[$index]['price'] ?? 0);
            $this->editItems[$index]['total'] = $rate * $qty;
            $this->editingOrderItems = $this->editItems;
        }
    }

    public function increaseQty($index)
    {
        if (isset($this->editItems[$index])) {
            $this->editItems[$index]['quantity'] = (int)($this->editItems[$index]['quantity'] ?? 1) + 1;
            $rate = (float)($this->editItems[$index]['rate'] ?? $this->editItems[$index]['price'] ?? 0);
            $this->editItems[$index]['total'] = $rate * $this->editItems[$index]['quantity'];
            $this->editingOrderItems = $this->editItems;
        }
    }

    public function decreaseQty($index)
    {
        if (isset($this->editItems[$index])) {
            $current = (int)($this->editItems[$index]['quantity'] ?? 1);
            if ($current > 1) {
                $this->editItems[$index]['quantity'] = $current - 1;
                $rate = (float)($this->editItems[$index]['rate'] ?? $this->editItems[$index]['price'] ?? 0);
                $this->editItems[$index]['total'] = $rate * $this->editItems[$index]['quantity'];
                $this->editingOrderItems = $this->editItems;
            }
        }
    }

    public function removeItem($index)
    {
        if (isset($this->editItems[$index])) {
            $itemName = $this->editItems[$index]['product_name'] ?? 'Item';
            array_splice($this->editItems, $index, 1);
            $this->editItems = array_values($this->editItems);
            $this->editingOrderItems = $this->editItems;
            $this->modalMessage = "Removed \"{$itemName}\" from order.";
            $this->modalMessageType = 'success';
        }
    }

    public function addNewItem()
    {
        $stock = null;
        // Use select() to avoid triggering image_url accessor file I/O on production
        $selectCols = ['id', 'item_name', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'quantity', 'ordered_count'];
        
        if (!empty($this->newProductId)) {
            $stock = Stock::select($selectCols)->find($this->newProductId);
        }
        
        if (!$stock && !empty($this->newItemSearch)) {
            $term = trim($this->newItemSearch);
            // 1. Try finding by exact ID
            if (is_numeric($term)) {
                $stock = Stock::select($selectCols)->find($term);
            }
            // 2. Try exact name match
            if (!$stock) {
                $stock = Stock::select($selectCols)->where('item_name', $term)->first();
            }
            // 3. Try partial name match (LIKE)
            if (!$stock) {
                $stock = Stock::select($selectCols)->where('item_name', 'like', '%' . $term . '%')->first();
            }
        }

        if (!$stock) {
            $this->modalMessage = 'Please select or search a valid product to add.';
            $this->modalMessageType = 'error';
            return;
        }

        $qty = max(1, (int)$this->newItemQty);

        // Check if already in editItems
        foreach ($this->editItems as $idx => $item) {
            if (($item['product_id'] ?? null) == $stock->id) {
                $this->editItems[$idx]['quantity'] += $qty;
                $rate = (float)($this->editItems[$idx]['rate'] ?? $this->editItems[$idx]['price'] ?? 0);
                $this->editItems[$idx]['total'] = $rate * $this->editItems[$idx]['quantity'];
                
                $this->editingOrderItems = $this->editItems;
                $this->newProductId = '';
                $this->newItemSearch = '';
                $this->searchItemsList = [];
                $this->showSearchDropdown = false;
                $this->newItemQty = 1;
                $this->modalMessage = "Updated quantity for \"{$stock->item_name}\" to {$this->editItems[$idx]['quantity']}.";
                $this->modalMessageType = 'success';
                return;
            }
        }

        $rate = (float)($stock->price ?? 0);
        $originalPrice = (!empty($stock->original_price) && (float)$stock->original_price > 0)
            ? (float)$stock->original_price
            : ($rate > 0 ? round($rate / 0.255, 2) : 0);

        $this->editItems[] = [
            'id' => null,
            'product_id' => $stock->id,
            'stock_id' => $stock->id,
            'product_name' => $stock->item_name,
            'rate' => $rate,
            'price' => $rate,
            'original_price' => $originalPrice,
            'discount_percentage' => (float)($stock->discount_percentage ?? 70),
            'special_discount_percentage' => (float)($stock->special_discount_percentage ?? 15),
            'quantity' => $qty,
            'total' => $rate * $qty,
            'is_lucky_spin_gift' => false,
            'is_combo' => false,
        ];
        $this->editItems = array_values($this->editItems);
        $this->editingOrderItems = $this->editItems;

        $this->newProductId = '';
        $this->newItemSearch = '';
        $this->searchItemsList = [];
        $this->showSearchDropdown = false;
        $this->newItemQty = 1;
        $this->modalMessage = "Added \"{$stock->item_name}\" (Qty: {$qty}) to order.";
        $this->modalMessageType = 'success';
    }

    // Recalculate totals in real time for modal
    public function recalculateTotals()
    {
        $regularSubtotal = 0;
        $comboSubtotal = 0;

        foreach ($this->editItems as $item) {
            // Lucky spin free gifts don't contribute to subtotal or line discounts
            if (!empty($item['is_lucky_spin_gift']) || !empty($item['is_free_gift'])) {
                continue;
            }

            $qty = (int)($item['quantity'] ?? 1);
            $pId = (int)($item['product_id'] ?? 0);
            $pName = (string)($item['product_name'] ?? '');
            $isCombo = !empty($item['is_combo']) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO');

            if ($isCombo) {
                $comboPrice = (float)($item['price'] ?? $item['rate'] ?? 0);
                if ($comboPrice <= 0 || ($comboPrice > 10000 && str_contains(strtoupper($pName), '3K'))) {
                    if (str_contains(strtoupper($pName), '3K')) $comboPrice = 3000;
                    elseif (str_contains(strtoupper($pName), '5K')) $comboPrice = 5000;
                    elseif (str_contains(strtoupper($pName), '8K')) $comboPrice = 8000;
                    elseif (str_contains(strtoupper($pName), '10K')) $comboPrice = 10000;
                }
                $comboSubtotal += $comboPrice * $qty;
            } else {
                $rate = (float)($item['rate'] ?? $item['price'] ?? 0);
                $origPrice = (!empty($item['original_price']) && (float)$item['original_price'] > $rate)
                    ? (float)$item['original_price']
                    : ($rate > 0 ? round($rate / 0.255, 2) : 0);

                $lineSubtotal = $origPrice * $qty;
                $regularSubtotal += $lineSubtotal;
            }
        }

        $discount70 = round($regularSubtotal * 0.70, 2);
        $afterDiscount70 = round($regularSubtotal - $discount70, 2);
        $discount15 = round($afterDiscount70 * 0.15, 2);
        $afterDiscount15 = round($afterDiscount70 - $discount15, 2);

        // Add Packaging Cost (5% on regular items AND combos)
        $taxableGoods = $afterDiscount15 + $comboSubtotal;
        $packingCharge = ($taxableGoods > 0) ? round($taxableGoods * 0.05, 2) : 0;
        $regularPacking = ($afterDiscount15 > 0) ? round($afterDiscount15 * 0.05, 2) : 0;
        $comboPacking = round($packingCharge - $regularPacking, 2);
        $regularWithPacking = round($afterDiscount15 + $regularPacking, 2);
        
        $couponDiscount = 0;
        if ($this->editingOrder && $this->editingOrder->coupon_discount) {
            $couponDiscount = (float)$this->editingOrder->coupon_discount;
        }

        // Coupon under packaging cost
        $afterCoupon = max(0, round($regularWithPacking - $couponDiscount, 2));

        $luckySpinDiscount = 0;
        if ($this->editingOrder && $this->editingOrder->lucky_spin_discount) {
            $luckySpinDiscount = (float)$this->editingOrder->lucky_spin_discount;
        }

        // Net Rate Items (combos) with combo packing added in the LAST:
        $taxableAmount = max(0, round($afterCoupon - $luckySpinDiscount + $comboSubtotal + $comboPacking, 2));
        $gstAmount = $this->editHasGst ? round($taxableAmount * 0.18, 2) : 0;
        $finalTotal = round($taxableAmount + $gstAmount);

        return [
            'subtotal' => $regularSubtotal,
            'regular_subtotal' => $regularSubtotal,
            'discount_70_percent' => $discount70,
            'amount_after_70_discount' => $afterDiscount70,
            'special_discount_15_percent' => $discount15,
            'amount_after_15_discount' => $afterDiscount15,
            'packing_charge_5_percent' => $packingCharge,
            'coupon_discount' => $couponDiscount,
            'amount_after_coupon' => $afterCoupon,
            'combo_subtotal' => $comboSubtotal,
            'lucky_spin_discount' => $luckySpinDiscount,
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
            'editCustomerName' => 'nullable|string|max:255',
            'editCustomerMobile' => 'nullable|string|max:20',
        ]);

        if (empty($this->editItems)) {
            $this->modalMessage = 'Order must contain at least one item.';
            $this->modalMessageType = 'error';
            return;
        }

        try {
            $oldStatus = strtolower($order->status);

            // Once an order is confirmed or processed, do not allow moving back to pending
            if ($oldStatus !== 'pending' && $this->editStatus === 'pending') {
                $this->modalMessage = 'A confirmed order cannot be moved back to pending.';
                $this->modalMessageType = 'error';
                return;
            }

            $oldPaymentStatus = strtolower($order->payment_status);
            $oldReceiveAmount = $order->receive_amount;
            $oldNotes = $order->notes;

            $totals = $this->recalculateTotals();

            $newItemsJson = [];
            foreach ($this->editItems as $item) {
                $isGift = !empty($item['is_lucky_spin_gift']);
                $rate = $isGift ? 0 : (float)($item['rate'] ?? $item['price'] ?? 0);
                $qty = max(1, (int)($item['quantity'] ?? 1));
                $productId = $item['product_id'] ?? $item['stock_id'] ?? null;
                $productName = $item['product_name'] ?? 'Product';

                $isCombo = !empty($item['is_combo']) 
                    || ($productId >= 999000 && $productId <= 999999) 
                    || str_contains(strtoupper($productName), 'COMBO');

                if ($isCombo) {
                    if ($rate <= 0 || ($rate > 10000 && str_contains(strtoupper($productName), '3K'))) {
                        if (str_contains(strtoupper($productName), '3K')) $rate = 3000;
                        elseif (str_contains(strtoupper($productName), '5K')) $rate = 5000;
                        elseif (str_contains(strtoupper($productName), '8K')) $rate = 8000;
                        elseif (str_contains(strtoupper($productName), '10K')) $rate = 10000;
                    }
                    $origPrice = $rate;
                } else {
                    $origPrice = (!empty($item['original_price']) && (float)$item['original_price'] > $rate)
                        ? (float)$item['original_price']
                        : ($rate > 0 ? round($rate / 0.255, 2) : 0);
                }

                $newItemsJson[] = [
                    'id' => $item['id'] ?? null,
                    'product_id' => $productId,
                    'stock_id' => $productId,
                    'product_name' => $productName,
                    'content' => $item['content'] ?? '',
                    'rate' => $rate,
                    'price' => $rate,
                    'original_price' => $origPrice,
                    'discount_percentage' => $isCombo ? 0 : ($item['discount_percentage'] ?? 70),
                    'special_discount_percentage' => $isCombo ? 0 : ($item['special_discount_percentage'] ?? 15),
                    'quantity' => $qty,
                    'total' => $rate * $qty,
                    'subtotal' => $rate * $qty,
                    'is_lucky_spin_gift' => $isGift,
                    'is_combo' => $isCombo,
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
                'customer_name' => $this->editCustomerName ?: ($order->customer_name ?: 'Customer'),
                'customer_mobile' => $this->editCustomerMobile ?: ($order->customer_mobile ?: '9999999999'),
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
                'lucky_spin_discount' => $totals['lucky_spin_discount'],
                'has_gst' => $this->editHasGst,
                'gst_amount' => $totals['gst_amount'],
                'delivery_type' => $this->editDeliveryType,
                'transport_provider' => $provider,
                'transport_details' => $details,
                'total' => $totals['total'],
                'total_amount' => $totals['total'],
                'final_amount' => $totals['total'],
            ];

            // Try ensuring missing columns exist in the database table if possible
            try {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_discount')) {
                    \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_prize')) {
                            $table->string('lucky_spin_prize')->nullable();
                        }
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_discount')) {
                            $table->decimal('lucky_spin_discount', 10, 2)->default(0);
                        }
                    });
                }
            } catch (\Throwable $migrationEx) {
                // Silently ignore if DB user doesn't have ALTER permissions
            }

            // Safety check: Filter updateData to only columns that actually exist in the orders table
            try {
                $existingOrderColumns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');
                if (!empty($existingOrderColumns)) {
                    $updateData = array_intersect_key($updateData, array_flip($existingOrderColumns));
                }
            } catch (\Throwable $colEx) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_discount')) {
                    unset($updateData['lucky_spin_discount']);
                }
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'lucky_spin_prize')) {
                    unset($updateData['lucky_spin_prize']);
                }
            }

            $order->update($updateData);

            // Sync OrderItem database table records
            try {
                \App\Models\OrderItem::where('order_id', $order->id)->delete();
                foreach ($newItemsJson as $itemRow) {
                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'stock_id' => $itemRow['product_id'],
                        'product_name' => $itemRow['product_name'],
                        'content' => $itemRow['content'] ?? '',
                        'rate' => $itemRow['rate'],
                        'quantity' => $itemRow['quantity'],
                        'total' => $itemRow['total'],
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::warning('Could not sync OrderItem table: ' . $e->getMessage());
            }

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

            // Track automated WhatsApp dispatches
            $waNotifications = [];

            try {
                // Automatically send WhatsApp notification if order status CHANGED to confirmed
                if ($this->editStatus === 'confirmed' && $oldStatus !== 'confirmed') {
                    $this->sendWhatsAppConfirmed($order->id);
                    $waNotifications[] = 'WhatsApp Bill sent';
                }

                // Automatically send WhatsApp notification if payment status CHANGED to paid or confirmed
                if (in_array($this->editPaymentStatus, ['paid', 'confirmed']) && $oldPaymentStatus !== $this->editPaymentStatus) {
                    $this->sendWhatsAppPaidBill($order->id);
                    $waNotifications[] = 'WhatsApp Payment confirmation sent';
                }

                // Automatically send WhatsApp notification if order status CHANGED to dispatched
                if ($this->editStatus === 'dispatched' && $oldStatus !== 'dispatched') {
                    $this->sendWhatsAppDispatched($order->id);
                    $waNotifications[] = 'WhatsApp Dispatch alert sent';
                }
            } catch (\Exception $waEx) {
                \Log::warning('WhatsApp notification failed during order save: ' . $waEx->getMessage());
                $waNotifications[] = 'WhatsApp failed (order still saved)';
            }

            $this->closeEditModal();
            $successMsg = "Order #{$order->id} updated successfully!";
            if (!empty($waNotifications)) {
                $successMsg .= " (" . implode(', ', $waNotifications) . ")";
            }
            session()->flash('success', $successMsg);

            
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }
            \Log::error('Error saving order: ' . $e->getMessage(), ['exception' => $e]);
            $this->modalMessage = 'Error updating order: ' . $e->getMessage();
            $this->modalMessageType = 'error';
            session()->flash('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    private function getFormattedItemsList($order)
    {
        $rawItems = $order->items_json ?: [];
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

    public function updateOrderStatus($orderId, $newStatus)
    {
        $validStatuses = ['pending', 'confirmed', 'dispatched', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            session()->flash('error', 'Invalid order status.');
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $oldStatus = strtolower($order->status);
        if ($oldStatus === $newStatus) {
            return;
        }

        // Once an order is confirmed or processed, do not allow moving back to pending
        if ($oldStatus !== 'pending' && $newStatus === 'pending') {
            session()->flash('error', 'A confirmed order cannot be moved back to pending.');
            return;
        }

        $order->update(['status' => $newStatus]);

        Stock::recalculateOrderedCounts();

        OrderLog::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'previous_status' => $oldStatus,
            'changed_by' => auth()->id(),
            'notes' => "Status updated directly from {$oldStatus} to {$newStatus}",
            'payment_status' => null,
        ]);

        $waNote = '';
        try {
            if ($newStatus === 'confirmed') {
                $this->sendWhatsAppConfirmed($order->id);
                $waNote = ' (WhatsApp bill sent automatically)';
            } elseif ($newStatus === 'dispatched') {
                $this->sendWhatsAppDispatched($order->id);
                $waNote = ' (WhatsApp dispatch alert sent automatically)';
            }
        } catch (\Exception $e) {
            \Log::warning('WhatsApp notification failed during status update: ' . $e->getMessage());
            $waNote = ' (WhatsApp notification failed - status still updated)';
        }

        // Flash success AFTER WhatsApp attempts so it doesn't get overwritten
        session()->flash('success', "Order #{$order->id} status updated to " . ucfirst($newStatus) . " successfully!{$waNote}");
    }


    public function sendWhatsAppConfirmed($orderId)
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
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        if (!$phone) {
            session()->flash('error', 'Customer mobile number not available.');
            return;
        }

        $customerName = $this->editCustomerName ?: ($order->customer_name ?: 'Customer');
        $orderValue = '₹' . number_format($order->total_amount ?: ($order->total ?: 0), 2);

        try {
            $smsService = new \App\Services\SMSService();
            $res = $smsService->sendWhatsApp($phone, '', 'order_confirmation', [
                'customer_name' => $customerName,
                'order_id' => (string)$order->id,
                'order_value' => $orderValue,
            ]);

            \Log::info("WhatsApp Order Confirmation triggered for Order #{$order->id} to {$phone}");
            session()->flash('success', "WhatsApp Order Confirmation sent to {$customerName} (+91{$phone})!");
        } catch (\Exception $e) {
            \Log::error("WhatsApp Order Confirmation error for Order #{$order->id}: " . $e->getMessage());
            session()->flash('error', "Error sending WhatsApp notification: " . $e->getMessage());
        }
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
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        if (!$phone) {
            session()->flash('error', 'Customer mobile number not available.');
            return;
        }

        $customerName = $this->editCustomerName ?: ($order->customer_name ?: 'Customer');
        $orderValue = '₹' . number_format($order->total_amount ?: ($order->total ?: 0), 2);
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
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        if (!$phone) {
            session()->flash('error', 'Customer mobile number not available.');
            return;
        }

        $customerName = $this->editCustomerName ?: ($order->customer_name ?: 'Customer');
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

    public function getWhatsAppChatUrl($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) return '#';
        $phone = preg_replace('/[^0-9]/', '', $order->customer_mobile ?: ($order->user->phone ?? ''));
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        $name = $order->customer_name ?: 'Customer';
        $msg = urlencode("Hello {$name}, regarding your Radhe Crackers Order #{$order->id}: ");
        return "https://wa.me/91{$phone}?text={$msg}";
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

    public function exportOrders()
    {
        $selectedYear = $this->selected_year ?: $this->selectedYear;

        $query = Order::with(['user', 'items', 'payment']);

        if ($selectedYear !== '' && $selectedYear !== null && $selectedYear !== 'all') {
            $query->whereYear('created_at', $selectedYear);
        }

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_city', 'like', "%{$search}%");
            });
        }

        $status = $this->status_filter !== 'all' ? $this->status_filter : $this->statusFilter;
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $dateFrom = $this->date_from ?: $this->dateFrom;
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        $dateTo = $this->date_to ?: $this->dateTo;
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $orders = $query->latest()->get();

        $filename = 'orders_' . ($selectedYear && $selectedYear !== 'all' ? "year_{$selectedYear}_" : "") . date('Y-m-d_H-i-s') . '.csv';

        $csvData = [];
        $csvData[] = ['Order ID', 'Customer Name', 'Phone', 'Email', 'City', 'State', 'Total Amount', 'Status', 'Payment Status', 'Created Date'];

        foreach ($orders as $order) {
            $csvData[] = [
                $order->id,
                $order->customer_name ?: ($order->user->name ?? 'Guest'),
                $order->customer_mobile ?: ($order->user->phone ?? ''),
                $order->customer_email ?: ($order->user->email ?? ''),
                $order->customer_city ?? '',
                $order->customer_state ?? '',
                $order->total_amount ?: $order->total,
                $order->status,
                $order->payment_status ?? ($order->payment && $order->payment->verified_at ? 'paid' : 'pending'),
                $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : ''
            ];
        }

        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        session(['export_csv_content' => $csvContent, 'export_csv_filename' => $filename]);
        $this->dispatch('download-csv');
    }
}
