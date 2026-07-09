
<!-- New Evaluation Modal -->
<div id="updateEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-screen overflow-y-auto border border-gray-100">
      <div class="bg-gradient-to-r from-orange-400 to-sky-400 px-8 py-6 rounded-t-xl">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-white">SUPPLIER'S EVALUATION FORM</h3>
            <p class="text-blue-100 text-sm mt-1">Performance Assessment & Rating System</p>
          </div>
          <button id="closeUpdateEvaluationModalBtn" class="text-white hover:text-gray-200 transition-colors">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-close-line text-xl"></i>
            </div>
          </button>
        </div>
      </div>
      <div class="p-8">
        <div class="mb-8">
        </div>
        <div class="mb-8">
          <div class="bg-blue-50 rounded-xl p-6 border-l-4 border-primary">
            <h4 class="text-base font-bold text-primary mb-3 flex items-center">
              <div class="w-5 h-5 flex items-center justify-center mr-2">
                <i class="ri-information-line"></i>
              </div>
              INSTRUCTIONS
            </h4>
            <div class="space-y-2 text-sm text-gray-700">
              <p class="flex items-start">
                <span class="font-bold text-primary mr-2 mt-0.5">1.</span>
                <span>Check the box which corresponds to the supplier's performance based on the Purchase Order/Contract listed above.</span>
              </p>
              <p class="flex items-start">
                <span class="font-bold text-primary mr-2 mt-0.5">2.</span>
                <span>In the Remarks / Specific Comments Column, please provide the details especially incidents/description of the delivery in case it fell beyond what was expected.  </span>
              </p>
              <!-- <p class="flex items-start">
                <span class="font-bold text-primary mr-2 mt-0.5">3.</span>
                <span>When multiple POs are added, each evaluation will be calculated separately and combined for the overall rating.</span>
              </p> -->
            </div>
          </div>
        </div>
        <div id="UpdateevaluationFormsContainer">
          <div class="evaluation-form-item mb-8" data-form-id="1">
            <div class="bg-white border-2 border-primary rounded-xl shadow-lg">
              <div class="bg-gradient-to-r from-primary to-blue-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                  <h4 class="text-lg font-bold text-white flex items-center">
                    <div class="w-5 h-5 flex items-center justify-center mr-2">
                      <i class="ri-file-text-line"></i>
                    </div>
                    EVALUATION FORM
                  </h4>
                  <div class="flex items-center space-x-2">
                    <button class="collapse-toggle text-white hover:text-gray-200 transition-colors">
                      <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-subtract-line"></i>
                      </div>
                    </button>
                    <button class="remove-po-btn text-white hover:text-red-200 transition-colors hidden">
                      <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-close-line"></i>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
              <div class="form-content p-6">
                {{-- <form id="updateEvaluationForm">
                 @csrf
                 @method('PUT') --}}
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <input type="hidden" name="evaluation_id" id="update_evaluation_id">
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">NAME OF SUPPLIER:</label>
                    <input id="update_supplier_name" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Purchase Order / Contract No.:</label>
                    <input id="update_po_no" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Date of Evaluation:</label>
                    <input readonly id="update_date_evaluation" type="date" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
<div>
  <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">
    Covered Period (CY):
  </label>

  <div class="flex items-center gap-4">

    <!-- CY DISPLAY (covered_period) -->
    <input
      id="update_covered_period"
      type="text"
      readonly
      class="w-2/3 border-0 border-b-2 border-gray-300 px-1 py-3 text-base bg-transparent font-medium text-gray-800
             focus:outline-none focus:border-primary">

    <!-- YEAR SELECT (period_year) -->
    <select disabled
      id="update_year"
      class="w-2/3 border-0 border-b-2 border-gray-300 px-1 py-3 text-base bg-transparent font-medium text-gray-800
             focus:outline-none focus:border-primary">
      <option value="">Select Year</option>
    </select>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const yearSelect = document.getElementById("update_year");
  const coveredInput = document.getElementById("update_covered_period");

  if (!yearSelect || !coveredInput) return;

  const currentYear = new Date().getFullYear();

  // Populate years
  for (let y = currentYear - 5; y <= currentYear + 5; y++) {
    const option = document.createElement("option");
    option.value = y;
    option.textContent = y;
    yearSelect.appendChild(option);
  }

  function updateCY(year) {
    coveredInput.value = year ? `CY ${year}` : '';
  }

  yearSelect.addEventListener("change", function () {
    updateCY(this.value);
  });

  // 🔥 THIS IS WHAT updateEvaluation CALLS
  window.setUpdateEvaluationData = function (item) {

    if (!item) return;

    const year = item.period_year;

    if (year) {
      yearSelect.value = year;
      updateCY(year);
    } else {
      yearSelect.value = '';
      coveredInput.value = item.covered_period || '';
    }
  };

});
</script>

                </div>

<div class="form-content p-6">
  <label for="update_office_id" class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">
    Evaluated by (Office Name):
  </label>

    <select id="update_office_id" name="office_id" required
      class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">

      <option value="" disabled>Select Department</option>
    </select>
