@php
    $dir = Session::get('language') ? (Session::get('language')->is_rtl ? 'rtl' : 'ltr') : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $dir }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DOKU Payment Receipt</title>

    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .full-width-table {
            width: 100%;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .logo {
            height: 50px;
            width: auto;
        }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .mb-2 { margin-bottom: 10px; }

        .badge {
            border-radius: 3px;
            font-size: 11px;
            padding: 4px 8px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .bill-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .detail-table th,
        .detail-table td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            font-size: 13px;
        }
        .detail-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: left;
            width: 35%;
        }
        .detail-table td {
            text-align: left;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #888;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="body">
        {{-- Header --}}
        <table class="full-width-table">
            <tr>
                <td>
                    <div>
                        @if (!empty($settings['horizontal_logo']))
                            <img class="logo" src="{{ public_path('storage/' . $settings['horizontal_logo']) }}" alt="">
                        @else
                            <img class="logo" src="{{ public_path('assets/no_image_available.jpg') }}" alt="">
                        @endif
                    </div>
                </td>
                <td class="text-right">
                    <div>
                        <span class="bill-title">Payment Receipt</span><br>
                        <span style="font-size: 13px; color: #666;">DOKU Payment Gateway</span>
                        <div class="mt-1">
                            @if ($receipt->payment_status === 'success')
                                <span class="badge badge-success">SUCCESS</span>
                            @elseif ($receipt->payment_status === 'pending')
                                <span class="badge badge-warning">PENDING</span>
                            @else
                                <span class="badge badge-danger">FAILED</span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <hr>

        {{-- System Info & Receipt Info --}}
        <table class="full-width-table">
            <tr>
                <td style="vertical-align: top; width: 50%;">
                    <div>
                        <b>{{ $settings['system_name'] ?? 'eSchool SaaS' }}</b>
                    </div>
                    <div class="mt-1" style="font-size: 12px; color: #666;">
                        {{ $settings['address'] ?? '' }}
                    </div>
                </td>
                <td class="text-right" style="vertical-align: top; width: 50%;">
                    <div>
                        <b>Invoice: {{ $receipt->invoice_number }}</b>
                    </div>
                    <div class="mt-1" style="font-size: 12px;">
                        <b>Date:</b> {{ $receipt->payment_date ? $receipt->payment_date->format('d M Y, H:i') : '-' }}
                    </div>
                    @if ($receipt->doku_transaction_id)
                    <div class="mt-1" style="font-size: 12px;">
                        <b>Transaction ID:</b> {{ $receipt->doku_transaction_id }}
                    </div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- School Details --}}
        <div class="section-title">School Details</div>
        <table class="detail-table">
            <tr>
                <th>School Name</th>
                <td>{{ $receipt->school_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $receipt->school_email }}</td>
            </tr>
        </table>

        {{-- Payment Details --}}
        <div class="section-title">Payment Details</div>
        <table class="detail-table">
            <tr>
                <th>Invoice Number</th>
                <td>{{ $receipt->invoice_number }}</td>
            </tr>
            <tr>
                <th>Package</th>
                <td>{{ $receipt->package_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Payment Gateway</th>
                <td>{{ $receipt->payment_gateway }}</td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td>{{ $receipt->payment_date ? $receipt->payment_date->format('d M Y, H:i:s') : '-' }}</td>
            </tr>
            @if ($receipt->doku_transaction_id)
            <tr>
                <th>DOKU Transaction ID</th>
                <td>{{ $receipt->doku_transaction_id }}</td>
            </tr>
            @endif
            <tr>
                <th>Payment Status</th>
                <td>
                    @if ($receipt->payment_status === 'success')
                        <span class="badge badge-success">SUCCESS</span>
                    @elseif ($receipt->payment_status === 'pending')
                        <span class="badge badge-warning">PENDING</span>
                    @else
                        <span class="badge badge-danger">FAILED</span>
                    @endif
                </td>
            </tr>
            <tr class="total-row">
                <th>Total Amount</th>
                <td>{{ $currency }} {{ number_format($receipt->amount, 2) }}</td>
            </tr>
        </table>

        {{-- Footer --}}
        <div class="footer">
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>Generated on {{ now()->format('d M Y, H:i:s') }}</p>
        </div>
    </div>
</body>

</html>
