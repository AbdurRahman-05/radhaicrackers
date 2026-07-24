@extends('layouts.admin')

@section('title', 'Create GST Bill')
@section('page-title', 'Create GST Bill')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Create New GST Invoice / Bill</h2>
            <p class="text-xs text-gray-500 mt-0.5">Generate a GST tax bill with CGST (9%) + SGST (9%) or IGST (18%) pricing</p>
        </div>
        <a href="{{ route('admin.gst-bills.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-lg text-xs transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Back to Bills
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700 text-xs rounded space-y-1">
            <strong>Please correct the following errors:</strong>
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.gst-bills.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($prefilledOrder)
            <input type="hidden" name="order_id" value="{{ $prefilledOrder->id }}">
        @endif

        <!-- Customer & Bill Header Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-purple-800 border-b border-purple-100 pb-2">
                📋 Bill & Customer Details
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Invoice / Bill Number *</label>
                    <input type="text" name="bill_number" value="{{ old('bill_number', $defaultBillNo) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none font-bold text-purple-700">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Date *</label>
                    <input type="date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">HSN Code</label>
                    <input type="text" name="hsn_code" value="{{ old('hsn_code', '3604') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Customer / Party Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', $prefilledOrder->customer_name ?? '') }}" placeholder="e.g. KING SANTHA CRACKERS" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none font-semibold">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Customer GSTIN</label>
                    <input type="text" name="customer_gstin" value="{{ old('customer_gstin', '') }}" placeholder="e.g. 33ABDFK9901C1Z0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none uppercase font-semibold">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Place of Supply</label>
                    <input type="text" name="place_of_supply" value="{{ old('place_of_supply', $prefilledOrder->customer_state ?? 'Tamil Nadu') }}" placeholder="e.g. Tamil Nadu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">Customer Address</label>
                    <input type="text" name="customer_address" value="{{ old('customer_address', implode(', ', array_filter([$prefilledOrder->customer_city ?? '', $prefilledOrder->customer_district ?? '', $prefilledOrder->customer_state ?? '']))) }}" placeholder="e.g. AMATHUR, VIRUDHUNAGAR-626005" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Transport Details</label>
                    <input type="text" name="transport" value="{{ old('transport', $prefilledOrder->transport_provider ?? '') }}" placeholder="e.g. Lorry Transport" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">No. of Cases / Boxes</label>
                    <input type="text" name="no_of_cases" value="{{ old('no_of_cases', '') }}" placeholder="e.g. 5 Boxes" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">GST Tax Type</label>
                    <select name="tax_type" id="taxTypeSelect" onchange="calculateTotals()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white font-semibold">
                        <option value="cgst_sgst" selected>CGST (9%) + SGST (9%) [Intra-state / Tamil Nadu]</option>
                        <option value="igst">IGST (18%) [Inter-state / Other States]</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Particulars & Items Table -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
            <div class="flex justify-between items-center border-b border-purple-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-purple-800">
                    📦 Product Particulars & Pricing
                </h3>
                <button type="button" onclick="addRow()" class="bg-purple-100 hover:bg-purple-200 text-purple-800 font-bold px-3 py-1 rounded text-xs transition-colors flex items-center gap-1">
                    <i class="fas fa-plus"></i> Add Item Row
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs" id="itemsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left font-bold text-gray-600 uppercase" style="width: 40%;">Particulars / Product Name *</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-600 uppercase" style="width: 15%;">Qty *</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-600 uppercase" style="width: 15%;">Rate (₹) *</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-600 uppercase" style="width: 15%;">Per</th>
                            <th class="px-2 py-2 text-right font-bold text-gray-600 uppercase" style="width: 15%;">Amount (₹)</th>
                            <th class="px-2 py-2 text-center" style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="itemsTableBody">
                        <!-- Dynamic Rows loaded via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Totals Preview Section -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <div class="w-full md:w-80 space-y-2 text-xs bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between text-gray-700">
                        <span>Sub Total:</span>
                        <span class="font-bold text-gray-900" id="previewSubtotal">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-blue-700" id="cgstRow">
                        <span>CGST (9%):</span>
                        <span class="font-bold" id="previewCgst">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-indigo-700" id="sgstRow">
                        <span>SGST (9%):</span>
                        <span class="font-bold" id="previewSgst">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-purple-700 hidden" id="igstRow">
                        <span>IGST (18%):</span>
                        <span class="font-bold" id="previewIgst">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Round off:</span>
                        <span class="font-medium" id="previewRoundOff">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-green-700 border-t border-gray-300 pt-2">
                        <span>Grand Total:</span>
                        <span id="previewGrandTotal">₹0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.gst-bills.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-6 py-2.5 rounded-lg text-xs transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-8 py-2.5 rounded-lg text-xs transition-colors shadow-md flex items-center gap-2">
                <i class="fas fa-check"></i> Save & Generate GST Bill
            </button>
        </div>
    </form>
</div>

