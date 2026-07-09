<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Supplier Evaluation Summary</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border: none;
        }

        .header td {
            border: none;
            vertical-align: middle;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin: 20px 0;
            text-decoration: underline;
        }

        h2 {
            margin-top: 25px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #e5e7eb;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 8px;
            text-align: center;
            font-size: 10px;
        }

        .auth-text {
            font-style: italic;
        }
    </style>

</head>

<body>

<div class="header">

    <table>

        <tr>

            <td style="width:20%;text-align:center;">
                <img src="{{ public_path('logo.png') }}" style="width:95px;">
            </td>

            <td style="width:60%;text-align:center;">

                <div>Republic of the Philippines</div>

                <div style="font-size:16px;font-weight:bold;">
                    PROVINCE OF DAVAO DEL SUR
                </div>

                <div>Provincial Government</div>

                <div>Matti, City of Digos</div>

                <div style="margin-top:8px;font-weight:bold;">
                    OFFICE OF THE PROVINCIAL PROCUREMENT MANAGEMENT OFFICER
                </div>

            </td>

            <td style="width:20%;"></td>

        </tr>

    </table>

</div>

<h1>Supplier Evaluation Summary Report</h1>

<table>

    <thead>

        <tr>

            <th>Department</th>
            <th>Supplier</th>
            <th>Total Evaluations</th>
            <th>Overall Average Score</th>

        </tr>

    </thead>

    <tbody>

    @php
        $grandAverage = 0;
    @endphp

    @foreach($summary as $item)

        @php
            $grandAverage += $item['average_score'];
        @endphp

        <tr>

            <td>{{ $item['department'] }}</td>

            <td>{{ $item['supplier'] }}</td>

            <td>{{ $item['total_evaluations'] }}</td>

            <td>{{ number_format($item['average_score'],2) }}%</td>

        </tr>

    @endforeach

    </tbody>

</table>

{{-- <p style="margin-top:15px;text-align:right;font-weight:bold;">

Overall Average Score:

{{ count($summary) ? number_format($grandAverage / count($summary),2) : '0.00' }}%

</p> --}}

@php

$hasRemarks = collect($summary)->pluck('remarks')->flatten()->isNotEmpty();

@endphp

@if($hasRemarks)

<h2>Remarks Summary</h2>

<table>

    <thead>

        <tr>

            <th style="width:30%">Department / Supplier</th>

            <th>Remarks</th>

        </tr>

    </thead>

    <tbody>

    @foreach($summary as $item)

        @php

            $rowspan = count($item['remarks']);

        @endphp

        @if($rowspan)

            @foreach($item['remarks'] as $index=>$remark)

                <tr>

                    @if($index==0)

                    <td rowspan="{{ $rowspan }}">

                        <strong>{{ $item['department'] }}</strong><br>

                        {{ $item['supplier'] }}

                    </td>

                    @endif

                    <td style="text-align:left;">

                        • {{ $remark['criteria'] }} - {{ $remark['remarks'] }}

                    </td>

                </tr>

            @endforeach

        @endif

    @endforeach

    </tbody>

</table>

@endif

<div class="footer">

This document is confidential and intended for official use only.

<br><br>

{{-- <div class="auth-text">

This Supplier Evaluation is authenticated and authorized through computer-generated facial recognition technology, which serves as an official signature in lieu of a handwritten signature.

</div> --}}

</div>

</body>

</html>