</div>


            <div class="{{ auth()->user()->isPgso() ? 'hidden' : '' }}">
                <div class="border-2 border-gray-300 rounded-xl mb-8 overflow-hidden shadow-sm">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-gray-400">
                        <th class="border-r border-gray-500 p-4 text-left font-bold text-white uppercase tracking-wide">EVALUATION CRITERIA</th>
                        <th class="p-4 text-left font-bold text-white uppercase tracking-wide">REMARKS / SPECIFIC COMMENTS</th>
                      </tr>
                    </thead>
                        <tbody>
                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">A. PRICE (20%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input type="radio" name="price_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Highly Reasonable <span class="bg-yellow-200 px-1 rounded">(20%)</span></strong><br>• Bid amount is reasonable based on the brand/services delivered.<br>• Pricing is consistent with current market rates (brand or market scooping / historical data)<br>• No competitive</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="price_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Reasonable <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong><br>• Bid amount generally aligns with brand/services delivered.<br>• Minor discrepancies in pricing but still within acceptable market range.<br>• No significant cost or overpricing based on brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="price_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Moderately Reasonable <span class="bg-yellow-200 px-1 rounded">(10%)</span></strong><br>• Some mismatch between bid amount and brand/services delivered.<br>• The bid amount is notably higher than the prevailing market range based on the brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="price_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Not Reasonable <span class="bg-yellow-200 px-1 rounded">(5%)</span></strong><br>• The bid amount is higher than the prevailing market price against the brand/services delivered.</span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_price_1" name="form_remarks_price_1" data-criterion="price1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">B. QUALITY / SERVICE LEVEL (30%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input type="radio" name="quality_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods delivered according to specifications, and acceptable quality <span class="bg-yellow-200 px-1 rounded">(30%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="quality_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods delivered in accordance with specifications, with minor damages, defects, or workmanship issues, which were immediately corrected without affecting functionality or project timeline. <span class="bg-yellow-200 px-1 rounded">(22.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="quality_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods delivered in accordance with specifications and of fair to low quality <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="quality_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods delivered with recurring or significant damages, defects, or workmanship issues, affecting functionality and functionality <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_quality_1" name="form_remarks_quality_1" data-criterion="quality1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">C. CUSTOMER CARE / AFTER SALES SERVICE (25%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input type="radio" name="customercare_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Accessible and easy to contact, responsive to inquiries / complaints, adaptable to certain needs of the end-user</strong> and has competent staff to handle end-user's concerns. <strong><span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="customercare_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - If one (1) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="customercare_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - If any two (2) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="customercare_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - If any three (3) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_customercare_1" name="form_remarks_customercare_1" data-criterion="customercare1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr>
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">D. DELIVERY FULFILLMENT (25%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input type="radio" name="delivery_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods / Services delivered on Time <span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="delivery_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods / Services delivered, One (1) to Five (5) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="delivery_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods / Services delivered, Six (6) to Ten (10) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input type="radio" name="delivery_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods / Services delivered, eleven (11) or more days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="update_form_remarks_delivery_1" name="form_remarks_delivery_1" data-criterion="delivery1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                        </tbody>

                  </table>
                </div>



                <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-4 text-white mb-6">
                    <div class="text-center">
                      <h4 class="text-lg font-bold mb-4">OVERALL RATING CALCULATION</h4>
                      <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-4">
                        <div class="text-3xl font-bold">
                          <span id="update_currentRating">0</span>%
                        </div>
                        <div class="text-sm opacity-90 mt-1">Overall Average Score</div>
                      </div>
                      <div class="flex items-center justify-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Passing Rate</div>
                          <div class="font-bold">60%</div>
                        </div>
                        <div id="update_ratingStatus" class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Status</div>
                          <div class="font-bold" id="update_statusText">Pending</div>
                        </div>
                      </div>
                    </div>

                </div>


              </div>
            </div>
          </div>
        </div>
<!-- Digital Authorization Section -->
<div>
  <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">

    <h4 class="text-lg font-bold text-gray-800 mb-6 pb-3 border-b border-gray-300">
      Digital Authorization
    </h4>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


<!-- ================= LEFT PANEL ================= -->
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

  <!-- HEADER -->
  <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4">
    <h5 class="font-semibold text-white flex items-center text-base">
      <div class="w-8 h-8 flex items-center justify-center mr-3 bg-white/20 rounded-full backdrop-blur-sm">
        <i class="ri-user-line text-lg"></i>
      </div>
      Prepared by (END-USER)
    </h5>
    <p class="text-blue-100 text-xs mt-1 ml-11">
      Authorized evaluator information & facial verification
    </p>
  </div>

  <!-- BODY -->
  <div class="p-5">

    <!-- ================= FORM SECTION ================= -->
    <div id="update_preparedBySection">

      <!-- FULL NAME -->
      <div class="mb-4">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
          Full Name
        </label>

        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
            <i class="ri-user-3-line"></i>
          </span>

          <input
            id="update_full_name"
            type="text"
            placeholder="Enter full name"
            class="w-full border border-gray-300 rounded-xl pl-11 pr-4 py-3 text-sm
                   focus:outline-none focus:ring-4 focus:ring-blue-100
                   focus:border-blue-500 transition-all duration-200">
        </div>
      </div>

      <!-- DESIGNATION -->
      <div class="mb-5">
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
          Designation
        </label>

        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
            <i class="ri-briefcase-line"></i>
          </span>

          <input
            id="update_designation"
            type="text"
            placeholder="Enter designation"
            class="w-full border border-gray-300 rounded-xl pl-11 pr-4 py-3 text-sm
                   focus:outline-none focus:ring-4 focus:ring-blue-100
                   focus:border-blue-500 transition-all duration-200">
        </div>
      </div>

      <!-- USER CONTEXT -->


      <input type="hidden" id="update_user_id">

      <!-- SIGNATURE BUTTON -->
      @if(auth()->user()->role === 'end_user')
      <button
        id="update_captureEvaluatorBtn"
        type="button"
        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600
               hover:from-blue-700 hover:to-indigo-700
               text-white px-4 py-3 rounded-xl font-medium text-sm
               shadow-md hover:shadow-lg transition-all duration-200
               flex items-center justify-center">

        <i class="ri-ink-bottle-line mr-2 text-lg"></i>
        Insert Existing Signature
      </button>
      @endif

    </div>

    <!-- ================= CAPTURED SECTION ================= -->
    <div id="update_evaluatorCaptured" class="hidden">

      <!-- PROFILE CARD -->
      <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">

        <div class="flex items-center gap-4">

          <!-- SIGNATURE IMAGE -->
          <div class="relative">
            <img
              id="update_evaluatorSignature"
              class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-md">

            <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
              <i class="ri-check-line text-white text-sm"></i>
            </div>
          </div>

          <!-- INFO -->
          <div class="flex-1">

            <div class="mb-3">
              <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
                Full Name
              </p>

              <h6
                id="update_evaluatorName"
                class="text-base font-bold text-gray-800 leading-tight">
              </h6>
            </div>

            <div>
              <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
                Designation
              </p>

              <p
                id="update_evaluatorDesignation"
                class="text-sm text-gray-700 font-medium">
              </p>
            </div>

          </div>

        </div>

        <!-- ================= STATUS (SIG SAFE AREA) ================= -->
        <div id="update_verificationStatus"
             class="mt-5 rounded-xl px-4 py-3 flex items-center border bg-gray-50 border-gray-200">

          <div id="update_verificationIcon"
               class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center mr-3">
            <i class="ri-time-line text-white"></i>
          </div>

          <div>
            <p id="update_verificationTitle"
               class="text-sm font-semibold text-gray-700">
              Signature Not Linked
            </p>

            <p id="update_verificationDesc"
               class="text-xs text-gray-500">
              No signature has been inserted or linked yet
            </p>
          </div>

        </div>

        <!-- ACTION BUTTONS -->

        <div class="<?= auth()->user()->role === 'end_user' ? 'flex' : 'hidden' ?> gap-3 mt-5">

          <button
            id="update_end_userchangeEvaluatorBtn"
            class="hidden flex-1 bg-blue-600 hover:bg-blue-700
                   text-white py-2.5 rounded-xl text-sm font-medium
                   transition-all duration-200 flex items-center justify-center">

            <i class="ri-edit-line mr-2"></i>
            Change
          </button>

          <button
            id="update_cancelChangeEvaluatorBtn"
            class="hidden flex-1 bg-gray-200 hover:bg-gray-300
                   text-gray-700 py-2.5 rounded-xl text-sm font-medium
                   transition-all duration-200 flex items-center justify-center">

            <i class="ri-close-line mr-2"></i>
            Cancel
          </button>

        </div>


      </div>

    </div>

  </div>
</div>

<!-- ================= RIGHT PANEL : HEAD AUTHORIZATION ================= -->
<div id="headAuthorizationPanel"
     class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
     data-has-file="false">

  <!-- HEADER -->
  <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-5 py-4">

    <h5 class="font-semibold text-white flex items-center text-base">
      <div class="w-8 h-8 flex items-center justify-center mr-3 bg-white/20 rounded-full backdrop-blur-sm">
        <i class="ri-shield-user-line text-lg"></i>
      </div>

      Head Authorization
    </h5>

    <p class="text-indigo-100 text-xs mt-1 ml-11">
      Office head approval & signature verification
    </p>

  </div>

  <!-- BODY -->
  <div class="p-5">

    <!-- PROFILE CARD -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">

      <div class="flex items-center gap-4">

        <!-- IMAGE -->
        <div class="relative shrink-0">

          <img
            id="head_signatureImage"
            src="https://ui-avatars.com/api/?name=Head+User&background=2563eb&color=fff"
            alt="Head Signature"
            class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-md">

          <!-- VERIFIED BADGE -->
          <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 rounded-full border-2 border-white flex items-center justify-center">
            <i class="ri-shield-check-line text-white text-sm"></i>
          </div>

        </div>

        <!-- INFO -->
        <div class="flex-1">
            <input type="hidden" id="update_head_user_id">

          <!-- NAME -->
          <div class="mb-4">

            <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
              Approved by
            </p>

            <h6 id="head_name_display"></h6>

          </div>

          <!-- DESIGNATION -->
          <div>

            <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">
              Designation
            </p>

            <p id="head_designation_display"></p>

          </div>

        </div>

      </div>

      <!-- ================= STATUS ================= -->
      <div id="headSubmitStatus"
           class="mt-5 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 flex items-center">

        <div id="headStatusIconWrapper"
             class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center mr-3">

          <i id="headStatusIcon" class="ri-time-line text-white"></i>

        </div>

        <div>
          <p id="headStatusTitle"
             class="text-sm font-semibold text-gray-700">
            Signature Not Linked
          </p>

          <p id="headStatusMessage"
             class="text-xs text-gray-500">
            Awaiting head signature linking
          </p>
        </div>

      </div>

      <!-- ================= ACTIONS ================= -->
      <div class="<?= in_array(auth()->user()->role, ['head', 'presentative_staff']) ? 'block' : 'hidden' ?> mt-6 space-y-3">

        <button id="head_linkSignatureBtn"
                type="button"
                class="hidden w-full bg-gradient-to-r from-indigo-600 to-blue-600
                       hover:from-indigo-700 hover:to-blue-700 text-white
                       px-4 py-3 rounded-xl font-medium transition-all duration-200
                       shadow-md hover:shadow-lg">

          <i class="ri-link mr-2 text-lg"></i>
          Link Signature
        </button>

      </div>

    </div>

  </div>
</div>

    </div>
  </div>
</div>

          <div class="flex justify-end space-x-4 mt-8">
            <button id="cancelUpdateEvaluationModalBtn" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50">Cancel</button>
            <button id="submitUpdateEvaluationBtn" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600">Submit Evaluation</button>
          </div>
        </div>
        {{-- </form> --}}
      </div>
    </div>
  </div>
</div>





<script>
  window.authUser = {
    id: {{ auth()->id() }},
    role: "{{ auth()->user()->role }}",
    name: "{{ auth()->user()->name }}",
    designation: "{{ auth()->user()->designation }}"
  };
</script>


<script>
// ===============================
// GLOBAL VARIABLES
// ===============================
let currentEditEvaluationId = null;

// Criteria mapping: backend ID → HTML name
const criteriaMap = {
    1: 'price',
    2: 'quality',
    3: 'customercare',
    4: 'delivery'
};


// SIGNATURE MODULE
document.addEventListener('DOMContentLoaded', function () {

  const insertBtn = document.getElementById('update_captureEvaluatorBtn');

  const formSection = document.getElementById('update_preparedBySection');
  const capturedSection = document.getElementById('update_evaluatorCaptured');

  const fullName = document.getElementById('update_full_name');
  const designation = document.getElementById('update_designation');

  const img = document.getElementById('update_evaluatorSignature');
  const name = document.getElementById('update_evaluatorName');
  const desig = document.getElementById('update_evaluatorDesignation');

  const changeBtn = document.getElementById('update_end_userchangeEvaluatorBtn');
  const cancelBtn = document.getElementById('update_cancelChangeEvaluatorBtn');

  const userIdHidden = document.getElementById('update_user_id');

  const statusBox = document.getElementById('update_verificationStatus');
  const statusIcon = document.getElementById('update_verificationIcon');
  const statusTitle = document.getElementById('update_verificationTitle');
  const statusDesc = document.getElementById('update_verificationDesc');

  if (!insertBtn) return;

  let original = null;
  let isLinked = false;

  function setStatus(linked) {

    if (linked) {
      statusBox.className = "mt-5 rounded-xl px-4 py-3 flex items-center border bg-green-50 border-green-200";
      statusIcon.className = "w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mr-3";
      statusIcon.innerHTML = `<i class="ri-check-line text-white"></i>`;
      statusTitle.textContent = "Signature Linked";
      statusDesc.textContent = "Existing signature successfully linked";
      isLinked = true;
    } else {
      statusBox.className = "mt-5 rounded-xl px-4 py-3 flex items-center border bg-gray-50 border-gray-200";
      statusIcon.className = "w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center mr-3";
      statusIcon.innerHTML = `<i class="ri-time-line text-white"></i>`;
      statusTitle.textContent = "Signature Not Linked";
      statusDesc.textContent = "No signature inserted yet";
      isLinked = false;
    }
  }

  function showCaptured() {
    formSection.classList.add('hidden');
    capturedSection.classList.remove('hidden');
  }

  function showForm() {
    formSection.classList.remove('hidden');
    capturedSection.classList.add('hidden');
  }

  function getUserId() {
    return window.authUser?.id || userIdHidden?.value || null;
  }

  function validate() {
    if (!fullName.value.trim()) return alert("Full name required"), false;
    if (!designation.value.trim()) return alert("Designation required"), false;
    return true;
  }

  setStatus(false);

  // =========================
  // INSERT SIGNATURE
  // =========================
  insertBtn.addEventListener('click', async () => {

    if (!validate()) return;

    const userId = getUserId();
    if (!userId) return alert("No user selected");

    try {
      const url = `/signature/${userId}`;

      const res = await fetch(url, { method: 'HEAD' });
      if (!res.ok) return alert("No signature found");

      original = {
        name: name.textContent,
        designation: desig.textContent,
        image: img.src
      };

      name.textContent = fullName.value;
      desig.textContent = designation.value;
      img.src = url + "?t=" + Date.now();

      showCaptured();
      changeBtn.classList.remove('hidden');
      cancelBtn.classList.add('hidden');

      setStatus(true);

    } catch (e) {
      console.error(e);
      alert("Failed to load signature");
      setStatus(false);
    }
  });

  // =========================
  // CHANGE
  // =========================
  changeBtn?.addEventListener('click', () => {
    fullName.value = name.textContent;
    designation.value = desig.textContent;

    showForm();
    changeBtn.classList.add('hidden');
    cancelBtn.classList.remove('hidden');

    setStatus(false);
  });

  // =========================
  // CANCEL
  // =========================
  cancelBtn?.addEventListener('click', () => {

    if (original) {
      name.textContent = original.name;
      desig.textContent = original.designation;
      img.src = original.image;
    }

    showCaptured();

    cancelBtn.classList.add('hidden');
    changeBtn.classList.remove('hidden');

    setStatus(true);
  });

});
// END

// ===============================
// FETCH & POPULATE UPDATE FORM
// ===============================
async function updateEvaluation(id) {

    try {

        const response = await safeFetch(`/showupdate/${id}`);

        if (!response.ok) {
            throw new Error('Failed to fetch evaluation data');
        }

        const data = await response.json();

        const evaluation = data.evaluation;
        const evaluator = data.prepared_by;

        currentEditEvaluationId = id;

        // =====================================
        // HEAD AUTHORIZATION
        // =====================================
        const headPanel = document.getElementById('headAuthorizationPanel');
        const headLinkBtn = document.getElementById('head_linkSignatureBtn');

        const headNameDisplay = document.getElementById('head_name_display');
        const headDesignationDisplay = document.getElementById('head_designation_display');
        const headSignatureImage = document.getElementById('head_signatureImage');
        document.getElementById('update_head_user_id').value =
            data.head_authorization?.user_id || '';

        const headStatusTitle = document.getElementById('headStatusTitle');
        const headStatusMessage = document.getElementById('headStatusMessage');
        const headStatusIcon = document.getElementById('headStatusIcon');
        const headStatusIconWrapper = document.getElementById('headStatusIconWrapper');

        // values
        headNameDisplay.textContent =
            data.head_authorization?.head_name?.trim() || 'Not Assigned';

        headDesignationDisplay.textContent =
            data.head_authorization?.designation?.trim() || 'Not Assigned';

        // panel visibility
        if (data.show_head_panel) {
            headPanel.classList.remove('hidden');
        } else {
            headPanel.classList.add('hidden');
        }

        // =====================================
        // DEFAULT STATE (NOT LINKED)
        // =====================================
        if (data.head_authorization?.linked) {

            headSignatureImage.src =
                data.head_authorization.signature_url ||
                data.head_authorization.image ||
                'https://ui-avatars.com/api/?name=Head+User&background=2563eb&color=fff';

            headStatusTitle.textContent = 'Signature Linked';

            headStatusMessage.textContent =
                'Head approval already exists.';

            headStatusIconWrapper.className =
                'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-green-500';

            headStatusIcon.className =
                'ri-check-line text-white';

            headLinkBtn.classList.add('hidden');

        } else {

            headSignatureImage.src =
                'https://ui-avatars.com/api/?name=Head+User&background=2563eb&color=fff';

            headStatusTitle.textContent = 'Signature Not Linked';

            headStatusMessage.textContent =
                'Waiting for head signature linking';

            headStatusIconWrapper.className =
                'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-blue-600';

            headStatusIcon.className =
                'ri-time-line text-white';

            if (data.show_head_panel) {
                headLinkBtn.classList.remove('hidden');
            }
        }

        // =====================================
        // LINK SIGNATURE BUTTON
        // =====================================
headLinkBtn.onclick = async function () {

    try {

        const currentUserId = window.authUser.id;
        const currentUserRole = window.authUser.role;

        let signatureUserId = data.head_authorization?.user_id;

        if (currentUserRole === 'presentative_staff') {
            signatureUserId = currentUserId;
        }

        if (!signatureUserId) {
            alert('No valid user for signature.');
            return;
        }

        const signatureUrl = `/signature/${signatureUserId}`;

        const res = await fetch(signatureUrl, { method: 'HEAD' });

        if (!res.ok) {
            alert('No signature found for head.');
            return;
        }

if (currentUserRole === 'presentative_staff') {

    // NAME = representative staff
    headNameDisplay.textContent =
        window.authUser.name || 'Representative Staff';

    // DESIGNATION = representative staff designation + acting label
    const userDesignation = window.authUser.designation || 'Representative Staff';

    headDesignationDisplay.textContent =
        `${userDesignation} / Acting for Head`;
}

        headSignatureImage.src = signatureUrl + '?t=' + Date.now();

        headStatusTitle.textContent =
            currentUserRole === 'presentative_staff'
                ? 'Representative Staff Linked'
                : 'Head Signature Linked';

        headStatusMessage.textContent =
            currentUserRole === 'presentative_staff'
                ? 'Signature linked by representative staff acting for head approval.'
                : 'Head signature successfully linked.';

        headStatusIconWrapper.className =
            'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-green-500';

        headStatusIcon.className = 'ri-check-line text-white';

        headLinkBtn.classList.add('hidden');

    } catch (err) {
        console.error(err);
        alert('Failed to load signature.');
    }
};

        // =====================================
        // OFFICE DROPDOWN
        // =====================================
        const officeSelect = document.getElementById('update_office_id');

        officeSelect.innerHTML =
            '<option value="" disabled>Select Department</option>';

        data.offices.forEach(office => {
            const option = document.createElement('option');
            option.value = office.id;
            option.textContent = office.name;

            if (office.id == evaluation.office_id) {
                option.selected = true;
            }

            officeSelect.appendChild(option);
        });

        // =====================================
        // BASIC FIELDS
        // =====================================
        const basicFields = [
            'supplier_name',
            'po_no',
            'date_evaluation',
            'covered_period'
        ];

        basicFields.forEach(key => {

            const el = document.getElementById(`update_${key}`);
            if (!el) return;

            if (key === 'date_evaluation' && evaluation[key]) {
                const date = new Date(evaluation[key]);
                el.value = date.toISOString().split('T')[0];
            } else {
                el.value = evaluation[key] ?? '';
            }
        });

        // =====================================
        // ROLE ACCESS
        // =====================================
        const role = window.userRole;
        const requestStatus = evaluation.latest_request_status;

        const canEdit =
            role === 'pgso' ||
            role === 'administrator' ||
            (role === 'end_user' && requestStatus === 'approved') ||
            (role === 'head' && requestStatus === 'approved');

        [
            'update_supplier_name',
            'update_po_no',
            'update_date_evaluation',
            'update_covered_period',
            'update_office_id'
        ].forEach(id => {

            const el = document.getElementById(id);
            if (!el) return;

            if (canEdit) {
                el.removeAttribute('disabled');
            } else {
                el.setAttribute('disabled', true);
            }
        });

        // =====================================
        // RESET MODAL INPUTS
        // =====================================
        const modal = document.getElementById('updateEvaluationModal');

        modal.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
        modal.querySelectorAll('textarea').forEach(t => t.value = '');

        // =====================================
        // CRITERIA SCORES
        // =====================================
        if (evaluation.criteria_scores?.length) {

            evaluation.criteria_scores.forEach(score => {

                const criteriaName = criteriaMap[score.criteria_id];
                if (!criteriaName) return;

                const radio = modal.querySelector(
                    `input[name="${criteriaName}_1"][value="${score.number_rating}"]`
                );

                if (radio) radio.checked = true;

                const remarksField = document.getElementById(
                    `update_form_remarks_${criteriaName}_1`
                );

                if (remarksField) {
                    remarksField.value = score.remarks ?? '';
                }
            });
        }

        calculateUpdateRating();
        attachUpdateRatingListeners();

        // =====================================
        // SIGNATURE SECTION
        // =====================================
        const sigCaptured = document.getElementById('update_evaluatorCaptured');
        const sigForm = document.getElementById('update_preparedBySection');
        const sigChangeBtn = document.getElementById('update_end_userchangeEvaluatorBtn');

        const sigName = document.getElementById('update_evaluatorName');
        const sigDesignation = document.getElementById('update_evaluatorDesignation');
        const sigImage = document.getElementById('update_evaluatorSignature');

        const sigStatusTitle = document.getElementById('update_verificationTitle');
        const sigStatusDesc = document.getElementById('update_verificationDesc');
        const sigStatusBox = document.getElementById('update_verificationStatus');
        const sigStatusIcon = document.getElementById('update_verificationIcon');

        const hasSignature = !!data.prepared_by_signature_url;
        const hasEvaluator =
            evaluator?.full_name ||
            evaluator?.designation ||
            hasSignature;

        if (hasEvaluator) {

            sigCaptured.classList.remove('hidden');
            sigForm.classList.add('hidden');
            sigChangeBtn.classList.remove('hidden');

            sigName.textContent = evaluator?.full_name ?? '';
            sigDesignation.textContent = evaluator?.designation ?? '';

            sigImage.src = hasSignature
                ? data.prepared_by_signature_url
                : '/images/default-avatar.png';

            if (hasSignature) {

                sigStatusTitle.textContent = 'Signature Linked';
                sigStatusDesc.textContent = 'Existing user signature successfully linked.';

                sigStatusBox.className =
                    'mt-5 rounded-xl px-4 py-3 flex items-center border bg-green-50 border-green-200';

                sigStatusIcon.className =
                    'w-8 h-8 rounded-full bg-green-500 flex items-center justify-center mr-3';

                sigStatusIcon.innerHTML = '<i class="ri-check-line text-white"></i>';

            } else {

                sigStatusTitle.textContent = 'Signature Missing';
                sigStatusDesc.textContent = 'Evaluator exists but no linked signature found.';

                sigStatusBox.className =
                    'mt-5 rounded-xl px-4 py-3 flex items-center border bg-yellow-50 border-yellow-200';

                sigStatusIcon.className =
                    'w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center mr-3';

                sigStatusIcon.innerHTML =
                    '<i class="ri-error-warning-line text-white"></i>';
            }

        } else {

            sigCaptured.classList.add('hidden');
            sigForm.classList.remove('hidden');
            sigChangeBtn.classList.add('hidden');
        }

        // =====================================
        // OPEN MODAL
        // =====================================
        modal.classList.remove('hidden');

    } catch (error) {

        console.error('Error loading evaluation:', error);

        Swal.fire(
            'Oops!',
            'Failed to load evaluation data.',
            'error'
        );
    }
}


// ===============================
// AUTO-CALCULATE SCORE & STATUS
// ===============================
function calculateUpdateRating() {
    const scores = {};

    Object.entries(criteriaMap).forEach(([id, name]) => {
        const radios = document.querySelectorAll(`#updateEvaluationModal input[name="${name}_1"]`);
        let value = 0;
        radios.forEach(r => { if(r.checked) value = parseFloat(r.value); });
        scores[id] = value;
    });

    // Weighted total
    const total = parseFloat(((5*scores[1]) + (7.5*scores[2]) + (6.25*scores[3]) + (6.25*scores[4])).toFixed(2));
    document.getElementById('update_currentRating').innerText = total;

    const status = Object.values(scores).every(v => v !== 0) ? (total >= 60 ? 'Pass' : 'Fail') : 'Pending';
    document.getElementById('update_statusText').innerText = status;
}

// ===============================
// ATTACH LISTENERS
// ===============================
function attachUpdateRatingListeners() {
    const modal = document.getElementById('updateEvaluationModal');
    modal.querySelectorAll('input[type="radio"]').forEach(r => r.addEventListener('change', calculateUpdateRating));
    modal.querySelectorAll('textarea').forEach(t => t.addEventListener('input', calculateUpdateRating));
}

// ===============================
// SAFE INPUT GETTER
// ===============================
function safeValue(id, trim = true) {
    const el = document.getElementById(id);
    if(!el) return '';
    return trim ? el.value.trim() : el.value;
}
// ===============================
// SUBMIT UPDATE (FINAL)
// ===============================
async function submitUpdateEvaluation(id) {
    try {

        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this evaluation?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel'
        });

        if (!confirmResult.isConfirmed) return;

        // =====================================================
        // AUTH FILE
        // =====================================================
        const useExisting = window.__useExistingFile === true;

        let authorizationFile = null;

        if (useExisting && window.__pdfId) {
            authorizationFile = {
                mode: 'existing',
                pdf_id: window.__pdfId
            };
        } else if (window.__approvedPdfFile) {
            authorizationFile = {
                mode: 'new',
                file: window.__approvedPdfFile
            };
        }

        // =====================================================
        // EVALUATOR
        // =====================================================
        const evaluator = {
            user_id: window.authUser?.id || null,
            full_name: document.getElementById('update_evaluatorName')?.textContent.trim() || '',
            designation: document.getElementById('update_evaluatorDesignation')?.textContent.trim() || '',
            image: document.getElementById('update_evaluatorSignature')?.src || null,
            role: 'prepared by'
        };

        // =====================================================
        // HEAD (FINAL FIX)
        // =====================================================
        const currentRole = window.authUser?.role;

        const head = {
            user_id: document.getElementById('update_head_user_id')?.value || null,
            full_name: document.getElementById('head_name_display')?.textContent.trim() || '',
            designation: document.getElementById('head_designation_display')?.textContent.trim() || '',
            image: document.getElementById('head_signatureImage')?.src || null,

            // always logical role in workflow
            role: 'head',

            // ✅ NEW: WHO ACTUALLY ACTED
            acting_user_id: window.authUser?.id || null,
            acting_role: currentRole
        };

        // =====================================================
        // STATUS
        // =====================================================
        let status = document.querySelector(`[data-id="${id}"]`)?.dataset.status || 'pending';

        if (currentRole === 'head' || currentRole === 'presentative_staff') {
            status = 'submitted';
        }

        // =====================================================
        // BASE PAYLOAD
        // =====================================================
        const evaluationData = {
            supplier_name: safeValue('update_supplier_name'),
            po_no: safeValue('update_po_no'),
            date_evaluation: safeValue('update_date_evaluation', false),
            covered_period: safeValue('update_covered_period'),
            period_year: safeValue('update_year'),
            office_id: safeValue('update_office_id'),

            criteria_scores: [],
            evaluator,
            head,
            status,
            authorization_file: authorizationFile
        };

        // =====================================================
        // CRITERIA SCORES
        // =====================================================
        Object.entries(criteriaMap).forEach(([cid, name]) => {

            let selectedValue = 0;

            document.querySelectorAll(
                `#updateEvaluationModal input[name="${name}_1"]`
            ).forEach(r => {
                if (r.checked) selectedValue = parseFloat(r.value);
            });

            const remarks = document
                .getElementById(`update_form_remarks_${name}_1`)
                ?.value?.trim() || '';

            evaluationData.criteria_scores.push({
                criteria_id: parseInt(cid),
                number_rating: selectedValue,
                remarks
            });
        });

        // =====================================================
        // VALIDATION
        // =====================================================
        const errors = [];

        if (!evaluationData.supplier_name) errors.push('Supplier Name is required');
        if (!evaluationData.po_no) errors.push('PO Number is required');
        if (!evaluationData.date_evaluation) errors.push('Evaluation Date is required');
        if (!evaluationData.covered_period) errors.push('Covered Period is required');
        if (!evaluationData.office_id) errors.push('Department is required');

        if (!evaluationData.evaluator.full_name) errors.push('Prepared By Name is required');
        if (!evaluationData.evaluator.designation) errors.push('Prepared By Designation is required');

        if (!evaluationData.head.full_name) errors.push('Head Name is required');
        if (!evaluationData.head.designation) errors.push('Head Designation is required');

        evaluationData.criteria_scores.forEach(c => {
            if (!c.number_rating) {
                errors.push(`Rating is required for criteria ID ${c.criteria_id}`);
            }
        });

        if (errors.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                html: `<div style="text-align:left">${errors.map(e => `• ${e}`).join('<br>')}</div>`
            });
            return;
        }

        // =====================================================
        // REQUEST
        // =====================================================
        const response = await safeFetch(`/updateevaluations/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(evaluationData)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to update evaluation');
        }

        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: result.message || 'Evaluation updated.',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });

    } catch (error) {
        console.error('Error updating evaluation:', error);

        Swal.fire(
            'Error!',
            error.message || 'An error occurred while updating the evaluation.',
            'error'
        );
    }
}

// ===============================
// BUTTON EVENTS
// ===============================
document.getElementById('submitUpdateEvaluationBtn')?.addEventListener('click', () => {
    if(currentEditEvaluationId) submitUpdateEvaluation(currentEditEvaluationId);
});
document.getElementById('cancelUpdateEvaluationModalBtn')?.addEventListener('click', () => {
    document.getElementById('updateEvaluationModal').classList.add('hidden');
});
document.getElementById('closeUpdateEvaluationModalBtn')?.addEventListener('click', () => {
    document.getElementById('updateEvaluationModal').classList.add('hidden');
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const startSelect = document.getElementById("update_start_month");
    const endSelect   = document.getElementById("update_end_month");
    const yearSelect  = document.getElementById("update_year");
    const output      = document.getElementById("update_covered_period");

    if (!startSelect || !endSelect || !yearSelect || !output) return;

    const months = [
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
    ];

    // =========================
    // CLEAR FIRST (IMPORTANT FIX)
    // =========================
    startSelect.innerHTML = `<option value="">Start Month</option>`;
    endSelect.innerHTML   = `<option value="">End Month</option>`;
    yearSelect.innerHTML   = `<option value="">Year</option>`;

    // =========================
    // POPULATE MONTHS (1–12 FIXED)
    // =========================
    months.forEach((month, index) => {
        const value = index + 1; // 🔥 FIX: 1–12 instead of 0–11

        startSelect.innerHTML += `<option value="${value}">${month}</option>`;
        endSelect.innerHTML   += `<option value="${value}">${month}</option>`;
    });

    // =========================
    // POPULATE YEARS
    // =========================
    const currentYear = new Date().getFullYear();

    for (let y = currentYear - 5; y <= currentYear + 5; y++) {
        yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
    }

    // =========================
    // UPDATE FUNCTION
    // =========================
    function updateCoveredPeriod() {

        const start = parseInt(startSelect.value);
        const end   = parseInt(endSelect.value);
        const year  = yearSelect.value;

        if (!start || !end || !year) {
            output.value = "";
            return;
        }

        if (end < start) {
            output.value = "Invalid range";
            return;
        }

        const startName = months[start - 1]; // 🔥 FIX OFFSET
        const endName   = months[end - 1];

        output.value = `${startName}–${endName} ${year}`;
    }

    // =========================
    // LISTENERS
    // =========================
    startSelect.addEventListener("change", updateCoveredPeriod);
    endSelect.addEventListener("change", updateCoveredPeriod);
    yearSelect.addEventListener("change", updateCoveredPeriod);

});
</script>




