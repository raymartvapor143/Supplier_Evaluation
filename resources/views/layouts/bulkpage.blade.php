<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bulk Evaluation System</title>
  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

  <script src="{{asset('script/block.js')}}"></script>
  <style>
    :where([class^="ri-"])::before {
      content: "\f3c2"
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box
    }

    body {
      font-family: 'Inter', sans-serif;
      overflow-x: hidden
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in-out
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    .slide-in-left {
      animation: slideInLeft 0.4s ease-out
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%)
      }

      to {
        transform: translateX(0)
      }
    }

    .slide-in-right {
      animation: slideInRight 0.4s ease-out
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%)
      }

      to {
        transform: translateX(0)
      }
    }

    .scale-in {
      animation: scaleIn 0.3s ease-out
    }

    @keyframes scaleIn {
      from {
        transform: scale(0.9);
        opacity: 0
      }

      to {
        transform: scale(1);
        opacity: 1
      }
    }

    .checkbox-bounce {
      animation: checkboxBounce 0.4s ease-out
    }

    @keyframes checkboxBounce {
      0% {
        transform: scale(1)
      }

      50% {
        transform: scale(1.2)
      }

      100% {
        transform: scale(1)
      }
    }

    .shimmer {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite
    }

    @keyframes shimmer {
      0% {
        background-position: 200% 0
      }

      100% {
        background-position: -200% 0
      }
    }

    .custom-radio {
      appearance: none;
      width: 1.25rem;
      height: 1.25rem;
      border: 2px solid #d1d5db;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative
    }

    .custom-radio:checked {
      border-color: #2563eb;
      background: #2563eb
    }

    .custom-radio:checked::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 0.5rem;
      height: 0.5rem;
      background: white;
      border-radius: 50%;
      animation: radioCheck 0.3s ease-out
    }

    @keyframes radioCheck {
      from {
        transform: translate(-50%, -50%) scale(0)
      }

      to {
        transform: translate(-50%, -50%) scale(1)
      }
    }

    .custom-checkbox {
      appearance: none;
      width: 1.25rem;
      height: 1.25rem;
      border: 2px solid #d1d5db;
      border-radius: 0.25rem;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative
    }

    .custom-checkbox:checked {
      border-color: #2563eb;
      background: #2563eb
    }

    .custom-checkbox:checked::after {
      content: '\2713';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 0.875rem;
      font-weight: bold;
      animation: checkmark 0.3s ease-out
    }

    @keyframes checkmark {
      from {
        transform: translate(-50%, -50%) scale(0) rotate(-45deg)
      }

      to {
        transform: translate(-50%, -50%) scale(1) rotate(0deg)
      }
    }

    .evaluation-item {
      transition: all 0.3s ease;
      cursor: pointer
    }

    .evaluation-item:hover {
      transform: translateX(4px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08)
    }

    .evaluation-item.active {
      background: #eff6ff;
      border-left: 4px solid #2563eb
    }

    .evaluation-item.selected {
      background: #f0f9ff;
      border: 2px solid #2563eb
    }

    .authorization-content {
      transition: all 0.3s ease;
      overflow: hidden;
      max-height: 0
    }

    .authorization-toggle {
      transition: all 0.3s ease;
      cursor: pointer;
      background: none;
      border: none;
      text-align: left;
      width: 100%
    }

    .btn-primary {
      background: #2563eb;
      color: white;
      transition: all 0.3s ease
    }

    .btn-primary:hover {
      background: #1d4ed8;
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3)
    }

    .btn-secondary {
      background: #f3f4f6;
      color: #374151;
      transition: all 0.3s ease
    }

    .btn-secondary:hover {
      background: #e5e7eb;
      transform: translateY(-2px)
    }

    .signature-linked {
      border: 2px solid #10b981;
      background: #f0fdf4
    }

    .signature-preview {
      width: 100%;
      height: 4rem;
      border: 1px dashed #d1d5db;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 0.75rem;
      background: #fafafa
    }

    .modal-backdrop {
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px)
    }

    .modal-content {
      animation: modalSlideUp 0.4s ease-out
    }

    @keyframes modalSlideUp {
      from {
        transform: translateY(100px);
        opacity: 0
      }

      to {
        transform: translateY(0);
        opacity: 1
      }
    }

    .toast {
      position: fixed;
      top: 1.5rem;
      right: 1.5rem;
      padding: 1rem 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      z-index: 9999;
      animation: toastSlide 0.4s ease-out
    }

    @keyframes toastSlide {
      from {
        transform: translateX(400px);
        opacity: 0
      }

      to {
        transform: translateX(0);
        opacity: 1
      }
    }

    .toast.success {
      background: #10b981;
      color: white
    }

    .toast.error {
      background: #ef4444;
      color: white
    }

    .progress-bar {
      height: 0.25rem;
      background: #e5e7eb;
      border-radius: 9999px;
      overflow: hidden
    }

    .progress-fill {
      height: 100%;
      background: #2563eb;
      transition: width 0.5s ease
    }

    textarea {
      resize: vertical;
      min-height: 4rem
    }

    .floating-label {
      position: relative
    }

    .floating-label input,
    .floating-label textarea,
    .floating-label select {
      padding-top: 1.5rem;
      padding-bottom: 0.5rem
    }

    .floating-label label {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      transition: all 0.3s ease;
      pointer-events: none;
      color: #6b7280;
      font-size: 1rem
    }

    .floating-label input:focus~label,
    .floating-label input:not(:placeholder-shown)~label,
    .floating-label textarea:focus~label,
    .floating-label textarea:not(:placeholder-shown)~label,
    .floating-label select:focus~label,
    .floating-label select:not([value=""])~label {
      top: 0.75rem;
      font-size: 0.75rem;
      color: #2563eb;
      transform: translateY(0)
    }

    .sidebar-collapsed {
      width: 0;
      overflow: hidden
    }

    @media(max-width:768px) {
      .mobile-drawer {
        transform: translateY(-100%);
        transition: transform 0.3s ease
      }

      .mobile-drawer.open {
        transform: translateY(0)
      }

      @media(min-width:1024px) {
        .mobile-drawer {
          transform: translateY(0) !important
        }
      }
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0
    }

    input[type="number"] {
      -moz-appearance: textfield
    }

    .progress-fill {
    transition: width 0.3s ease;
}



@keyframes resultPop {

    0% {
        opacity: 0;
        transform: scale(.75) translateY(20px);
    }

    70% {
        opacity: 1;
        transform: scale(1.03) translateY(0);
    }

    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.result-show {
    animation: resultPop .5s ease-out;
}


  </style>
</head>
<!-- Bulk Evaluation Reminder -->
<div id="bulkReminderModal"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden animate-fadeIn">

        <!-- Header -->
        <div class="flex items-center gap-4 px-6 py-5 bg-yellow-50 border-b">
            <div class="w-14 h-14 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="ri-alert-line text-3xl text-yellow-600"></i>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    Important Reminder
                </h2>
                <p class="text-sm text-gray-600">
                    Please read before using the Bulk Evaluation feature.
                </p>
            </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-6 text-gray-700 leading-7">

            <p>
                <strong>Bulk Evaluation</strong> applies a single evaluation result to
                <strong>all selected purchase orders</strong> of the chosen supplier.
            </p>

            <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                <p class="font-medium text-blue-800 mb-2">
                    The following information will be applied to every selected purchase order:
                </p>

                <ul class="list-disc list-inside text-blue-700 space-y-1">
                    <li>Evaluation scores</li>
                    <li>Remarks</li>
                    <li>Comments</li>
                    <li>Digital signature</li>
                </ul>
            </div>

            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">
                <p class="text-red-700 font-semibold">
                    Ensure that all selected purchase orders are intended to receive the
                    <strong>same evaluation</strong> before submitting.
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
            <button
                onclick="document.getElementById('bulkReminderModal').style.display='none'"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Got it
            </button>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('bulkReminderModal');

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
});
</script>
<body class="bg-gray-50">
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50 fade-in">
    <div class="flex items-center justify-between px-6 py-4">
      <div class="flex items-center gap-4">
        <button id="menuToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-lg transition">
          <i class="ri-menu-line ri-xl"></i>
        </button>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-lg">
            <i class="ri-file-list-3-line ri-xl"></i>
          </div>
          <h1 class="text-2xl font-bold text-gray-900">Bulk Evaluation System</h1>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <div class="hidden md:flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg">
          <i class="ri-checkbox-circle-fill ri-lg text-green-600"></i>
          <span class="text-sm font-medium text-green-700">E-Signature Active</span>
        </div>
        {{-- <button class="hidden md:flex items-center gap-2 px-4 py-2 btn-secondary rounded-lg whitespace-nowrap !rounded-button">
          <i class="ri-save-line ri-lg"></i>
          <span class="text-sm font-medium">Save Draft</span>
        </button> --}}
        {{-- <button class="hidden md:flex items-center gap-2 px-4 py-2 btn-secondary rounded-lg whitespace-nowrap !rounded-button">
          <i class="ri-download-line ri-lg"></i>
          <span class="text-sm font-medium">Export</span>
        </button> --}}
        {{-- <button class="w-10 h-10 flex items-center justify-center btn-secondary rounded-lg">
          <i class="ri-settings-3-line ri-xl"></i>
        </button> --}}
        {{-- <div class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full cursor-pointer">
          <span class="text-sm font-semibold">JD</span>
        </div> --}}
      </div>
    </div>
  </header>
  <div class="flex flex-col lg:flex-row h-auto lg:h-[calc(100vh-73px)]">



