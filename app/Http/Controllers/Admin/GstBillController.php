<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GstBill;
use App\Models\GstBillItem;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GstBillController extends Controller
{
    public function index(Request $request)
    {
        $query = GstBill::with('items')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_gstin', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }

        $gstBills = $query->paginate(15);

        $totalBillsCount = GstBill::count();
        $totalGrandAmount = GstBill::sum('grand_total');
        $totalCgst = GstBill::sum('cgst_amount');
        $totalSgst = GstBill::sum('sgst_amount');

        return view('admin.gst_bills.index', compact(
            'gstBills', 'totalBillsCount', 'totalGrandAmount', 'totalCgst', 'totalSgst'
        ));
    }

    public function create(Request $request)
    {
        $stocks = Stock::orderBy('category')->orderBy('item_name')->get();
        $prefilledOrder = null;
        $prefilledItems = [];

        if ($request->filled('order_id')) {
            $order = Order::with('items')->find($request->order_id);
            if ($order) {
                $prefilledOrder = $order;
                $items = is_array($order->items) ? $order->items : ($order->items_json ?? []);
                foreach ($items as $item) {
                    $itemArr = is_object($item) ? (array)$item : $item;
                    $prefilledItems[] = [
                        'stock_id' => $itemArr['product_id'] ?? $itemArr['stock_id'] ?? null,
                        'particulars' => $itemArr['product_name'] ?? 'Cracker Item',
                        'qty' => (int)($itemArr['quantity'] ?? 1),
                        'rate' => (float)($itemArr['price'] ?? $itemArr['rate'] ?? 0),
                        'per' => '1 Nos',
                        'amount' => (int)($itemArr['quantity'] ?? 1) * (float)($itemArr['price'] ?? $itemArr['rate'] ?? 0)
                    ];
                }
            }
        }

        // Generate next invoice number
        $lastBill = GstBill::orderBy('id', 'desc')->first();
        $nextNumber = $lastBill ? ((int)preg_replace('/[^0-9]/', '', $lastBill->bill_number) + 1) : 1001;
        $defaultBillNo = 'GST-' . date('Y') . '-' . sprintf('%04d', $nextNumber);

        return view('admin.gst_bills.create', compact('stocks', 'prefilledOrder', 'prefilledItems', 'defaultBillNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_number' => 'required|string|unique:gst_bills,bill_number',
            'customer_name' => 'required|string|max:255',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.particulars' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $qty = (int)$item['qty'];
            $rate = (float)$item['rate'];
            $amount = round($qty * $rate, 2);
            $subtotal += $amount;

            $itemsData[] = [
                'stock_id' => $item['stock_id'] ?? null,
                'particulars' => $item['particulars'],
                'qty' => $qty,
                'rate' => $rate,
                'per' => $item['per'] ?? '1 Nos',
                'amount' => $amount
            ];
        }

        $taxType = $request->input('tax_type', 'cgst_sgst'); // 'cgst_sgst' or 'igst'
        $cgstRate = ($taxType === 'cgst_sgst') ? (float)$request->input('cgst_rate', 9) : 0;
        $sgstRate = ($taxType === 'cgst_sgst') ? (float)$request->input('sgst_rate', 9) : 0;
        $igstRate = ($taxType === 'igst') ? (float)$request->input('igst_rate', 18) : 0;

        $cgstAmount = round(($subtotal * $cgstRate) / 100, 2);
        $sgstAmount = round(($subtotal * $sgstRate) / 100, 2);
        $igstAmount = round(($subtotal * $igstRate) / 100, 2);

        $rawTotal = $subtotal + $cgstAmount + $sgstAmount + $igstAmount;
        $grandTotal = round($rawTotal);
        $roundOff = round($grandTotal - $rawTotal, 2);

        $amountInWords = $this->numberToWordsIndian($grandTotal);

        $gstBill = GstBill::create([
            'bill_number' => $request->bill_number,
            'order_id' => $request->order_id ?: null,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_gstin' => $request->customer_gstin,
            'bill_date' => $request->bill_date,
            'hsn_code' => $request->input('hsn_code', '3604'),
            'transport' => $request->transport,
            'no_of_cases' => $request->no_of_cases,
            'place_of_supply' => $request->place_of_supply,
            'subtotal' => $subtotal,
            'cgst_rate' => $cgstRate,
            'cgst_amount' => $cgstAmount,
            'sgst_rate' => $sgstRate,
            'sgst_amount' => $sgstAmount,
            'igst_rate' => $igstRate,
            'igst_amount' => $igstAmount,
            'round_off' => $roundOff,
            'grand_total' => $grandTotal,
            'amount_in_words' => $amountInWords
        ]);

        foreach ($itemsData as $item) {
            $gstBill->items()->create($item);
        }

        return redirect()->route('admin.gst-bills.index')->with('success', 'GST Bill created successfully!');
    }

    public function showPdf($id)
    {
        $gstBill = GstBill::with('items')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.gst-bill', compact('gstBill'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('GST_Bill_' . $gstBill->bill_number . '.pdf');
    }

    public function destroy($id)
    {
        $gstBill = GstBill::findOrFail($id);
        $gstBill->delete();

        return redirect()->route('admin.gst-bills.index')->with('success', 'GST Bill deleted successfully.');
    }

    private function numberToWordsIndian($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty',
            '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred
                    : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        return trim($result) ? (trim($result) . " Only") : "Zero Only";
    }
}
