<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Purchase Orders Pending PDF Upload Report</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            color: #000000;
            line-height: 1.3;
        }

        /* Prevent messy page breaks on tables */
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .header-title {
            text-align: center;
        }

        .header-title h3 {
            margin: 0;
            font-size: 11px;
            font-weight: normal;
            color: #000000;
            text-transform: uppercase;
        }

        .header-title h2 {
            margin: 2px 0;
            font-size: 15px;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .header-title h4 {
            margin: 2px 0 0 0;
            font-size: 11px;
            font-weight: bold;
            color: #000000;
        }

        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #000000;
            margin: 10px 0 3px 0;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .filter-subtitle {
            text-align: center;
            font-size: 10.5px;
            color: #000000;
            margin-bottom: 12px;
        }

        /* KPI Summary Cards */
        .kpi-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: separate;
            border-spacing: 6px 0;
            page-break-inside: avoid;
        }

        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #000000;
            padding: 6px 8px;
            text-align: center;
        }

        .kpi-title {
            font-size: 8.5px;
            text-transform: uppercase;
            font-weight: bold;
            color: #000000;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 14px;
            font-weight: bold;
            color: #000000;
        }

        /* Main Data Table */
        .report-table {
            width: 100%;
            table-layout: fixed;
            word-wrap: break-word;
            border: 1px solid #000000;
            margin-bottom: 15px;
        }


        .report-table th,
        .report-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
        }

        .report-table th {
            background-color: #f2f2f2;
            color: #000000;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .font-bold {
            font-weight: bold;
        }

        .badge-pending {
            font-size: 9px;
            font-weight: bold;
            color: #b91c1c;
            text-transform: uppercase;
        }

        /* Footer Stamp */
        .footer-stamp {
            margin-top: 20px;
            width: 100%;
            page-break-inside: avoid;
        }

        .footer-stamp td {
            border: none;
            vertical-align: top;
            font-size: 10px;
        }

        .footer-line {
            border-top: 1px solid #000000;
            width: 180px;
            margin-top: 35px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
<table class="header-table">
    <tr>
        <td style="width: 15%; text-align: center;">
            <img src="{{ public_path('logo.png') }}" style="width: 75px; height: auto;">
        </td>
        <td style="width: 70%;" class="header-title">
            <h3>Republic of the Philippines</h3>
            <h2>PROVINCE OF DAVAO DEL SUR</h2>
            <h3>Provincial Government | Matti, City of Digos</h3>
            <h4>OFFICE OF THE PROVINCIAL PROCUREMENT MANAGEMENT OFFICER</h4>
        </td>
        <td style="width: 15%; text-align: right; font-size: 9px; color: #000000;">
            Date Generated:<br>
            <strong>{{ \Carbon\Carbon::now()->format('M d, Y') }}</strong>
        </td>
    </tr>
</table>

    <div class="doc-title">PURCHASE ORDERS PENDING PDF UPLOAD REPORT</div>
    <div class="filter-subtitle">
        @if(!empty($department) && $department !== 'all') Department / End User: <strong>{{ $department }}</strong> @else All Departments @endif
        &nbsp;|&nbsp;
        @if(!empty($supplier) && $supplier !== 'all') Supplier: <strong>{{ $supplier }}</strong> @else All Suppliers @endif
        &nbsp;|&nbsp;
        Generated: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}
    </div>

    <!-- KPI Cards -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 33%;">
                <div class="kpi-title">Total POs Pending PDF</div>
                <div class="kpi-value">{{ count($pos) }}</div>
            </td>
            <td class="kpi-card" style="width: 33%;">
                <div class="kpi-title">Filter Department</div>
                <div class="kpi-value" style="font-size: 11px; font-weight: normal;">{{ $department !== 'all' && !empty($department) ? $department : 'All Departments' }}</div>
            </td>
            <td class="kpi-card" style="width: 34%;">
                <div class="kpi-title">Filter Supplier</div>
                <div class="kpi-value" style="font-size: 11px; font-weight: normal;">{{ $supplier !== 'all' && !empty($supplier) ? $supplier : 'All Suppliers' }}</div>
            </td>
        </tr>
    </table>

    <!-- Main Data Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">PO Number</th>
                <th style="width: 15%;">PR Number</th>
                <th style="width: 25%;">Item / Particulars</th>
                <th style="width: 20%;">End User / Department</th>
                <th style="width: 20%;">Supplier Name</th>
                <th style="width: 10%;">PDF Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pos as $index => $po)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold">{{ $po->po_no ?? '-' }}</td>
                    <td class="text-center">{{ $po->pr_no ?? '-' }}</td>
                    <td>{{ $po->item ?? '-' }}</td>
                    <td>{{ $po->end_user ?? '-' }}</td>
                    <td>{{ $po->supplier ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge-pending">No PDF Uploaded</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px; font-style: italic;">
                        No Purchase Orders found without PDF uploads for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Signatures -->
    <table class="footer-stamp">
        <tr>
            <td style="width: 50%;">
                Noted by:<br>
                <div class="footer-line"></div><br>
                <strong>System Administrator</strong>
            </td>
        </tr>
    </table>

</body>

</html>