<aside id="sidebar"
  class="hidden w-full lg:w-80 bg-white border-b lg:border-b-0 lg:border-r border-gray-200
         flex flex-col mobile-drawer lg:relative fixed top-[73px] lg:top-0 inset-x-0 lg:inset-y-0
         left-0 z-40 transition-all duration-300 max-h-[50vh] lg:max-h-none
         -translate-y-full lg:translate-y-0">

  <!-- HEADER (fixed, not scrollable) -->
  <div class="p-4 border-b border-gray-200 shrink-0">

    <div class="flex items-center justify-between mb-4">

      <h2 class="text-lg font-semibold text-gray-900">
        Evaluations
        <span class="ml-2 text-sm font-medium text-gray-500">
          ({{ $evaluations->count() }})
        </span>
      </h2>

      <!-- ACTION BUTTONS -->
      <div class="flex items-center gap-2">

        <button onclick="openSupplierModal()"
          class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">
          + Add
        </button>

        <button onclick="removeSelectedEvaluations()"
          class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-600 text-white hover:bg-red-700">
          Remove All
        </button>

      </div>
    </div>

    <!-- SEARCH -->
    <div class="relative">
      <i class="ri-search-line ri-lg absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
      <input type="text" placeholder="Search evaluations..."
        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm
               focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
    </div>

  </div>

  <!-- SCROLLABLE CONTENT AREA (ONLY ONE SCROLL CONTAINER) -->
  <div class="flex-1 overflow-y-auto p-4 space-y-2">

    @forelse ($evaluations as $evaluation)

      <div class="evaluation-item p-4 bg-white border border-gray-200 rounded-lg cursor-pointer relative"
           data-id="{{ $evaluation->id }}"
           data-supplier="{{ $evaluation->supplier_name }}"
           data-po="{{ $evaluation->po_no }}"
           data-date="{{ optional($evaluation->date_evaluation)->format('Y-m-d') }}"
           data-coveredperiod="{{ $evaluation->covered_period }}"
           data-office-id="{{ $evaluation->office_id }}"
           data-office-name="{{ $evaluation->office->name ?? '' }}">

        <!-- REMOVE BUTTON -->
        <button type="button"
                class="absolute top-2 right-2 text-gray-400 hover:text-red-600"
                onclick="removeEvaluation(event, '{{ $evaluation->id }}')">
          <i class="ri-close-circle-line text-lg"></i>
        </button>

        <div class="flex items-start gap-3">

          <input type="checkbox"
                 class="custom-checkbox mt-1 batch-checkbox hidden">

          <div class="flex-1 min-w-0">

            <h3 class="font-semibold text-gray-900 truncate">
              {{ $evaluation->supplier_name }}
            </h3>

            <p class="text-sm text-gray-600 mt-1">
              {{ $evaluation->po_no }}
            </p>

            <div class="flex items-center gap-2 mt-2">

              <span class="text-xs text-gray-500">
                {{ optional($evaluation->date_evaluation)->format('M d, Y') }}
              </span>

              @php $status = strtolower($evaluation->status); @endphp

              <span class="px-2 py-1 text-xs font-medium rounded
                @if($status === 'submitted') bg-green-100 text-green-800
                @elseif($status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($status === 'head review') bg-yellow-100 text-yellow-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($evaluation->status) }}
              </span>

            </div>
          </div>
        </div>
      </div>

    @empty

      <div class="text-sm text-gray-500 text-center py-6">
        No evaluations found.
      </div>

    @endforelse

  </div>

</aside>




    <main class="flex-1 overflow-y-auto w-full">
      <div class="max-w-5xl mx-auto p-4 lg:p-6 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 scale-in">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-primary rounded-lg">
              <i class="ri-file-text-line ri-xl"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>
          </div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

  <!-- COVERED PERIOD (INPUT TYPE FIXED) -->
  <div class="floating-label">
    <input disabled
      type="text"
      id="coveredPeriod"
      placeholder=" "
      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900"
    >
    <label>Covered Period *</label>
  </div>

  <div class="floating-label">
    <input disabled type="text" id="supplierName"
      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
    <label>Name of Supplier *</label>
  </div>

  <div class="floating-label">
    <input disabled type="date" id="evaluationDate"
      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
    <label>Evaluation Date *</label>
  </div>

  <div class="floating-label">
    <input disabled type="text" id="poNumber"
      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
    <label>Purchase Order No. *</label>
  </div>

  <div class="floating-label md:col-span-2">
    <input disabled type="text"
       id="officeName"
      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
    <label>Evaluated by (Office Name) *</label>
    <input type="hidden" id="officeId">
  </div>

</div>


        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 flex items-center justify-center bg-purple-100 text-purple-600 rounded-lg">
                <i class="ri-star-line ri-xl"></i>
              </div>
              <h2 class="text-xl font-bold text-gray-900">Evaluation Criteria</h2>
            </div>


<div class="flex items-center gap-3">

  <span class="text-sm text-gray-600">Progress:</span>

  <!-- Progress bar -->
  <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
    <div class="progress-fill h-full bg-red-500 transition-all duration-300"></div>
  </div>

  <!-- Percentage -->
  <span class="text-sm font-semibold" id="progressText">0%</span>

  <!-- Status badge -->
  <span id="progressStatus"
        class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-600">
    FAIL
  </span>

</div>


          </div>
          <div class="space-y-4">
<div class="border border-gray-200 rounded-lg overflow-hidden">

  <!-- HEADER -->
  <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition criteria-toggle">
    <div class="flex items-center gap-3">
      <span class="w-8 h-8 flex items-center justify-center bg-primary text-black rounded-full text-sm font-bold">A</span>
      <span class="font-semibold text-gray-900">Price</span>
    </div>
    <i class="ri-arrow-down-s-line ri-xl text-gray-600 transition-transform"></i>
  </button>

  <!-- CONTENT -->
  <div class="criteria-content p-4 space-y-4">

    <div class="mb-3">
      <div class="font-medium mb-2">A. PRICE (20%)</div>

      <div class="space-y-2 text-xs">

        <!-- 4 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaA" value="4" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>4 - Highly Reasonable <span class="bg-yellow-200 px-1 rounded">(20%)</span></strong><br>
            • The bid amount is highly reasonable based on the brand, specifications, and services delivered.<br>
            • Pricing is consistent with current market rates supported by historical data or market benchmarking.<br>
            • There is no indication of overpricing or lack of competition.
          </span>
        </label>

        <!-- 3 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaA" value="3" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>3 - Reasonable <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong><br>
            • The bid amount generally aligns with the brand, specifications, and services delivered.<br>
            • Minor pricing variations are observed but remain within acceptable market range.<br>
            • No significant evidence of overpricing or cost inefficiency.
          </span>
        </label>

        <!-- 2 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaA" value="2" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>2 - Moderately Reasonable <span class="bg-yellow-200 px-1 rounded">(10%)</span></strong><br>
            • The bid amount shows some mismatch with the brand, specifications, and services delivered.<br>
            • The pricing is noticeably higher than prevailing market rates for similar items or services.<br>
            • There are concerns regarding cost efficiency.
          </span>
        </label>

        <!-- 1 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaA" value="1" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>1 - Not Reasonable <span class="bg-yellow-200 px-1 rounded">(5%)</span></strong><br>
            • The bid amount is significantly higher than prevailing market prices for similar items or services.<br>
            • There is clear indication of overpricing or lack of competitiveness.<br>
            • The pricing is not justifiable based on delivered value.
          </span>
        </label>

      </div>
    </div>

    <!-- REMARKS (UNCHANGED) -->
    <div class="floating-label">
      <textarea
        placeholder=" "
        id="remarksA"
        rows="3"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 text-sm resize-none overflow-auto max-h-32"
      ></textarea>
      <label>Remarks</label>
    </div>

    <div class="text-xs text-gray-500 text-right">
      Character count: 0
    </div>

  </div>
</div>
          </div>
          <br>


<div class="border border-gray-200 rounded-lg overflow-hidden">

  <!-- HEADER -->
  <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition criteria-toggle">
    <div class="flex items-center gap-3">
      <span class="w-8 h-8 flex items-center justify-center bg-primary text-black rounded-full text-sm font-bold">B</span>
      <span class="font-semibold text-gray-900">QUALITY / SERVICE LEVEL</span>
    </div>
    <i class="ri-arrow-down-s-line ri-xl text-gray-600 transition-transform"></i>
  </button>

  <!-- CONTENT -->
  <div class="criteria-content p-4 space-y-4">

    <div class="mb-3">
      <div class="font-medium mb-2">B. QUALITY / SERVICE LEVEL (30%)</div>

      <div class="space-y-2 text-xs">

        <!-- 4 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaB" value="4" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>
              4 - Goods delivered according to specifications and acceptable delivery performance
              <span class="bg-yellow-200 px-1 rounded">(30%)</span>
            </strong>
          </span>
        </label>

        <!-- 3 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaB" value="3" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>
              3 - Goods delivered in accordance with specifications with minor delays or issues, which were immediately corrected without affecting functionality or project timeline
              <span class="bg-yellow-200 px-1 rounded">(22.5%)</span>
            </strong>
          </span>
        </label>

        <!-- 2 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaB" value="2" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>
              2 - Goods delivered in accordance with specifications but with fair to low delivery performance
              <span class="bg-yellow-200 px-1 rounded">(15%)</span>
            </strong>
          </span>
        </label>

        <!-- 1 -->
        <label class="flex items-start gap-2 p-3 border border-gray-300 rounded-lg hover:border-primary hover:bg-blue-50 transition cursor-pointer">
          <input type="radio" name="criteriaB" value="1" class="mt-1 w-5 h-5 flex-shrink-0">
          <span>
            <strong>
              1 - Goods delivered with recurring or significant delays or delivery issues affecting functionality and operations
              <span class="bg-yellow-200 px-1 rounded">(6.25%)</span>
            </strong>
          </span>
        </label>

      </div>
    </div>

    <!-- REMARKS -->
    <div class="floating-label">
      <textarea placeholder=" " id="remarksB"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 text-sm"></textarea>
      <label>Remarks</label>
    </div>

    <div class="text-xs text-gray-500 text-right">
      Character count: 0
    </div>

  </div>