<script>
    const availableStocks = @json($stocks);
    const prefilledItems = @json($prefilledItems);

    function addRow(particulars = '', qty = 1, rate = 0, per = '1 Nos', stockId = null) {
        const tbody = document.getElementById('itemsTableBody');
        const rowIndex = tbody.children.length;

        let stockOptionsHtml = '<option value="">-- Custom / Select Product --</option>';
        availableStocks.forEach(st => {
            const selected = (stockId && stockId == st.id) ? 'selected' : '';
            stockOptionsHtml += `<option value="${st.id}" data-name="${st.item_name}" data-price="${st.price}" ${selected}>${st.item_name} (₹${st.price})</option>`;
        });

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-2 py-2">
                <div class="space-y-1">
                    <select onchange="onStockSelect(this, ${rowIndex})" class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-white">
                        ${stockOptionsHtml}
                    </select>
                    <input type="text" name="items[${rowIndex}][particulars]" value="${particulars}" required placeholder="Particulars / Item Name" class="w-full px-2 py-1 border border-gray-300 rounded text-xs font-semibold focus:ring-1 focus:ring-purple-500">
                    <input type="hidden" name="items[${rowIndex}][stock_id]" value="${stockId || ''}">
                </div>
            </td>
            <td class="px-2 py-2">
                <input type="number" min="1" name="items[${rowIndex}][qty]" value="${qty}" required oninput="calculateTotals()" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center font-bold">
            </td>
            <td class="px-2 py-2">
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][rate]" value="${rate}" required oninput="calculateTotals()" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center font-bold">
            </td>
            <td class="px-2 py-2">
                <input type="text" name="items[${rowIndex}][per]" value="${per}" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center">
            </td>
            <td class="px-2 py-2 text-right font-bold text-gray-900" id="rowAmount_${rowIndex}">
                ₹0.00
            </td>
            <td class="px-2 py-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 p-1">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        calculateTotals();
    }

    function onStockSelect(selectElem, rowIndex) {
        const option = selectElem.options[selectElem.selectedIndex];
        const particularsInput = selectElem.nextElementSibling;
        const stockIdInput = particularsInput.nextElementSibling;
        const row = selectElem.closest('tr');
        const rateInput = row.querySelector('input[name*="[rate]"]');

        if (option.value) {
            particularsInput.value = option.getAttribute('data-name');
            rateInput.value = option.getAttribute('data-price');
            stockIdInput.value = option.value;
        }
        calculateTotals();
    }

    function removeRow(btn) {
        const tbody = document.getElementById('itemsTableBody');
        if (tbody.children.length > 1) {
            btn.closest('tr').remove();
            calculateTotals();
        } else {
            alert('At least one product item is required.');
        }
    }

    function calculateTotals() {
        const tbody = document.getElementById('itemsTableBody');
        let subtotal = 0;

        Array.from(tbody.children).forEach((tr, index) => {
            const qtyInput = tr.querySelector('input[name*="[qty]"]');
            const rateInput = tr.querySelector('input[name*="[rate]"]');
            const rowAmountTd = tr.querySelector(`[id^="rowAmount_"]`);

            const qty = parseFloat(qtyInput?.value || 0);
            const rate = parseFloat(rateInput?.value || 0);
            const amount = qty * rate;

            if (rowAmountTd) {
                rowAmountTd.textContent = '₹' + amount.toFixed(2);
            }
            subtotal += amount;
        });

        const taxType = document.getElementById('taxTypeSelect').value;
        let cgst = 0, sgst = 0, igst = 0;

        if (taxType === 'cgst_sgst') {
            cgst = (subtotal * 0.09);
            sgst = (subtotal * 0.09);
            document.getElementById('cgstRow').classList.remove('hidden');
            document.getElementById('sgstRow').classList.remove('hidden');
            document.getElementById('igstRow').classList.add('hidden');
        } else {
            igst = (subtotal * 0.18);
            document.getElementById('cgstRow').classList.add('hidden');
            document.getElementById('sgstRow').classList.add('hidden');
            document.getElementById('igstRow').classList.remove('hidden');
        }

        const rawTotal = subtotal + cgst + sgst + igst;
        const grandTotal = Math.round(rawTotal);
        const roundOff = grandTotal - rawTotal;

        document.getElementById('previewSubtotal').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('previewCgst').textContent = '₹' + cgst.toFixed(2);
        document.getElementById('previewSgst').textContent = '₹' + sgst.toFixed(2);
        document.getElementById('previewIgst').textContent = '₹' + igst.toFixed(2);
        document.getElementById('previewRoundOff').textContent = '₹' + roundOff.toFixed(2);
        document.getElementById('previewGrandTotal').textContent = '₹' + grandTotal.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (prefilledItems && prefilledItems.length > 0) {
            prefilledItems.forEach(item => {
                addRow(item.particulars, item.qty, item.rate, item.per, item.stock_id);
            });
        } else {
            addRow('30 Shots Multicolour', 7, 2000, '1 Nos');
            addRow('60 Shots Multicolour', 5, 2000, '1 Nos');
            addRow('120 Shots Multicolour', 5, 2000, '1 Nos');
        }
    });
</script>
@endsection
