<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Supplier Semester Evaluation Summary Report</title>

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
            font-size: 15px;
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

        /* KPI Summary Cards - B&W */
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
            font-size: 9px;
            color: #000000;
            text-transform: uppercase;
            font-weight: bold;
        }

        .kpi-value {
            font-size: 14px;
            font-weight: bold;
            color: #000000;
            margin-top: 2px;
        }

        /* Main Data Table - Black & White */
        table.data-table {
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table.data-table th {
            background-color: #e5e7eb;
            color: #000000;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 6px 4px;
            border: 1px solid #000000;
            text-align: center;
        }

        table.data-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            text-align: center;
            font-size: 10px;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-left {
            text-align: left !important;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Rating Badges - Black & White outline */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #000000;
            background-color: #ffffff;
            color: #000000;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #000000;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #000000;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

<!-- Header -->
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

<div class="doc-title">Supplier Semester Evaluation Summary Report</div>
<div class="filter-subtitle">
    Department: <strong>{{ $department === 'all' ? 'All Departments' : $department }}</strong> &nbsp;|&nbsp; 
    Period Year: <strong>{{ $year === 'all' ? 'All Years' : $year }}</strong>
</div>

@php
    $totalSuppliers = count($data);
    $sem1TotalPos = collect($data)->sum('sem1_count');
    $sem2TotalPos = collect($data)->sum('sem2_count');
    $grandAvg = $totalSuppliers > 0 ? collect($data)->whereNotNull('overall_avg')->avg('overall_avg') : 0;
@endphp

<!-- KPI Summary Section -->
<table class="kpi-table">
    <tr>
        <td style="width: 25%;">
            <div class="kpi-card">
                <div class="kpi-title">Total Suppliers</div>
                <div class="kpi-value">{{ $totalSuppliers }}</div>
            </div>
        </td>
        <td style="width: 25%;">
            <div class="kpi-card">
                <div class="kpi-title">1st Sem POs (Jan - Jun)</div>
                <div class="kpi-value">{{ $sem1TotalPos }}</div>
            </div>
        </td>
        <td style="width: 25%;">
            <div class="kpi-card">
                <div class="kpi-title">2nd Sem POs (Jul - Dec)</div>
                <div class="kpi-value">{{ $sem2TotalPos }}</div>
            </div>
        </td>
        <td style="width: 25%;">
            <div class="kpi-card">
                <div class="kpi-title">Overall Average Score</div>
                <div class="kpi-value">{{ number_format($grandAvg ?? 0, 2) }}%</div>
            </div>
        </td>
    </tr>
</table>

<!-- Main Semester Table -->
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 30%;" class="text-left">Supplier Name</th>
            <th style="width: 22%;">1st Semester (Jan - Jun)</th>
            <th style="width: 22%;">2nd Semester (Jul - Dec)</th>
            <th style="width: 11%;">Overall Avg</th>
            <th style="width: 10%;">Rating</th>
        </tr>
    </thead>
    <tbody>
    @if(empty($data) || count($data) === 0)
        <tr>
            <td colspan="6" style="padding: 12px; text-align: center;">No evaluation data available for the selected filters.</td>
        </tr>
    @else
        @foreach($data as $index => $item)
            @php
                $sem1 = $item['sem1_avg'] !== null ? number_format($item['sem1_avg'], 2) . '% (' . $item['sem1_count'] . ' POs)' : 'N/A';
                $sem2 = $item['sem2_avg'] !== null ? number_format($item['sem2_avg'], 2) . '% (' . $item['sem2_count'] . ' POs)' : 'N/A';
                $overall = $item['overall_avg'] !== null ? number_format($item['overall_avg'], 2) . '%' : 'N/A';
                
                $ratingLabel = 'No Rating';
                if ($item['overall_avg'] !== null) {
                    if ($item['overall_avg'] >= 90) {
                        $ratingLabel = 'Outstanding';
                    } elseif ($item['overall_avg'] >= 80) {
                        $ratingLabel = 'Satisfactory';
                    } elseif ($item['overall_avg'] >= 75) {
                        $ratingLabel = 'Fair';
                    } else {
                        $ratingLabel = 'Needs Improvement';
                    }
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left font-bold">{{ $item['supplier'] }}</td>
                <td>{{ $sem1 }}</td>
                <td>{{ $sem2 }}</td>
                <td class="font-bold">{{ $overall }}</td>
                <td>
                    <span class="badge">{{ $ratingLabel }}</span>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<!-- Footer -->
<div class="footer">
    This report is official and computer-generated by the Supplier Evaluation System | Province of Davao del Sur.<br>
    Confidential - For official procurement management and evaluation reference only.
</div>

</body>
</html>