</div>


          <br>

<div class="border border-gray-200 rounded-lg overflow-hidden">

  <!-- HEADER -->
  <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition criteria-toggle">
    <div class="flex items-center gap-3">
      <span class="w-8 h-8 flex items-center justify-center bg-primary text-black rounded-full text-sm font-bold">C</span>
      <span class="font-semibold text-gray-900">Customer Care / After Sales Service</span>
    </div>
    <i class="ri-arrow-down-s-line ri-xl text-gray-600 transition-transform"></i>
  </button>

  <!-- CONTENT -->
  <div class="criteria-content p-4 space-y-4">

    <div class="mb-3">
      <div class="font-medium mb-2">C. CUSTOMER CARE / AFTER SALES SERVICE (25%)</div>

      <div class="space-y-2 text-xs">

        <!-- 4 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaC" value="4" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Accessible and easy to contact, responsive to inquiries and complaints, adaptable to end-user needs, and supported by competent staff handling concerns efficiently
            <span class="bg-yellow-200 px-1 rounded">(25%)</span></div>

          </div>
        </label>

        <!-- 3 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaC" value="3" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              One (1) of the required service characteristics is lacking (accessibility, responsiveness, adaptability, or staff competence)
            <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></div>
          </div>
        </label>

        <!-- 2 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaC" value="2" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Two (2) of the required service characteristics are lacking (accessibility, responsiveness, adaptability, or staff competence)
            <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></div>
          </div>
        </label>

        <!-- 1 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaC" value="1" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Three (3) or more required service characteristics are lacking, resulting in poor customer support performance
            <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></div>
          </div>
        </label>

      </div>
    </div>

    <!-- REMARKS (UNCHANGED) -->
    <div class="floating-label">
      <textarea
        placeholder=" "
        id="remarksC"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 text-sm"
      ></textarea>
      <label>Remarks</label>
    </div>

    <div class="text-xs text-gray-500 text-right">
      Character count: 0
    </div>

  </div>
</div>

          <br>


<div class="border border-gray-200 rounded-lg overflow-hidden">

  <!-- HEADER -->
  <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition criteria-toggle">
    <div class="flex items-center gap-3">
      <span class="w-8 h-8 flex items-center justify-center bg-primary text-black rounded-full text-sm font-bold">D</span>
      <span class="font-semibold text-gray-900">Delivery Fulfillment</span>
    </div>
    <i class="ri-arrow-down-s-line ri-xl text-gray-600 transition-transform"></i>
  </button>

  <!-- CONTENT -->
  <div class="criteria-content p-4 space-y-4">

    <div class="mb-3">
      <div class="font-medium mb-2">D. DELIVERY FULFILLMENT (25%)</div>

      <div class="space-y-2 text-xs">

        <!-- 4 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaD" value="4" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Goods / Services delivered on time, fully compliant with agreed delivery schedule and requirements
            <span class="bg-yellow-200 px-1 rounded">(25%)</span></div>
          </div>
        </label>

        <!-- 3 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaD" value="3" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Goods / Services delivered 1 to 5 days after the expiration of the delivery period
            <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></div>
          </div>
        </label>

        <!-- 2 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaD" value="2" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Goods / Services delivered 6 to 10 days after the expiration of the delivery period
            <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></div>
          </div>
        </label>

        <!-- 1 -->
        <label class="flex items-center gap-2 p-3 border border-gray-300 rounded-lg cursor-pointer hover:border-primary hover:bg-blue-50 transition">
          <input type="radio" name="criteriaD" value="1" class="custom-radio">
          <div>
            <div class="text-sm font-medium text-gray-900">
              Goods / Services delivered 11 or more days after the expiration of the delivery period
            <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></div>
          </div>
        </label>

      </div>
    </div>

    <!-- REMARKS (UNCHANGED) -->
    <div class="floating-label">
      <textarea
        placeholder=" "
        id="remarksD"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 text-sm"
      ></textarea>
      <label>Remarks</label>
    </div>

    <div class="text-xs text-gray-500 text-right">
      Character count: 0
    </div>

  </div>
</div>

        </div>
      </div>

  </div>
  </div>
  </div>


  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <button class="authorization-toggle mb-6">
      <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 flex items-center justify-center bg-green-100 text-green-600 rounded-lg">
            <i class="ri-quill-pen-line ri-xl"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900">Digital Authorization</h2>
        </div>
        <i class="ri-arrow-down-s-line ri-xl text-gray-600 transition-transform"></i>
      </div>
    </button>


    <div class="authorization-content">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="p-6 border-2 border-gray-200 rounded-xl">
          <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="ri-user-line ri-lg text-gray-600"></i>
            Prepared By (END-USER)
          </h3>
          <div class="space-y-4">
            <div class="floating-label">
              <input disabled type="text" placeholder=" " id="preparerName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
              <label>Full Name *</label>
            </div>
            <div class="floating-label">
              <input disabled type="text" placeholder=" " id="preparerDesignation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
              <label>Designation *</label>
            </div>
            <button class="w-full flex items-center justify-center gap-2 px-4 py-3 btn-primary rounded-lg whitespace-nowrap !rounded-button signature-btn" data-role="preparer">
              <i class="ri-quill-pen-line ri-lg"></i>
              <span class="font-medium">Link Signature</span>
            </button>
            <div class="signature-preview" id="preparerSignature">
              <div class="text-center text-gray-400">
                <i class="ri-quill-pen-line ri-2x mb-2"></i>
                <p class="text-sm">No signature linked</p>
              </div>
            </div>
          </div>
        </div>

        <div class="p-6 border-2 border-gray-200 rounded-xl">
          <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="ri-shield-check-line ri-lg text-gray-600"></i>
            Head Section Approval
          </h3>
          <div class="space-y-4">
            <div class="floating-label">
              <input disabled type="text" placeholder=" " id="approverName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
              <label>Full Name *</label>
            </div>
            <div class="floating-label">
              <input disabled type="text" placeholder=" " id="approverDesignation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900">
              <label>Designation *</label>
            </div>
            @if(!auth()->user()->isPresentativeStaff())
            <button class="w-full flex items-center justify-center gap-2 px-4 py-3 btn-primary rounded-lg whitespace-nowrap !rounded-button signature-btn" data-role="approver">
              <i class="ri-quill-pen-line ri-lg"></i>
              <span class="font-medium">Link Signature</span>
            </button>
            @endif
            <div class="signature-preview" id="approverSignature">
              <div class="text-center text-gray-400">
                <i class="ri-quill-pen-line ri-2x mb-2"></i>
                <p class="text-sm">No signature linked</p>
              </div>
            </div>
          </div>
@if(auth()->user()->isPresentativeStaff())
<div class="mt-8 border-t pt-6">

    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <i class="ri-user-star-line ri-lg text-gray-600"></i>
        Presentative Staff Approval
    </h3>

    <div class="space-y-4">

        <div class="floating-label">
            <input disabled
                   type="text"
                   placeholder=" "
                   id="presentativeName"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            <label>Full Name *</label>
        </div>

        <div class="floating-label">
            <input disabled
                   type="text"
                   placeholder=" "
                   id="presentativeDesignation"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            <label>Designation *</label>
        </div>

        <button
            class="w-full flex items-center justify-center gap-2 px-4 py-3 btn-primary rounded-lg signature-btn"
            data-role="presentative">
            <i class="ri-quill-pen-line ri-lg"></i>
            <span class="font-medium">Link Signature</span>
        </button>

        <div class="signature-preview" id="presentativeSignature">
            <div class="text-center text-gray-400">
                <i class="ri-quill-pen-line ri-2x mb-2"></i>
                <p class="text-sm">No signature linked</p>
            </div>
        </div>

    </div>

</div>
@endif
        </div>


      </div>
    </div>


  </div>


  </div>
  </main>
  </div>
  <footer class="bg-white border-t border-gray-200 px-6 py-4 sticky bottom-0 z-40">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-3 text-sm text-gray-600">
        <i class="ri-time-line ri-lg"></i>
        {{-- <span>Last saved: <span class="font-medium text-gray-900">2 minutes ago</span></span> --}}
        <div class="flex items-center gap-1">
          <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
          <span class="text-xs">Auto-sync active</span>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <button id="submitEvaluationBtn"
                class="px-6 py-2 btn-primary rounded-lg whitespace-nowrap !rounded-button">
            Submit Evaluation
        </button>
      </div>
    </div>
  </footer>






