<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GST BILL - {{ $gstBill->bill_number }}</title>
    <style>
        @font-face {
            font-family: 'Noto Sans Tamil';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/NotoSansTamil-Regular.ttf') }}') format('truetype');
        }
        @page {
            size: A4 portrait;
            margin: 8mm;
        }
        body {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .outer-box {
            border: 2px solid #000;
            padding: 0;
            box-sizing: border-box;
        }
        .header-box {
            text-align: center;
            border-bottom: 1.5px solid #000;
            padding: 8px 12px;
        }
        .company-title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .company-address {
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 3px;
        }
        .gstin-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 2px;
        }
        .customer-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1.5px solid #000;
        }
        .customer-table td {
            padding: 6px 10px;
            vertical-align: top;
            font-size: 11px;
        }
        .customer-left {
            width: 65%;
            border-right: 1.5px solid #000;
        }
        .customer-right {
            width: 35%;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 70px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            border-bottom: 1.5px solid #000;
            border-right: 1px solid #000;
            padding: 6px 4px;
            font-size: 11px;
            font-weight: bold;
            background: #f8fafc;
            text-align: center;
        }
        .items-table th:last-child {
            border-right: none;
        }
        .items-table td {
            border-right: 1px solid #000;
            padding: 5px 6px;
            font-size: 11px;
            vertical-align: top;
        }
        .items-table td:last-child {
            border-right: none;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
        }
        .totals-table td {
            padding: 4px 10px;
            font-size: 11px;
            vertical-align: top;
        }
        .totals-left {
            width: 60%;
            border-right: 1.5px solid #000;
        }
        .totals-right {
            width: 40%;
        }
        .totals-right-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-right-table td {
            padding: 2px 0;
            border: none;
        }
        .words-section {
            padding: 6px 10px;
            border-bottom: 1.5px solid #000;
            font-weight: bold;
            font-size: 11px;
        }
        .terms-signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .terms-signature-table td {
            padding: 6px 10px;
            vertical-align: top;
            font-size: 10px;
        }
        .terms-cell {
            width: 62%;
            border-right: 1.5px solid #000;
            line-height: 1.3;
        }
        .signature-cell {
            width: 38%;
            text-align: right;
            vertical-align: bottom;
        }
        .footer-bar {
            border-top: 1.5px solid #000;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="outer-box">
        <!-- Company Header -->
        <div class="header-box">
            <div class="company-title">RADHE CRACKERS</div>
            <div class="company-address">
                4/273-11/7, Virudhunagar Main Road, Amathur, Virudhunagar District, Tamil Nadu, 626005.<br>
                Contact: +91 88070 60809, +91 97510 48974 | Email: radhecrackers@gmail.com
            </div>
            <div class="gstin-title">GSTIN: 33AETFS7090D1ZO</div>
        </div>

        <!-- Customer & Invoice Info Table -->
        <table class="customer-table">
            <tr>
                <td class="customer-left">
                    <div><span class="label">Name</span>: <strong>{{ $gstBill->customer_name }}</strong></div>
                    <div><span class="label">Address</span>: {{ $gstBill->customer_address ?: '-' }}</div>
                    <div><span class="label">GSTIN</span>: <strong>{{ $gstBill->customer_gstin ?: '-' }}</strong></div>
                </td>
                <td class="customer-right">
                    <div><strong>Bill No :</strong> {{ $gstBill->bill_number }}</div>
                    <div><strong>Date :</strong> {{ $gstBill->bill_date ? $gstBill->bill_date->format('d/m/Y') : date('d/m/Y') }}</div>
                    <div style="margin-top: 3px;"><strong>HSN CODE :</strong> {{ $gstBill->hsn_code ?: '3604' }}</div>
                </td>
            </tr>
        </table>

        <!-- Particulars Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 35px;">S.No</th>
                    <th class="text-left">Particulars</th>
                    <th style="width: 50px;">Qty</th>
                    <th style="width: 80px;" class="text-right">Rate</th>
                    <th style="width: 55px;">Per</th>
                    <th style="width: 90px;" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $sno = 1; @endphp
                @foreach($gstBill->items as $item)
                    <tr>
                        <td class="text-center">{{ $sno++ }}</td>
                        <td class="text-left font-bold">{{ $item->particulars }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                        <td class="text-center">{{ $item->per ?: '1 Nos' }}</td>
                        <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach

                {{-- Pad empty rows so table maintains layout --}}
                @for($i = count($gstBill->items); $i < 10; $i++)
                    <tr>
                        <td style="height: 22px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Totals & Extra Info Section -->
        <table class="totals-table">
            <tr>
                <td class="totals-left">
                    <div><strong>Transport :</strong> {{ $gstBill->transport ?: '-' }}</div>
                    <div><strong>No of cases :</strong> {{ $gstBill->no_of_cases ?: '-' }}</div>
                    <div><strong>Place of supply :</strong> {{ $gstBill->place_of_supply ?: '-' }}</div>
                </td>
                <td class="totals-right">
                    <table class="totals-right-table">
                        <tr>
                            <td><strong>Sub Total :</strong></td>
                            <td class="text-right">{{ number_format($gstBill->subtotal, 2) }}</td>
                        </tr>
                        @if($gstBill->cgst_amount > 0)
                        <tr>
                            <td><strong>CGST {{ (float)$gstBill->cgst_rate }} % :</strong></td>
                            <td class="text-right">{{ number_format($gstBill->cgst_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($gstBill->sgst_amount > 0)
                        <tr>
                            <td><strong>SGST {{ (float)$gstBill->sgst_rate }} % :</strong></td>
                            <td class="text-right">{{ number_format($gstBill->sgst_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($gstBill->igst_amount > 0)
                        <tr>
                            <td><strong>IGST {{ (float)$gstBill->igst_rate }} % :</strong></td>
                            <td class="text-right">{{ number_format($gstBill->igst_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Round off :</strong></td>
                            <td class="text-right">{{ number_format($gstBill->round_off, 2) }}</td>
                        </tr>
                        <tr style="font-size: 13px; font-weight: bold; border-top: 1px solid #000;">
                            <td><strong>Grant total :</strong></td>
                            <td class="text-right">₹{{ number_format($gstBill->grand_total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Amount In Words -->
        <div class="words-section">
            {{ $gstBill->amount_in_words }}
        </div>

        <!-- Terms & Signature -->
        <table class="terms-signature-table">
            <tr>
                <td class="terms-cell">
                    1. Certified that the particulars given above are true and correct.<br>
                    2. Goods once sold cannot be taken back on any account.<br>
                    3. Our responsibility ceases at the goods leaves our godown and we are not responsible for any loss or damage during transit.<br>
                    4. Rate Quoted are EX-FACTORY. &nbsp; 5. Subject to Sattur Jurisdiction.
                </td>
                <td class="signature-cell">
                    <div style="font-weight: bold; margin-bottom: 35px;">For Radhe Crackers</div>
                    <div style="font-weight: bold; text-decoration: underline;">Authorized Signature</div>
                </td>
            </tr>
        </table>

        <!-- Footer Bar -->
        <table style="width: 100%; border-top: 1.5px solid #000; padding: 4px 10px; font-size: 10px; font-weight: bold;">
            <tr>
                <td style="width: 20%;">E & O.E</td>
                <td style="text-align: center; width: 80%;">Thank you for business with us!</td>
            </tr>
        </table>
    </div>
</body>
</html>
