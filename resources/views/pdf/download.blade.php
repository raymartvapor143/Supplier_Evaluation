<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
<title>
    Supplier Evaluation -
    {{ $evaluation->supplier_name ?? '' }}
    @if($evaluation->date_evaluation || $evaluation->created_at)
        -
        {{
            \Carbon\Carbon::parse($evaluation->date_evaluation ?? $evaluation->created_at)
                ->format('F d, Y h:i A')
        }}
    @endif
</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        h1, h2, h3, h4 { margin: 0; }
        h1 { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        h2 { font-size: 14px; font-weight: bold; margin-bottom: 5px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-bottom: 5px; background-color: #f3f4f6; padding: 5px; border-left: 4px solid #3b82f6; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #999; padding: 6px; vertical-align: top; }
        .table th { background-color: white; color: black; text-align: left; font-weight: bold; }
        .remarks { min-height: 50px; }
        .rating-box { background-color: #d1fae5; padding: 10px; margin-bottom: 5px; text-align: center; font-weight: bold; }
        .digital-auth img { width: 100px; height: 100px; object-fit: cover; border: 1px solid #ccc; }
        .auth-panel { background-color: #fff; border: 1px solid #ccc; border-radius: 12px; padding: 16px; }
        .auth-container { display: flex; flex-wrap: wrap; gap: 16px; }
        .auth-panel img { margin-top: 8px; }
    </style>
</head>
<body>
<!-- Header -->
<div class="header">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Left Logo -->
            <td style="width: 20%; text-align: center;">
                <img src="{{ public_path('logo.png') }}" style="width: 95px;">
            </td>

            <!-- Center Text -->
            <td style="width: 60%; text-align: center; line-height: 1.4;">
                <div style="font-size: 10px;">Republic of the Philippines</div>
                <div style="font-size: 13px; font-weight: bold; margin-top: 2px;">
                    PROVINCE OF DAVAO DEL SUR
                </div>
                <div style="font-size: 10px;">Provincial Government</div>
                <div style="font-size: 10px;">Matti, City of Digos</div>

                <!-- Title -->
                <div style="margin-top: 10px;">
                    <div style="font-size: 13px; font-weight: bold;">
                        SUPPLIER'S EVALUATION FORM
                    </div>
                    <div style="font-size: 9px;">
                        Performance Assessment & Rating System
                    </div>
                </div>
            </td>

            <!-- Right Spacer (optional for symmetry) -->
            <td style="width: 20%;"></td>
        </tr>
    </table>
</div>
<br>



    <!-- Instructions -->
    <!-- <div class="section">
        <div class="section-title">INSTRUCTIONS</div>
        <ol style="padding-left: 20px; margin-top: 5px;">
            <li>Check the box which corresponds to the supplier's performance based on the Purchase Order/Contract listed above.</li>
            <li>In the Remarks / Specific Comments Column, provide details of any incidents or deviations. Use additional sheet if necessary.</li>
            <li>When multiple POs are added, each evaluation is calculated separately for the overall rating.</li>
        </ol>
    </div> -->

    <!-- Evaluation Details -->
    <div class="section">
        <div class="section-title">Evaluation Details</div>
        <table class="table">
            <tr>
                <th>NAME OF SUPPLIER</th>
                <td>{{ $evaluation->supplier_name ?? '' }}</td>
                <th>Purchase Order / Contract No.</th>
                <td>{{ $evaluation->po_no ?? '' }}</td>
            </tr>
            <tr>
                <th>Date of Evaluation</th>
                <td>
                  {{ $evaluation->date_evaluation
                      ? \Carbon\Carbon::parse($evaluation->date_evaluation)->format('F j, Y')
                      : ''
                  }}
                </td>
                <th>Covered Period</th>
                <td>{{ $evaluation->covered_period ?? '' }}</td>
            </tr>
            <tr>
                <th>Evaluated by (Office Name)</th>
                <td colspan="3">{{ $evaluation->office->name ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- Evaluation Criteria Table -->
    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>EVALUATION CRITERIA</th>
                    <th>REMARKS / SPECIFIC COMMENTS</th>
                </tr>
            </thead>
<tbody>
    @php
        $criteriaWeightMap = [
            1 => [4 => 20, 3 => 15, 2 => 10, 1 => 5],         // PRICE
            2 => [4 => 30, 3 => 22.5, 2 => 15, 1 => 7.5],     // QUALITY
            3 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25], // CUSTOMER CARE
            4 => [4 => 25, 3 => 18.75, 2 => 12.5, 1 => 6.25], // DELIVERY
        ];
        $ratingDescriptions = [
            1 => [
                1 => "Bid amount is higher than the prevailing market price against the brand/services delivered.",
                2 => "Goods delivered with recurring or significant damages, defects, or workmanship issues, affecting functionality and functionality.",
                3 => "If any three (3) of the details given in item #4 is lacking.",
                4 => "Goods / Services delivered, eleven (11) or more days after the expiration of the delivery period."
            ],
            2 => [
                1 => "Some mismatch between bid amount and brand/services delivered; notably higher than market range. Moderately Reasonable ",
                2 => "Goods delivered in accordance with specs but of low quality. ",
                3 => "If any two (2) of the customer care details are lacking. ",
                4 => "Goods/Services delivered six (6) to ten (10) days after expiration. "
            ],
            3 => [
                1 => "Bid amount generally aligns with brand/services delivered; Minor discrepancies in pricing but still within acceptable market range.; and No significant cost or overpricing based on brand/services delivered.",
                2 => "Goods delivered in accordance with specifications, with minor damages, defects, or workmanship issues, which were immediately corrected without affecting functionality or project timeline.",
                3 => "If one (1) of the customer care details is lacking. ",
                4 => "Goods / Services delivered, One (1) to Five (5) days after the expiration of the delivery period "
            ],
            4 => [
                1 => "Bid amount is reasonable based on the brand/services delivered; Pricing is consistent with current market rates (brand or market scooping / historical data); No competitive. ",
                2 => "Goods delivered according to specifications, and acceptable quality ",
                3 => "Accessible and easy to contact, responsive to inquiries / complaints, adaptable to certain needs of the end-user and has competent staff to handle end-user's concerns.",
                4 => " Goods / Services delivered on Time "
            ],
        ];
        $overallScore = 0;
    @endphp

    @foreach($evaluation->criteriaScores as $score)
        @php
            $criteriaId = $score->criteria_id;
            $rating = $score->number_rating ?? 0;
            $weighted = $criteriaWeightMap[$criteriaId][$rating] ?? 0;
            $overallScore += $weighted;
            $criteriaName = $score->criteria->criteria_name ?? 'N/A';
            $description = $ratingDescriptions[$rating][$criteriaId] ?? '';
        @endphp
        <tr>
            <td>
                <strong>{{ $criteriaName }}</strong>
                ({{ max($criteriaWeightMap[$criteriaId]) }}%)
                <br>
                <strong>Rate:&nbsp;&nbsp;{{ number_format($weighted, 2) }}%</strong>
                <br>
                <em>{{ $description }}</em>
            </td>
            <td class="remarks">
                {{ $score->remarks ?? '' }}
            </td>
        </tr>
    @endforeach
</tbody>
        </table>
    </div>

    <!-- Overall Rating -->
    <div class="section">
        @php
            $status = $overallScore >= 60 ? 'Passed' : 'Failed';
        @endphp
        <div class="rating-box">
            <strong>{{ number_format($overallScore,2) }}%</strong> - {{ $status }}
        </div>
        <!-- <div style="display:flex; justify-content: space-around; margin-top:10px;">
            <div style="text-align:center;">
                <div>Passing Rate</div>
                <div>60%</div>
            </div>
        </div> -->
    </div>


@php
use App\Models\User;
@endphp
   @php

        /*
        |--------------------------------------------------------------------------
        | PREPARED BY
        |--------------------------------------------------------------------------
        */

        $preparedBy = $evaluation->digitalApprovals
            ->firstWhere('role', 'Prepared by');

        $preparedSig = null;

        if (
            $preparedBy &&
            $preparedBy->signer &&
            $preparedBy->signer->signature
        ) {

            $path = storage_path('app/private/' . $preparedBy->signer->signature);

            if (file_exists($path)) {
                $preparedSig = $path;
            }

        }

        /*
        |--------------------------------------------------------------------------
        | HEAD DIGITAL APPROVAL
        |--------------------------------------------------------------------------
        */

        $headApproval = $evaluation->digitalApprovals
            ->first(function ($item) {
                return in_array(strtolower(trim($item->role ?? '')), ['head', 'office head']);
            });

        /*
        |--------------------------------------------------------------------------
        | REPRESENTATIVE STAFF DIGITAL APPROVAL
        |--------------------------------------------------------------------------
        */

        $representativeApproval = $evaluation->digitalApprovals
            ->first(function ($item) {
                return in_array(strtolower(trim($item->role ?? '')), ['presentative_staff', 'representative_staff', 'representative staff', 'presentative staff']);
            });

        $repSig = null;

        if ($representativeApproval) {
            if ($representativeApproval->signer && $representativeApproval->signer->signature) {
                $path = storage_path('app/private/' . $representativeApproval->signer->signature);
                if (file_exists($path)) {
                    $repSig = $path;
                }
            } elseif ($representativeApproval->signed_by) {
                $signerUser = User::find($representativeApproval->signed_by);
                if ($signerUser && $signerUser->signature) {
                    $path = storage_path('app/private/' . $signerUser->signature);
                    if (file_exists($path)) {
                        $repSig = $path;
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | OFFICE HEAD USER
        |--------------------------------------------------------------------------
        */

        $officeHead = User::where('role', 'head')
            ->where('office_id', $evaluation->office_id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | DETERMINE NAME & DESIGNATION
        |--------------------------------------------------------------------------
        */

        if ($headApproval) {

            $headName = $headApproval->full_name;
            $headDesignation = $headApproval->designation;

        } elseif ($officeHead) {

            $headName = $officeHead->name;
            $headDesignation = $officeHead->designation;

        } else {

            $headName = optional($evaluation->office)->head;
            $headDesignation = optional($evaluation->office)->designation;

        }

        /*
        |--------------------------------------------------------------------------
        | DETERMINE SIGNATURE
        |--------------------------------------------------------------------------
        */

        $headSig = null;

        if (
            $headApproval &&
            $headApproval->signer &&
            $headApproval->signer->signature
        ) {

            $path = storage_path('app/private/' . $headApproval->signer->signature);

            if (file_exists($path)) {
                $headSig = $path;
            }

            $headNote = 'Electronically approved by Office Head';

        }
        elseif (
            $representativeApproval &&
            $representativeApproval->signer &&
            $representativeApproval->signer->signature
        ) {

            $path = storage_path('app/private/' . $representativeApproval->signer->signature);

            if (file_exists($path)) {
                $headSig = $path;
            }

            $headNote = 'Electronically signed by Representative Staff on behalf of the Office Head';

        }
        else {

            $headNote = 'Office Head';

        }

    @endphp
<div class="section">
    <div class="section-title">Digital Authorization</div>

    <table class="sig-wrap">

        <tr>

            <!-- ================= PREPARED ================= -->
            <td class="sig-card">
                <div class="sig-title-tag">PREPARED BY</div>

                <div class="sig-image">
                    @if($preparedSig)
                        <img src="{{ $preparedSig }}">
                    @endif
                </div>

                <div class="sig-line"></div>

                <div class="sig-name">
                    {{ $preparedBy->full_name ?? '-' }}
                </div>

                <div class="sig-role">
                    {{ $preparedBy->designation ?? '-' }}
                </div>

                <div class="sig-note">
                    Electronically signed & verified
                </div>

                @if($preparedBy && $preparedBy->signer && $preparedBy->signer->updated_at)
<div class="sig-note">
    Signed:
    {{ $preparedBy->created_at->format('F d, Y h:i A') }}
</div>
                @endif
            </td>

            <!-- ================= HEAD ================= -->
            <td class="sig-card">

                <div class="sig-title-tag">HEAD AUTHORIZATION</div>

                <div class="sig-image">
                    @if($headApproval && $headSig)
                        <img src="{{ $headSig }}">
                    @endif
                </div>

                <div class="sig-line"></div>

                <div class="sig-name">
                    {{ $headName ?? '-' }}
                </div>

                <div class="sig-role">
                    {{ $headDesignation ?? '-' }}
                </div>

                <div class="sig-note">
                    {{ $headNote }}
                </div>
@if($headApproval && $headApproval->signer && $headApproval->signer->updated_at)
<div class="sig-note">
    Signed:
    {{ optional($headApproval->created_at)->format('F d, Y h:i A') }}
</div>
 @endif

            </td>

            <!-- ================= REPRESENTATIVE (OPTIONAL COLUMN) ================= -->
@if($representativeApproval)

<td class="sig-card">

    <div class="sig-title-tag" style="color:#059669;">
        REPRESENTATIVE STAFF
    </div>

    <div class="sig-image">
        @if($repSig)
            <img src="{{ $repSig }}">
        @endif
    </div>

    <div class="sig-line"></div>

    <div class="sig-name">
        {{ $representativeApproval->full_name ?? '-' }}
    </div>

    <div class="sig-role">
        {{ $representativeApproval->designation ?? '-' }}
    </div>

    <div class="sig-note">
        Electronically signed on behalf of the Office Head
    </div>

    @if($representativeApproval->created_at)
        <div class="sig-note">
            Signed:
            {{ $representativeApproval->created_at->format('F d, Y h:i A') }}
        </div>
    @endif

</td>

@endif

        </tr>

    </table>

    <div class="sig-footer">
        This document is electronically signed using a secure electronic signature system and is valid without a handwritten signature.
    </div>
</div>

<style>
.sig-wrap{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    margin-top:10px;
}

/* dynamic columns */
.sig-card{
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:10px;
    text-align:center;
    background:#fff;
    vertical-align:top;
}

/* LABEL */
.sig-title-tag{
    font-size:9px;
    font-weight:700;
    letter-spacing:.1em;
    color:#1e40af;
    margin-bottom:6px;
}

/* IMAGE */
.sig-image{
    height:45px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.sig-image img{
    max-height:45px;
    max-width:120px;
    object-fit:contain;
}

/* LINE */
.sig-line{
    border-bottom:1px solid #111827;
    width:80%;
    margin:4px auto;
}

/* TEXT */
.sig-name{
    font-size:11px;
    font-weight:700;
}

.sig-role{
    font-size:10px;
    color:#374151;
}

.sig-note{
    font-size:9px;
    color:#6b7280;
    margin-top:4px;
}

/* FOOTER */
.sig-footer{
    text-align:center;
    margin-top:8px;
    font-size:9px;
    color:#6b7280;
    font-style:italic;
}

/* PDF SAFE */
@media print {
    .sig-wrap{
        page-break-inside: avoid;
    }
}
</style>

</body>
</html>