<!-- Supplier Selection Modal -->
<div id="supplierModal"
     class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">
                Select Supplier Evaluation
            </h2>
        </div>

        <!-- Body (scrollable) -->
        <div class="p-6 overflow-y-auto flex-1">

            <!-- Supplier Dropdown -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                    Supplier
                </label>
            <div class="flex items-center justify-between mb-3">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" id="selectAllEval">
                    Select All
                </label>
            </div>

                <select id="supplierSelect"
                        class="w-full border border-gray-300 rounded-lg p-3">
                    <option value="">Select Supplier</option>
                </select>
            </div>

            <!-- Evaluation List -->
            <div class="border rounded-lg overflow-hidden">

                <table class="w-full text-sm">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-3 w-16">Select</th>
                            <th class="p-3 text-left">PO No.</th>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Covered Period</th>
                        </tr>
                    </thead>

                    <tbody id="evaluationTable">
                        <tr>
                            <td colspan="4"
                                class="p-4 text-center text-gray-500">
                                Select a supplier
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

        <!-- Footer (only Proceed button) -->
        <div class="p-6 border-t flex justify-end">

            <button onclick="proceedBulk()"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Proceed
            </button>

        </div>

    </div>
</div>


<div id="resultModal"
     class="fixed inset-0 hidden z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">

    <div id="resultCard"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-75 opacity-0 transition-all duration-300">

        <!-- Header -->
        <div id="resultHeader"
             class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 text-center">

            <div id="resultIcon"
                 class="w-16 h-16 mx-auto rounded-full bg-white/20 flex items-center justify-center text-3xl mb-2">
            </div>

            <h2 class="text-xl font-bold">
                Evaluation Result
            </h2>

            <p class="text-sm opacity-90 mt-1">
                Supplier Performance Assessment
            </p>

        </div>

        <!-- Body -->
        <div class="p-8">

            <!-- Percentage -->
            <div class="text-center mb-6">

                <div id="modalPercent"
                     class="text-6xl font-extrabold text-slate-800">
                    0%
                </div>

                <div class="text-gray-500 mt-1">
                    Overall Evaluation Score
                </div>

            </div>

            <!-- Progress -->
            <div class="mb-4">

                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Score Progress</span>
                    <span>Passing Rate: <strong>60%</strong></span>
                </div>

                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hiddenn shadow-inner">

                    <div id="modalProgressBar"
                         class="h-full transition-all duration-1000 ease-out rounded-full">
                    </div>

                </div>

            </div>

            <!-- Result Badge -->
            <div class="text-center mb-6">

                <div id="modalStatus"
                     class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-semibold text-sm">
                    PASS
                </div>

            </div>

            <!-- Summary Card -->
            <div id="resultSummary"
                 class="rounded-xl border border-slate-200 bg-slate-50 p-3 mb-4 text-sm">

                <div class="flex justify-between">

                    <span class="text-gray-600">
                        Required Passing Score
                    </span>

                    <span class="font-bold text-slate-800">
                        60%
                    </span>

                </div>

                <div class="flex justify-between mt-2">

                    <span class="text-gray-600">
                        Your Result
                    </span>

                    <span id="summaryScore"
                          class="font-bold">
                        0%
                    </span>

                </div>

            </div>

            <!-- Button -->
            <button onclick="closeResultModal()"
                    class="w-full py-2.5 rounded-lg font-medium text-sm text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">

                Continue Evaluation

            </button>

        </div>

    </div>

</div>


<script>
    const currentUser = @json(auth()->user());
    const headUser = @json($headUser);

    const headName = @json($headName);
    const headDesignation = @json($headDesignation);

    const isEndUser = @json($isEndUser);
    const isHead = @json($isHead);
    const isPresentativeStaff = @json(auth()->user()->isPresentativeStaff());

    const preparerSignatureUrl =
        "{{ route('signature', auth()->user()) }}";

    const approverSignatureUrl =
        @json($headUser ? route('signature', $headUser) : null);
    const presentativeSignatureUrl =
        "{{ route('signature', auth()->user()) }}";
</script>


<script>
let linkedSignatures = {
    preparer: null,
    approver: null,
    presentative: null
};
let selectedEvaluations = new Map();
let currentEvaluation = null;



/* ====================================
   PAGE LOAD
==================================== */
document.addEventListener('DOMContentLoaded', () => {
if (isPresentativeStaff) {

    // ==============================
    // Fill presentative staff data
    // ==============================
    document.getElementById('presentativeName').value =
        currentUser.name || '';

    document.getElementById('presentativeDesignation').value =
        currentUser.designation || '';

    // ==============================
    // BLOCK END-USER (PREPARER) SIGNATURE LINKING
    // ==============================
    const preparerBtn = document.querySelector(
        '.signature-btn[data-role="preparer"]'
    );

    if (preparerBtn) {

        preparerBtn.disabled = true;
        preparerBtn.classList.add(
            'opacity-50',
            'cursor-not-allowed'
        );
        preparerBtn.title =
            'Presentative Staff cannot link End-User signatures';
    }

    // ==============================
    // HIDE HEAD (APPROVER) SIGNATURE BUTTON
    // ==============================
    const approverBtn = document.querySelector(
        '.signature-btn[data-role="approver"]'
    );

    if (approverBtn) {
        approverBtn.remove();
    }

    // ==============================
    // SAFETY: BLOCK CLICK EVEN IF BUTTON IS RE-INSERTED
    // ==============================
    document.addEventListener('click', function (e) {

        const btn = e.target.closest('.signature-btn');
        if (!btn) return;

        if (btn.dataset.role === 'approver') {
            e.preventDefault();
            e.stopPropagation();

            alert(
                'Presentative Staff is not allowed to link Head signature.'
            );
        }
    });
}
    /* ==========================
       FILL DATA
    ========================== */
    if (currentUser) {
        document.getElementById('preparerName').value =
            currentUser.name || '';

        document.getElementById('preparerDesignation').value =
            currentUser.designation || '';
    }

    document.getElementById('approverName').value =
        headUser?.name || headName || '';

    document.getElementById('approverDesignation').value =
        headUser?.designation || headDesignation || '';

    /* ==========================
       ROLE RESTRICTIONS (SIMPLE)
    ========================== */

    if (isEndUser) {
        // remove head panel
        document.querySelector('.signature-btn[data-role="approver"]')?.remove();
    }

    if (isHead) {
        // remove end-user panel
        document.querySelector('.signature-btn[data-role="preparer"]')?.remove();
    }

document.addEventListener('click', function (e) {

    const btn = e.target.closest('.signature-btn');
    if (!btn) return;

    const role = btn.dataset.role;

    // Presentative Staff cannot link Preparer
    if (isPresentativeStaff && role === 'preparer') {
        alert('You are not authorized to link the End-User signature.');
        return;
    }

    // End User cannot link Head
    if (isEndUser && role === 'approver') {
        alert('End User is not allowed to link the Head signature.');
        return;
    }

    const urls = {
        preparer: preparerSignatureUrl,
        approver: approverSignatureUrl,
        presentative: presentativeSignatureUrl
    };

    const url = urls[role];

    if (!url) {
        alert('No registered Head account found. Signature cannot be linked.');
        return;
    }

    linkSignature(role, url);
});

    /* ==========================
       INIT
    ========================== */
    document.getElementById('sidebar')?.classList.add('hidden');
    populateSuppliers();

    const urlParams = new URLSearchParams(window.location.search);
    const supplierParam = urlParams.get('supplier');
    if (supplierParam) {
        const select = document.getElementById('supplierSelect');
        if (select) {
            select.value = supplierParam;
            select.dispatchEvent(new Event('change'));
        }
    }

    document.getElementById('supplierModal')?.classList.remove('hidden');


});



function linkSignature(role, imageUrl) {

    const container = document.getElementById(`${role}Signature`);
    const button = document.querySelector(`.signature-btn[data-role="${role}"]`);

    if (!container || !button) return;

    // 1. show signature image
    container.innerHTML = `
        <img src="${imageUrl}"
             class="max-h-24 mx-auto object-contain"
             alt="Signature">
    `;

    // 2. save user id
    const userMap = {
        preparer: currentUser?.id,
        approver: headUser?.id,
        presentative: currentUser?.id
    };

    const userId = userMap[role];

    linkedSignatures[role] = { user_id: userId };

    // 3. REMOVE BUTTON + REPLACE WITH TEXT
    const statusText = document.createElement('div');
    statusText.className = "text-center text-green-600 font-semibold mt-2";
    statusText.innerHTML = "✔ Signature Linked";

    button.replaceWith(statusText);

    console.log('Linked Signatures:', linkedSignatures);
}


document.addEventListener('DOMContentLoaded', () => {

    const btn = document.getElementById('submitEvaluationBtn');

    if (btn) {
        btn.addEventListener('click', submitBulkEvaluations);
    }

});
const criteria = {
    A: 20,
    B: 30,
    C: 25,
    D: 25
};

const PASS_THRESHOLD = 60;
let resultShown = false;

function calculateProgress() {

    let totalScore = 0;
    let maxScore = 0;

    let completedCriteria = 0;

    Object.keys(criteria).forEach(letter => {

        const selected = document.querySelector(
            `input[name="criteria${letter}"]:checked`
        );

        const weight = criteria[letter];

        maxScore += weight;

        if (selected) {

            completedCriteria++;

            const value = parseInt(selected.value, 10);

            if (!isNaN(value)) {
                totalScore += (value / 4) * weight;
            }
        }
    });

    const percent =
        maxScore > 0
            ? Math.round((totalScore / maxScore) * 100)
            : 0;

    const isFail =
        percent < PASS_THRESHOLD;

    // ==========================
    // UPDATE PROGRESS BAR
    // ==========================

    const bar =
        document.querySelector('.progress-fill');

    const text =
        document.getElementById('progressText');

    const status =
        document.getElementById('progressStatus');

    if (bar) {

        bar.style.width = `${percent}%`;

        bar.classList.remove(
            'bg-red-500',
            'bg-green-500'
        );

        bar.classList.add(
            isFail
                ? 'bg-red-500'
                : 'bg-green-500'
        );
    }

    if (text) {

        text.textContent = `${percent}%`;

        text.classList.remove(
            'text-red-600',
            'text-green-600'
        );

        text.classList.add(
            isFail
                ? 'text-red-600'
                : 'text-green-600'
        );
    }

    if (status) {

        status.textContent =
            isFail
                ? 'FAIL'
                : 'PASS';

        status.className =
            `text-xs font-bold px-2 py-1 rounded-full ${
                isFail
                    ? 'bg-red-100 text-red-600'
                    : 'bg-green-100 text-green-700'
            }`;
    }

    // ==========================
    // SHOW RESULT MODAL
    // WHEN A,B,C,D COMPLETE
    // ==========================

    if (
        completedCriteria === 4 &&
        !resultShown
    ) {

        resultShown = true;

        setTimeout(() => {

            showResultModal(
                percent,
                isFail
            );

        }, 400);
    }
}



function showResultModal(percent, isFail) {

    const modal =
        document.getElementById('resultModal');

    const card =
        document.getElementById('resultCard');

    const icon =
        document.getElementById('resultIcon');

    const header =
        document.getElementById('resultHeader');

    const status =
        document.getElementById('modalStatus');

    const percentText =
        document.getElementById('modalPercent');

    const progress =
        document.getElementById('modalProgressBar');

    const summary =
        document.getElementById('summaryScore');

    modal.classList.remove('hidden');

    card.classList.remove(
        'scale-75',
        'opacity-0'
    );

    card.classList.add('result-show');

    percentText.textContent = `${percent}%`;
    summary.textContent = `${percent}%`;

    progress.style.width = `${percent}%`;

    if (isFail) {

        icon.innerHTML = '✖';

        header.className =
            'bg-gradient-to-r from-red-500 to-rose-600 text-white p-6 text-center';

        status.textContent = 'FAILED';

        status.className =
            'inline-flex items-center gap-2 px-5 py-3 rounded-full font-bold text-lg bg-red-100 text-red-600';

        progress.className =
            'h-full bg-gradient-to-r from-red-500 to-red-700 transition-all duration-1000 ease-out rounded-full';

        summary.className =
            'font-bold text-red-600';

    } else {

        icon.innerHTML = '✓';

        header.className =
            'bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 text-center';

        status.textContent = 'PASSED';

        status.className =
            'inline-flex items-center gap-2 px-5 py-3 rounded-full font-bold text-lg bg-green-100 text-green-700';

        progress.className =
            'h-full bg-gradient-to-r from-green-500 to-emerald-600 transition-all duration-1000 ease-out rounded-full';

        summary.className =
            'font-bold text-green-600';
    }
}

function closeResultModal() {

    const modal =
        document.getElementById('resultModal');

    const card =
        document.getElementById('resultCard');

    card.classList.add(
        'scale-75',
        'opacity-0'
    );

    setTimeout(() => {

        modal.classList.add('hidden');

    }, 250);
}

    // Listen to all radio changes
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('input[type="radio"]')
        .forEach(radio => {

            radio.addEventListener(
                'change',
                calculateProgress
            );
        });

    calculateProgress();
});





/* ====================================
   MODAL FUNCTIONS
==================================== */
function openSupplierModal() {

    document.getElementById('supplierModal')
        .classList.remove('hidden');
}

function closeModal() {

    document.getElementById('supplierModal')
        .classList.add('hidden');
}

supplierModal.addEventListener('click', function (e) {

    // if clicked directly on backdrop (not inside modal box)
    if (e.target === supplierModal) {
        closeModal();
    }
});


/* ====================================
   POPULATE SUPPLIERS
==================================== */
function populateSuppliers() {

    const suppliers = new Set();

    document.querySelectorAll('.evaluation-item')
        .forEach(item => {

            const supplier = item.dataset.supplier;

            if (supplier) {
                suppliers.add(supplier);
            }
        });

    const select =
        document.getElementById('supplierSelect');

    select.innerHTML =
        '<option value="">Select Supplier</option>';

    [...suppliers]
        .sort()
        .forEach(supplier => {

            select.innerHTML += `
                <option value="${supplier}">
                    ${supplier}
                </option>
            `;
        });
}


/* ====================================
   SUPPLIER CHANGE
==================================== */
document.getElementById('supplierSelect')
.addEventListener('change', function () {

    const supplier = this.value;
    const tbody = document.getElementById('evaluationTable');
    const selectAllCb = document.getElementById('selectAllEval');

    if (selectAllCb) selectAllCb.checked = false;

    tbody.innerHTML = '';

    if (!supplier) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4"
                    class="p-4 text-center text-gray-500">
                    Select a supplier
                </td>
            </tr>
        `;
        return;
    }

    let matchCount = 0;
    document.querySelectorAll('.evaluation-item')
        .forEach(item => {
            if (item.dataset.supplier === supplier) {
                matchCount++;
                tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3 text-center">
                            <input
                                type="checkbox"
                                class="eval-checkbox"
                                value="${item.dataset.id}">
                        </td>

                        <td class="p-3 font-medium text-gray-800">
                            ${item.dataset.po || '-'}
                        </td>

                        <td class="p-3">
                            ${item.dataset.date || '-'}
                        </td>

                        <td class="p-3">
                            ${item.dataset.coveredperiod || '-'}
                        </td>

                    </tr>
                `;
            }
        });

    if (matchCount === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4"
                    class="p-4 text-center text-gray-500">
                    No evaluations found for this supplier
                </td>
            </tr>
        `;
    } else {
        syncModalSelections();
    }
});

function removeSelectedEvaluations() {

    if (selectedEvaluations.size === 0) {
        alert('No evaluations selected to remove.');
        return;
    }

    if (!confirm('Remove selected evaluations from the list?')) {
        return;
    }

    selectedEvaluations.clear();

    document.querySelectorAll('.eval-checkbox')
        .forEach(cb => cb.checked = false);

    updateSidebarList();

    [
        'supplierName',
        'poNumber',
        'evaluationDate',
        'coveredPeriod',
        'officeName'
    ].forEach(id => {

        const el = document.getElementById(id);

        if (el) {
            el.value = '';
        }
    });
}


function removeEvaluation(event, id) {

    event.stopPropagation();

    if (!confirm('Remove this evaluation?')) return;

    id = String(id);

    // remove from selected list only
    selectedEvaluations.delete(id);

    // uncheck modal checkbox
    const checkbox = document.querySelector(
        `.eval-checkbox[value="${id}"]`
    );

    if (checkbox) {
        checkbox.checked = false;
    }

    updateSidebarList();
}

/* ====================================
   PROCEED BULK
==================================== */
function proceedBulk() {

    const checked =
        document.querySelectorAll('.eval-checkbox:checked');

    if (!checked.length) {
        alert('Select at least one evaluation');
        return;
    }

    let addedCount = 0;

    checked.forEach(cb => {

        const el = document.querySelector(
            `[data-id="${cb.value}"]`
        );

        if (!el) return;

        const id = String(el.dataset.id);

        if (selectedEvaluations.has(id)) {
            return;
        }

        const data = {
            id,
            supplier: el.dataset.supplier,
            po: el.dataset.po,
            date: el.dataset.date,
            covered: el.dataset.coveredperiod,
            office_id: el.dataset.officeId,
            office_name: el.dataset.officeName
        };

        selectedEvaluations.set(id, data);
        addedCount++;
    });

    updateSidebarList();

    if (addedCount === 0 && selectedEvaluations.size === 0) {
        alert('All selected evaluations are already added.');
    }

    closeModal();

    // Auto-select and load the first evaluation into the main evaluation form
    if (selectedEvaluations.size > 0) {
        const firstId = Array.from(selectedEvaluations.keys())[0];
        const firstItem = document.querySelector(`.evaluation-item[data-id="${firstId}"]`);
        if (firstItem) {
            firstItem.click();
        }
    }
}


document.addEventListener('change', function (e) {

    if (e.target.id === 'selectAllEval') {

        const checked = e.target.checked;

        document.querySelectorAll('.eval-checkbox').forEach(cb => {
            cb.checked = checked;
        });
    } else if (e.target.classList.contains('eval-checkbox')) {

        const all = document.querySelectorAll('.eval-checkbox');
        const checked = document.querySelectorAll('.eval-checkbox:checked');
        const selectAllCb = document.getElementById('selectAllEval');

        if (selectAllCb) {
            selectAllCb.checked = (all.length > 0 && all.length === checked.length);
        }
    }
});


function syncModalSelections() {

    document.querySelectorAll('.eval-checkbox').forEach(cb => {

        if (selectedEvaluations.has(cb.value)) {
            cb.checked = true;
        }
    });
}


function updateSidebarList() {

    const items = document.querySelectorAll('.evaluation-item');

    const ids = Array.from(selectedEvaluations.keys());

    items.forEach(el => {

        const id = String(el.dataset.id);

        el.style.display =
            ids.includes(id)
                ? 'block'
                : 'none';
    });

    const countElement =
        document.querySelector('#sidebar h2 span');

    if (countElement) {
        countElement.textContent = `(${ids.length})`;
    }

    const sidebar =
        document.getElementById('sidebar');

    if (ids.length > 0) {
        sidebar?.classList.remove('hidden');
    } else {
        sidebar?.classList.add('hidden');
    }
}

/* ====================================
   SIDEBAR ITEM CLICK
==================================== */
document.querySelectorAll('.evaluation-item')
.forEach(item => {

    item.addEventListener('click', function () {

        currentEvaluation = this.dataset.id;

        loadEvaluation(this.dataset.id);

        document.querySelectorAll('.evaluation-item')
            .forEach(el => {

                el.classList.remove(
                    'border-primary',
                    'bg-blue-50'
                );
            });

        this.classList.add(
            'border-primary',
            'bg-blue-50'
        );
    });

});


function buildBulkPayload() {

    const criteria = buildCriteriaPayload();

    return Array.from(selectedEvaluations.values()).map(ev => {

        return {
            supplier_name: ev.supplier,
            po_no: ev.po,
            date_evaluation: ev.date,
            office_id: ev.office_id, // <-- comma added
            year: new Date().getFullYear(),

            criteria: criteria,

            evaluator: {
                full_name: currentUser?.name,
                designation: currentUser?.designation,
                image: currentUser?.signature || null
            },

            preparer_id: linkedSignatures.preparer?.user_id || null,
            approver_id: linkedSignatures.approver?.user_id || null
        };
    });
}

function buildCriteriaPayload() {

    const criteriaMap = {
        A: 1,
        B: 2,
        C: 3,
        D: 4
    };

    return Object.keys(criteriaMap).map(letter => {

        const criteriaId = criteriaMap[letter];

        const selected = document.querySelector(
            `input[name="criteria${letter}"]:checked`
        );

        const remarks = document.getElementById(`remarks${letter}`)?.value || '';

        return {
            criteria_id: criteriaId,
            rating: selected ? parseInt(selected.value, 10) : null,
            remarks: remarks
        };
    });
}



async function loadEvaluation(id)
{
    try {
        resultShown = false;

        const response = await fetch(
            `/evaluations/bulk/${id}/data`
        );

        const data = await response.json();

        if (!data.success) {
            return;
        }

        const ev = data.evaluation;

        // ==========================
        // BASIC INFO
        // ==========================
        document.getElementById('supplierName').value =
            ev.supplier_name || '';

        document.getElementById('poNumber').value =
            ev.po_no || '';

        document.getElementById('evaluationDate').value =
            ev.date_evaluation?.split('T')[0] || '';

        document.getElementById('coveredPeriod').value =
            ev.covered_period || '';

        document.getElementById('officeName').value =
            ev.office?.name || '';

        // ==========================
        // RESET FIRST
        // ==========================
        document.querySelectorAll(
            'input[type="radio"]'
        ).forEach(r => r.checked = false);

        ['A','B','C','D'].forEach(letter => {
            const remarks =
                document.getElementById(`remarks${letter}`);

            if (remarks) {
                remarks.value = '';
            }
        });

        // ==========================
        // LOAD SCORES
        // ==========================
        ev.criteria_scores.forEach(score => {

            const criteriaMap = {
                1: 'A',
                2: 'B',
                3: 'C',
                4: 'D'
            };

            const letter =
                criteriaMap[score.criteria_id];

            if (!letter) return;

            const radio = document.querySelector(
                `input[name="criteria${letter}"][value="${score.number_rating}"]`
            );

            if (radio) {
                radio.checked = true;
            }

            const remarks =
                document.getElementById(
                    `remarks${letter}`
                );

            if (remarks) {
                remarks.value =
                    score.remarks || '';
            }
        });

        // ==========================
        // LOAD SIGNATURES
        // ==========================
        const prepared =
            ev.digital_approvals.find(
                x => x.role === 'Prepared by'
            );

        if (prepared) {

            document.getElementById(
                'preparerName'
            ).value =
                prepared.full_name || '';

            document.getElementById(
                'preparerDesignation'
            ).value =
                prepared.designation || '';

            if (prepared.signature_url) {

                document.getElementById(
                    'preparerSignature'
                ).innerHTML = `
                    <img
                        src="${prepared.signature_url}"
                        class="max-h-24 mx-auto object-contain">
                `;
            }

            linkedSignatures.preparer = {
                user_id: prepared.signed_by
            };
        }

        calculateProgress();

    } catch (e) {

        console.error(e);

        alert('Unable to load evaluation.');
    }
}




async function submitBulkEvaluations() {

    if (selectedEvaluations.size === 0) {
        alert("Please select at least one evaluation.");
        return;
    }

    const requiredCriteria = ['A', 'B', 'C', 'D'];

    for (const letter of requiredCriteria) {
        const selected = document.querySelector(
            `input[name="criteria${letter}"]:checked`
        );
        if (!selected) {
            alert(`Please select a rating for Criteria ${letter}.`);
            return;
        }
    }

    if (!linkedSignatures.preparer) {
        alert("Please link the PREPARER signature.");
        return;
    }

// Only Head (or other authorized roles) should link the approver.
// End User and Presentative Staff should NOT.
if (!isEndUser && !isPresentativeStaff && !linkedSignatures.approver) {
    alert("Please link the APPROVER signature.");
    return;
}

    const payload = Array.from(selectedEvaluations.values()).map(ev => ({
        supplier_name: ev.supplier,
        po_no: ev.po,
        date_evaluation: ev.date,
        office_id: ev.office_id,
        year: new Date().getFullYear(),

        criteria: buildCriteriaPayload(),

        preparer_id: linkedSignatures.preparer?.user_id || null,

        // IMPORTANT: send presentative as its own field
        presentative_id: isPresentativeStaff
            ? currentUser.id
            : null,

approver_id: (isEndUser || isPresentativeStaff)
    ? null
    : (linkedSignatures.approver?.user_id || null),

        evaluator: {
            full_name: currentUser?.name || '',
            designation: currentUser?.designation || '',
            image: currentUser?.signature || null
        }
    }));

    try {
        const response = await fetch('/evaluations/bulk-store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ evaluations: payload })
        });

        const result = await response.json();

        if (result.success) {
            alert("Evaluations saved successfully!");
            location.reload();
        } else {
            alert(result.message || "Unable to save evaluations.");
        }

    } catch (error) {
        console.error(error);
        alert("An error occurred while saving evaluations.");
    }
}



/* ====================================
   SEARCH FILTER
==================================== */
document.querySelector('input[placeholder="Search evaluations..."]')
?.addEventListener('input', function () {

    const search = this.value.toLowerCase().trim();

    const items = document.querySelectorAll('.evaluation-item');

    const hasSelected = selectedEvaluations.size > 0;

    items.forEach(item => {

        const text = item.innerText.toLowerCase();

        const id = item.dataset.id;

        // ----------------------------------
        // FILTER SCOPE
        // ----------------------------------

        const isInSelectedMode = hasSelected
            ? selectedEvaluations.has(id)
            : true;

        // ----------------------------------
        // FINAL MATCH
        // ----------------------------------
        const match = text.includes(search) && isInSelectedMode;

        item.style.display = match ? 'block' : 'none';
    });
});

</script>









   <script id="criteria-accordion">
    document.addEventListener('DOMContentLoaded', function() {
      const criteriaToggles = document.querySelectorAll('.criteria-toggle');
      criteriaToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
          const content = this.nextElementSibling;
          const icon = this.querySelector('i');
          const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
          if (isOpen) {
            content.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)'
          } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)'
          }
        })
      });
      const firstCriteria = document.querySelector('.criteria-content');
      const firstIcon = document.querySelector('.criteria-toggle i');
      if (firstCriteria && firstIcon) {
        firstCriteria.style.maxHeight = firstCriteria.scrollHeight + 'px';
        firstIcon.style.transform = 'rotate(180deg)'
      }
      const authorizationToggle = document.querySelector('.authorization-toggle');
      const authorizationContent = document.querySelector('.authorization-content');
      if (authorizationToggle && authorizationContent) {
        authorizationToggle.addEventListener('click', function() {
          const icon = this.querySelector('i');
          const isOpen = authorizationContent.style.maxHeight && authorizationContent.style.maxHeight !== '0px';
          if (isOpen) {
            authorizationContent.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)'
          } else {
            authorizationContent.style.maxHeight = authorizationContent.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)'
          }
        });
        authorizationContent.style.maxHeight = authorizationContent.scrollHeight + 'px';
        authorizationToggle.querySelector('i').style.transform = 'rotate(180deg)'
      }
    });
  </script>


  <script id="character-counter">
    document.addEventListener('DOMContentLoaded', function() {
      const textareas = document.querySelectorAll('textarea');
      textareas.forEach(textarea => {
        const updateCount = () => {
          const counter = textarea.parentElement.nextElementSibling;
          if (counter && counter.classList.contains('text-xs')) {
            counter.textContent = `Character count: ${textarea.value.length}`
          }
        };
        textarea.addEventListener('input', updateCount);
        updateCount()
      })
    });
  </script>
 <script id="menu-toggle">
    document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('menuToggle');
      const sidebar = document.getElementById('sidebar');
      menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open')
      })
    });
  </script>
{{--


  <script id="signature-modal">
    document.addEventListener('DOMContentLoaded', function() {
      const signatureModal = document.getElementById('signatureModal');
      const signatureBtns = document.querySelectorAll('.signature-btn');
      const closeModalBtns = document.querySelectorAll('.close-modal');
      const confirmSignature = document.getElementById('confirmSignature');
      const signatureMethods = document.querySelectorAll('.signature-method');
      let currentRole = '';
      signatureBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          currentRole = this.dataset.role;
          signatureModal.classList.remove('hidden');
          signatureModal.classList.add('flex');
          document.body.style.overflow = 'hidden'
        })
      });
      closeModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          signatureModal.classList.add('hidden');
          signatureModal.classList.remove('flex');
          document.body.style.overflow = ''
        })
      });
      signatureModal.addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.add('hidden');
          this.classList.remove('flex');
          document.body.style.overflow = ''
        }
      });
      confirmSignature.addEventListener('click', function() {
        const now = new Date();
        const formattedDate = now.toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric',
          year: 'numeric'
        });
        const formattedTime = now.toLocaleTimeString('en-US', {
          hour: '2-digit',
          minute: '2-digit'
        });
        const signaturePreview = document.getElementById(currentRole === 'preparer' ? 'preparerSignature' : 'approverSignature');
        signaturePreview.innerHTML = `<div class="text-center"><i class="ri-checkbox-circle-fill ri-2x text-green-600 mb-2"></i><p class="text-sm font-medium text-green-700">Signature Linked</p><p class="text-xs text-gray-500 mt-1">${formattedDate} at ${formattedTime}</p></div>`;
        signaturePreview.classList.add('signature-linked');
        showToast('Signature linked successfully!', 'success');
        signatureModal.classList.add('hidden');
        signatureModal.classList.remove('flex');
        document.body.style.overflow = ''
      });
      signatureMethods.forEach(method => {
        method.addEventListener('click', function() {
          signatureMethods.forEach(m => m.classList.remove('active'));
          this.classList.add('active')
        })
      })
    });
  </script>



  <script id="batch-mode">
    document.addEventListener('DOMContentLoaded', function() {
      const batchModeToggle = document.getElementById('batchModeToggle');
      const batchToolbar = document.getElementById('batchToolbar');
      const batchCheckboxes = document.querySelectorAll('.batch-checkbox');
      const selectedCountEl = document.getElementById('selectedCount');
      const clearSelection = document.getElementById('clearSelection');
      window.batchModeActive = false;
      batchModeToggle.addEventListener('click', function() {
        window.batchModeActive = !window.batchModeActive;
        if (window.batchModeActive) {
          batchCheckboxes.forEach(cb => cb.classList.remove('hidden'));
          batchToolbar.classList.remove('hidden');
          batchModeToggle.innerHTML = '<i class="ri-close-line ri-lg"></i><span>Cancel Selection</span>'
        } else {
          batchCheckboxes.forEach(cb => {
            cb.classList.add('hidden');
            cb.checked = false
          });
          batchToolbar.classList.add('hidden');
          batchModeToggle.innerHTML = '<i class="ri-checkbox-multiple-line ri-lg"></i><span>Select Multiple</span>';
          document.querySelectorAll('.evaluation-item').forEach(item => item.classList.remove('selected'))
        }
        updateSelectedCount()
      });
      batchCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          const item = this.closest('.evaluation-item');
          if (this.checked) {
            item.classList.add('selected');
            this.classList.add('checkbox-bounce')
          } else {
            item.classList.remove('selected')
          }
          updateSelectedCount()
        })
      });
      clearSelection.addEventListener('click', function() {
        batchCheckboxes.forEach(cb => cb.checked = false);
        document.querySelectorAll('.evaluation-item').forEach(item => item.classList.remove('selected'));
        updateSelectedCount()
      });

      function updateSelectedCount() {
        const count = Array.from(batchCheckboxes).filter(cb => cb.checked).length;
        selectedCountEl.textContent = count
      }
    });
  </script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* =========================
     GLOBAL STATE
  ========================= */
  window.evaluationData = window.evaluationData || {};
  window.activeEvaluationId = null;

  /* =========================
     HELPERS
  ========================= */

  function getActiveItem() {
    return document.querySelector('.evaluation-item.active');
  }

  function setActiveItem(item) {
    if (!item) return;

    document.querySelectorAll('.evaluation-item')
      .forEach(i => i.classList.remove('active'));

    item.classList.add('active');
    window.activeEvaluationId = item.dataset.id;
  }

  function getSelectedItems() {
    return Array.from(document.querySelectorAll('.batch-checkbox:checked'))
      .map(cb => cb.closest('.evaluation-item'))
      .filter(Boolean);
  }

  function setLinkedUI(id, state) {
    const item = document.querySelector(`.evaluation-item[data-id="${id}"]`);
    if (!item) return;

    const badge = item.querySelector('.linked-badge');
    if (badge) badge.classList.toggle('hidden', !state);
  }

  /* =========================
     BUILD FORM STATE
  ========================= */
  function buildFormState() {

    const getRadio = (name) =>
      document.querySelector(`input[name="${name}"]:checked`)?.value || null;

    return {
      coveredPeriod: document.getElementById('coveredPeriod')?.value || '',
      supplierName: document.getElementById('supplierName')?.value || '',
      evaluationDate: document.getElementById('evaluationDate')?.value || '',
      poNumber: document.getElementById('poNumber')?.value || '',
      officeName: document.getElementById('officeName')?.value || '',

      criteriaA: getRadio('criteriaA'),
      criteriaB: getRadio('criteriaB'),
      criteriaC: getRadio('criteriaC'),
      criteriaD: getRadio('criteriaD'),

      remarksA: document.getElementById('remarksA')?.value || '',
      remarksB: document.getElementById('remarksB')?.value || '',
      remarksC: document.getElementById('remarksC')?.value || '',
      remarksD: document.getElementById('remarksD')?.value || '',

      preparerName: document.getElementById('preparerName')?.value || '',
      preparerDesignation: document.getElementById('preparerDesignation')?.value || '',
      approverName: document.getElementById('approverName')?.value || '',
      approverDesignation: document.getElementById('approverDesignation')?.value || '',

      preparerSignature: document.getElementById('preparerSignature')?.innerHTML || '',
      approverSignature: document.getElementById('approverSignature')?.innerHTML || ''
    };
  }

  /* =========================
     APPLY STATE TO FORM
  ========================= */
  function applyStateToForm(data) {
    if (!data) return;

    document.getElementById('coveredPeriod').value = data.coveredPeriod || '';
    document.getElementById('supplierName').value = data.supplierName || '';
    document.getElementById('evaluationDate').value = data.evaluationDate || '';
    document.getElementById('poNumber').value = data.poNumber || '';
    document.getElementById('officeName').value = data.officeName || '';

    const setRadio = (name, value) => {
      if (!value) return;
      const el = document.querySelector(`input[name="${name}"][value="${value}"]`);
      if (el) el.checked = true;
    };

    setRadio('criteriaA', data.criteriaA);
    setRadio('criteriaB', data.criteriaB);
    setRadio('criteriaC', data.criteriaC);
    setRadio('criteriaD', data.criteriaD);

    document.getElementById('remarksA').value = data.remarksA || '';
    document.getElementById('remarksB').value = data.remarksB || '';
    document.getElementById('remarksC').value = data.remarksC || '';
    document.getElementById('remarksD').value = data.remarksD || '';

    document.getElementById('preparerName').value = data.preparerName || '';
    document.getElementById('preparerDesignation').value = data.preparerDesignation || '';
    document.getElementById('approverName').value = data.approverName || '';
    document.getElementById('approverDesignation').value = data.approverDesignation || '';

    document.getElementById('preparerSignature').innerHTML =
      data.preparerSignature || '<div class="text-center text-gray-400">No signature linked</div>';

    document.getElementById('approverSignature').innerHTML =
      data.approverSignature || '<div class="text-center text-gray-400">No signature linked</div>';
  }

  /* =========================
     SAVE EVALUATION
  ========================= */
  function saveEvaluation(item, dataOverride = null) {
    if (!item || !item.dataset.id) return;

    const id = item.dataset.id;

    const data = dataOverride || buildFormState();

    window.evaluationData[id] = {
      ...window.evaluationData[id],
      ...data,
      linkedTo: window.activeEvaluationId
    };
  }

  /* =========================
     LOAD EVALUATION
  ========================= */
  function loadEvaluation(id) {
    const data = window.evaluationData[id];
    if (!data) return;

    applyStateToForm(data);
  }

  /* =========================
     LIVE SYNC ENGINE (IMPORTANT)
  ========================= */
  function propagateToLinked(sourceId) {

    const sourceData = window.evaluationData[sourceId];
    if (!sourceData) return;

    Object.entries(window.evaluationData).forEach(([id, data]) => {

      if (data.linkedTo !== sourceId) return;
      if (id === sourceId) return;

      window.evaluationData[id] = {
        ...data,
        ...sourceData,
        linkedTo: sourceId
      };

      setLinkedUI(id, true);

      // refresh UI if active
      const item = document.querySelector(`.evaluation-item[data-id="${id}"]`);
      if (item?.classList.contains('active')) {
        applyStateToForm(sourceData);
      }
    });
  }

  /* =========================
     CLICK ITEM SWITCH
  ========================= */
  document.querySelectorAll('.evaluation-item').forEach(item => {

    item.addEventListener('click', function (e) {

      if (e.target.classList.contains('batch-checkbox')) return;

      const current = getActiveItem();

      if (current && current !== this) {
        saveEvaluation(current);
      }

      setActiveItem(this);
      loadEvaluation(this.dataset.id);
    });
  });

  /* =========================
     AUTO SYNC ON CHANGE (CRITICAL FIX)
  ========================= */
  document.addEventListener('change', function () {

    const active = getActiveItem();
    if (!active) return;

    const id = active.dataset.id;

    window.evaluationData[id] = buildFormState();

    propagateToLinked(id);
  });

  /* =========================
     BATCH APPLY (FIXED)
  ========================= */
  document.getElementById('saveBatchData')?.addEventListener('click', function () {

    const selectedItems = getSelectedItems();

    if (!selectedItems.length) {
      showToast('Please select evaluations', 'error');
      return;
    }

    const source = getActiveItem();

    if (!source) {
      showToast('No active evaluation selected', 'error');
      return;
    }

    saveEvaluation(source);

    const sourceId = source.dataset.id;
    const sourceData = window.evaluationData[sourceId];

    let count = 0;

    selectedItems.forEach(item => {

      const id = item.dataset.id;
      if (id === sourceId) return;

      window.evaluationData[id] = {
        ...sourceData,
        linkedTo: sourceId
      };

      setLinkedUI(id, true);
      count++;
    });

    showToast(`Applied to ${count} evaluations`, 'success');

    document.querySelectorAll('.batch-checkbox')
      .forEach(cb => cb.checked = false);

    document.getElementById('batchToolbar')?.classList.add('hidden');
  });

});
</script>


  <script id="toast-notifications">
    function showToast(message, type) {
      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      toast.innerHTML = `<div class="flex items-center gap-3"><i class="ri-${type==='success'?'checkbox-circle':'error-warning'}-fill ri-xl"></i><span class="font-medium">${message}</span></div>`;
      document.body.appendChild(toast);
      setTimeout(() => {
        toast.style.animation = 'toastSlide 0.4s ease-out reverse';
        setTimeout(() => toast.remove(), 400)
      }, 3000)
    }
  </script>




  <script id="radio-animations">
    document.addEventListener('DOMContentLoaded', function() {
      const radios = document.querySelectorAll('.custom-radio');
      radios.forEach(radio => {
        radio.addEventListener('change', function() {
          if (this.checked) {
            this.style.animation = 'none';
            setTimeout(() => {
              this.style.animation = ''
            }, 10)
          }
        })
      })
    });
  </script>


<script>
document.addEventListener('DOMContentLoaded', function () {

  /* =========================
     GLOBAL STATE
  ========================= */
  window.evaluationData = window.evaluationData || {};
  window.activeEvaluationId = null;


  /* =========================
     BASIC INFO FIELDS (SAFE BINDING)
  ========================= */
  const supplierNameInput = document.getElementById('supplierName');
  const poNumberInput = document.getElementById('poNumber');
  const evaluationDateInput = document.getElementById('evaluationDate');

  const preparerSig = document.getElementById('preparerSignature');
  const approverSig = document.getElementById('approverSignature');
  const coveredPeriodInput = document.getElementById('coveredPeriod');


  /* =========================
     GET ACTIVE ITEM
  ========================= */
  function getActiveItem() {
    return document.querySelector('.evaluation-item.active');
  }


  /* =========================
     SET ACTIVE ITEM
  ========================= */
  function setActiveItem(item) {

    if (!item) return;

    document.querySelectorAll('.evaluation-item').forEach(el => {
      el.classList.remove('active', 'bg-blue-50');
    });

    item.classList.add('active', 'bg-blue-50');

    window.activeEvaluationId = item.dataset.id || null;
  }


  /* =========================
     UPDATE BASIC INFO PANEL
  ========================= */
  function updateBasicInfo(item) {
    if (!item) return;

    if (supplierNameInput) supplierNameInput.value = item.dataset.supplier || '';
    if (poNumberInput) poNumberInput.value = item.dataset.po || '';
    if (evaluationDateInput) evaluationDateInput.value = item.dataset.date || '';

    // ✅ FIXED: COVERED PERIOD
    if (coveredPeriodInput) {
      coveredPeriodInput.value = item.dataset.coveredperiod || '';
    }
  }


  /* =========================
     SAVE EVALUATION DATA
  ========================= */
  function saveEvaluation(item) {
    if (!item) return;

    const id = item.dataset.id;
    if (!id) return;

    const getRadio = (name) => {
      const el = document.querySelector(`input[name="${name}"]:checked`);
      return el ? el.value : null;
    };

    window.evaluationData[id] = {
      supplier: item.dataset.supplier || '',
      po: item.dataset.po || '',
      date: item.dataset.date || '',

      criteriaA: getRadio('criteriaA'),
      criteriaB: getRadio('criteriaB'),
      criteriaC: getRadio('criteriaC'),
      criteriaD: getRadio('criteriaD'),

      remarksA: document.getElementById('remarksA')?.value || '',
      remarksB: document.getElementById('remarksB')?.value || '',
      remarksC: document.getElementById('remarksC')?.value || '',
      remarksD: document.getElementById('remarksD')?.value || '',

      preparerName: document.getElementById('preparerName')?.value || '',
      preparerDesignation: document.getElementById('preparerDesignation')?.value || '',
      approverName: document.getElementById('approverName')?.value || '',
      approverDesignation: document.getElementById('approverDesignation')?.value || '',

      preparerSignature: preparerSig?.innerHTML || '',
      approverSignature: approverSig?.innerHTML || '',

      linkedTo: window.activeEvaluationId || null
    };
  }


  /* =========================
     LOAD EVALUATION DATA
  ========================= */
  function loadEvaluation(id) {

    const data = window.evaluationData[id];

    const resetForm = () => {
      document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
      document.querySelectorAll('textarea').forEach(t => t.value = '');

      if (document.getElementById('remarksA')) document.getElementById('remarksA').value = '';
      if (document.getElementById('remarksB')) document.getElementById('remarksB').value = '';
      if (document.getElementById('remarksC')) document.getElementById('remarksC').value = '';
      if (document.getElementById('remarksD')) document.getElementById('remarksD').value = '';

      if (document.getElementById('preparerName')) document.getElementById('preparerName').value = '';
      if (document.getElementById('preparerDesignation')) document.getElementById('preparerDesignation').value = '';
      if (document.getElementById('approverName')) document.getElementById('approverName').value = '';
      if (document.getElementById('approverDesignation')) document.getElementById('approverDesignation').value = '';

      if (preparerSig) preparerSig.innerHTML = 'No signature linked';
      if (approverSig) approverSig.innerHTML = 'No signature linked';
    };

    if (!data) {
      resetForm();
      return;
    }

    const setRadio = (name, value) => {
      if (!value) return;
      const el = document.querySelector(`input[name="${name}"][value="${value}"]`);
      if (el) el.checked = true;
    };

    setRadio('criteriaA', data.criteriaA);
    setRadio('criteriaB', data.criteriaB);
    setRadio('criteriaC', data.criteriaC);
    setRadio('criteriaD', data.criteriaD);

    if (document.getElementById('remarksA')) document.getElementById('remarksA').value = data.remarksA || '';
    if (document.getElementById('remarksB')) document.getElementById('remarksB').value = data.remarksB || '';
    if (document.getElementById('remarksC')) document.getElementById('remarksC').value = data.remarksC || '';
    if (document.getElementById('remarksD')) document.getElementById('remarksD').value = data.remarksD || '';

    if (document.getElementById('preparerName')) document.getElementById('preparerName').value = data.preparerName || '';
    if (document.getElementById('preparerDesignation')) document.getElementById('preparerDesignation').value = data.preparerDesignation || '';
    if (document.getElementById('approverName')) document.getElementById('approverName').value = data.approverName || '';
    if (document.getElementById('approverDesignation')) document.getElementById('approverDesignation').value = data.approverDesignation || '';

    if (preparerSig) preparerSig.innerHTML = data.preparerSignature || 'No signature linked';
    if (approverSig) approverSig.innerHTML = data.approverSignature || 'No signature linked';
  }


  /* =========================
     CLICK HANDLER (MAIN FIXED FLOW)
  ========================= */
  document.querySelectorAll('.evaluation-item').forEach(item => {

    item.addEventListener('click', function (e) {

      // ignore checkbox clicks
      if (e.target.classList.contains('batch-checkbox')) return;

      const current = getActiveItem();

      // save current before switching
      if (current && current !== this) {
        saveEvaluation(current);
      }

      setActiveItem(this);
      updateBasicInfo(this);
      loadEvaluation(this.dataset.id);
    });

  });


  /* =========================
     GLOBAL SAVE FUNCTION
  ========================= */
  window.saveCurrentEvaluation = function () {
    const active = getActiveItem();
    if (active) saveEvaluation(active);
  };

});
</script> --}}
</body>

</html>
