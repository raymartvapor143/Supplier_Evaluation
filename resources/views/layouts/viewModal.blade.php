<!-- New Evaluation Modal -->
<div id="viewEvaluationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-screen overflow-y-auto border border-gray-100">
      <div class="sticky top-0 z-50 bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 rounded-t-xl">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-white">SUPPLIER'S EVALUATION FORM</h3>
            <p class="text-blue-100 text-sm mt-1">Performance Assessment & Rating System</p>
          </div>
          <button id="closeViewEvaluationModalBtn" class="text-white hover:text-gray-200 transition-colors">
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
                <span>In the Remarks / Specific Comments Column, please provide the details especially incidents/description of the delivery in case it fell beyond what was expected.</span>
              </p>
              <p class="flex items-start">
                {{-- <span class="font-bold text-primary mr-2 mt-0.5">3.</span>
                <span>When multiple POs are added, each evaluation will be calculated separately and combined for the overall rating.</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div id="viewevaluationFormsContainer">
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
                <div class="grid grid-cols-2 gap-6 mb-6">
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">NAME OF SUPPLIER:</label>
                    <input id="view_supplier_name" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Purchase Order / Contract No.:</label>
                    <input id="view_po_no" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Date of Evaluation:</label>
                    <input id="view_date_evaluation" type="date" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                  <div>
                    <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">Covered Period:</label>
                    <input id="view_covered_period" type="text" class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800">
                  </div>
                </div>
                <div class="mb-6">
                  <label class="text-sm font-semibold text-gray-700 mb-2 block uppercase tracking-wide">
                    Evaluated by (Office Name):
                  </label>


                      <!-- Non-admin: readonly input pre-filled with their department -->
                      <input id="view_office_name" type="text"
                             class="w-full border-0 border-b-2 border-gray-300 px-1 py-3 text-base
                                    focus:outline-none focus:border-primary bg-transparent font-medium text-gray-800" value="">

                  </div>

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
                                    <input id="view_price_1_option_4" type="radio" name="price_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Highly Reasonable <span class="bg-yellow-200 px-1 rounded">(20%)</span></strong><br>• Bid amount is reasonable based on the brand/services delivered.<br>• Pricing is consistent with current market rates (brand or market scooping / historical data)<br>• No competitive</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_price_1_option_3" type="radio" name="price_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Reasonable <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong><br>• Bid amount generally aligns with brand/services delivered.<br>• Minor discrepancies in pricing but still within acceptable market range.<br>• No significant cost or overpricing based on brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_price_1_option_2" type="radio" name="price_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Moderately Reasonable <span class="bg-yellow-200 px-1 rounded">(10%)</span></strong><br>• Some mismatch between bid amount and brand/services delivered.<br>• The bid amount is notably higher than the prevailing market range based on the brand/services delivered.</span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_price_1_option_1" type="radio" name="price_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Not Reasonable <span class="bg-yellow-200 px-1 rounded">(5%)</span></strong><br>• The bid amount is higher than the prevailing market price against the brand/services delivered.</span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_price_1" name="form_remarks_price_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">B. QUALITY / SERVICE LEVEL (30%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_4" type="radio" name="quality_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods delivered according to specifications, and acceptable quality <span class="bg-yellow-200 px-1 rounded">(30%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_3" type="radio" name="quality_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods delivered in accordance with specifications, with minor damages, defects, or workmanship issues, which were immediately corrected without affecting functionality or project timeline. <span class="bg-yellow-200 px-1 rounded">(22.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_2" type="radio" name="quality_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods delivered in accordance with specifications and of fair to low quality <span class="bg-yellow-200 px-1 rounded">(15%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_quality_1_option_1" type="radio" name="quality_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods delivered with recurring or significant damages, defects, or workmanship issues, affecting functionality and functionality <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_quality_1" name="form_remarks_quality_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr class="border-b border-gray-400">
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">C. CUSTOMER CARE / AFTER SALES SERVICE (25%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_4" type="radio" name="customercare_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Accessible and easy to contact, responsive to inquiries / complaints, adaptable to certain needs of the end-user</strong> and has competent staff to handle end-user's concerns. <strong><span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_3" type="radio" name="customercare_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - If one (1) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_2" type="radio" name="customercare_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - If any two (2) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_customercare_1_option_1" type="radio" name="customercare_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - If any three (3) of the details given in item #4 is lacking <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_customercare_1" name="form_remarks_customercare_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
                        </td>
                          </tr>

                          <tr>
                            <td class="border-r border-gray-400 p-3 align-top">
                              <div class="mb-3">
                                <div class="font-medium mb-2">D. DELIVERY FULFILLMENT (25%)</div>
                                <div class="space-y-1 text-xs">
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_4" type="radio" name="delivery_1" value="4" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>4 - Goods / Services delivered on Time <span class="bg-yellow-200 px-1 rounded">(25%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_3" type="radio" name="delivery_1" value="3" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>3 - Goods / Services delivered, One (1) to Five (5) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(18.75%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_2" type="radio" name="delivery_1" value="2" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>2 - Goods / Services delivered, Six (6) to Ten (10) days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(12.5%)</span></strong></span>
                                  </label>
                                  <label class="flex items-start">
                                    <input id="view_delivery_1_option_1" type="radio" name="delivery_1" value="1" class="mt-1 mr-2 w-5 h-5 flex-shrink-0">
                                    <span><strong>1 - Goods / Services delivered, eleven (11) or more days after the expiration of the delivery period <span class="bg-yellow-200 px-1 rounded">(6.25%)</span></strong></span>
                                  </label>
                                </div>
                              </div>
                            </td>
                        <td class="p-3 align-top">
                            <textarea id="view_form_remarks_delivery_1" name="form_remarks_delivery_1" class="w-full h-32 border border-gray-300 p-2 text-xs resize-none"></textarea>
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
                          <span id="view_currentRating">0</span>%
                        </div>
                        <div class="text-sm opacity-90 mt-1">Overall Average Score</div>
                      </div>
                      <div class="flex items-center justify-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Passing Rate</div>
                          <div class="font-bold">60%</div>
                        </div>
                        <div id="view_ratingStatus" class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                          <div class="text-xs opacity-90">Status</div>
                          <div class="font-bold" id="view_statusText">Pending</div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<!-- ================= DIGITAL AUTHORIZATION ================= -->
<div class="bg-white border border-slate-200 rounded-3xl shadow-lg p-6 md:p-8">

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        <!-- ================= PREPARED BY ================= -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">

            <!-- HEADER -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">

                <div class="flex items-center justify-between">

                    <div class="flex items-center">

                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mr-3">
                            <i class="ri-quill-pen-line text-xl text-white"></i>
                        </div>

                        <div>

                            <h5 class="text-lg font-semibold text-white">
                                Prepared by (END-USER)
                            </h5>

                            <p class="text-sm text-emerald-100">
                                Digitally signed and verified evaluator information
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="p-6">

                <div
                    id="view_preparedBySection"
                    class="bg-slate-50 border border-slate-200 rounded-3xl p-6">

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 max-w-sm mx-auto">

                        <!-- SIGNATURE -->
                        <div class="h-28 flex items-center justify-center">

                            <img
                                id="view_preparedByImage"
                                src=""
                                alt="Digital Signature"
                                class="max-h-24 max-w-full object-contain">

                        </div>

                        <!-- DETAILS -->
                        <div class="border-t border-slate-300 mt-5 pt-5 text-center">

                            <h4
                                id="view_preparedByName"
                                class="text-lg font-semibold text-slate-900">
                                -
                            </h4>

                            <p
                                id="view_preparedByDesignation"
                                class="text-sm text-slate-500 mt-1">
                                -
                            </p>

                            <div class="flex items-center justify-center gap-2 mt-4 text-sm text-emerald-600">

                                <i class="ri-time-line"></i>

                                <span id="view_preparedByDate">
                                    -
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ================= HEAD AUTHORIZATION ================= -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">

            <!-- HEADER -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">

                <div class="flex items-center">

                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center mr-3">
                        <i class="ri-shield-check-line text-xl text-white"></i>
                    </div>

                    <div>

                        <h5 class="text-lg font-semibold text-white">
                            Head Authorization
                        </h5>

                        <p class="text-sm text-blue-100">
                            Final approval and digital authorization
                        </p>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="p-6">

                <div
                    id="view_headSection"
                    class="bg-slate-50 border border-slate-200 rounded-3xl p-6">

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 max-w-sm mx-auto">

                        <!-- HEAD SIGNATURE -->
                        <div class="text-center">

                            <div class="h-28 flex items-center justify-center">

                                <img
                                    id="view_headImage"
                                    src=""
                                    alt="Head Signature"
                                    class="max-h-24 max-w-full object-contain">

                            </div>

                            <div class="border-t border-slate-300 mt-5 pt-5">

                                <h4 class="text-lg font-semibold text-slate-900">

                                    <span id="view_headName">
                                        -
                                    </span>

                                </h4>

                                <p class="text-sm text-slate-500 mt-1">

                                    <span id="view_headDesignation">
                                        -
                                    </span>

                                </p>

                                <div class="flex justify-center items-center gap-2 mt-4 text-sm text-blue-600">
                                    <i class="ri-time-line"></i>
                                    <span id="view_headDate">
                                        -
                                    </span>

                                </div>

                            </div>

                        </div>

                        <!-- REPRESENTATIVE -->
                        <div
                            id="view_representativeSection"
                            class="hidden mt-8 bg-slate-50 border border-slate-200 rounded-2xl p-5">

                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold mb-4">
                                Representative Signatory
                            </p>

                            <div class="h-24 flex items-center justify-center">

                                <img
                                    id="view_representativeImage"
                                    src=""
                                    alt="Representative Signature"
                                    class="max-h-20 max-w-full object-contain">

                            </div>

                            <div class="border-t border-slate-300 mt-4 pt-4 text-center">

                                <h5 class="font-semibold text-slate-900">

                                    <span id="view_representativeName">
                                        -
                                    </span>

                                </h5>

                                <p class="text-sm text-slate-500 mt-1">

                                    <span id="view_representativeDesignation">
                                        -
                                    </span>

                                </p>

                                <div class="flex justify-center items-center gap-2 mt-3 text-sm text-emerald-600">

                                    <i class="ri-time-line"></i>

                                    <span id="view_representativeDate">
                                        -
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<br>
<!-- CLOSE BUTTON -->
<div class="w-full">

  <button
    id="cancelViewEvaluationModalBtn"
    type="button"
    class="w-full block bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-medium text-sm transition-all duration-200">

    Close

  </button>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('viewEvaluationModal');

    document.getElementById('closeViewEvaluationModalBtn')
        ?.addEventListener('click', function () {
            modal.classList.add('hidden');
        });

    document.getElementById('cancelViewEvaluationModalBtn')
        ?.addEventListener('click', function () {
            modal.classList.add('hidden');
        });

});
</script>

<script>

function getCriteriaName(id) {
    switch (id) {
        case 1: return 'price';
        case 2: return 'quality';
        case 3: return 'customercare';
        case 4: return 'delivery';
        default: return '';
    }
}

function formatApprovalDate(dateString) {
    if (!dateString) return '-';

    let date;
    if (typeof dateString === 'string') {
        const trimmed = dateString.trim();
        // If string is in format "YYYY-MM-DD HH:mm:ss" without timezone indicator, treat as UTC
        if (/^\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}$/.test(trimmed)) {
            date = new Date(trimmed.replace(' ', 'T') + 'Z');
        } else {
            date = new Date(trimmed);
        }
    } else {
        date = new Date(dateString);
    }

    if (isNaN(date.getTime())) return dateString;

    return date.toLocaleString('en-PH', {
        timeZone: 'Asia/Manila',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

function setDigitalSignatureImage(imgElement, imageUrl) {
    if (!imgElement) return;
    if (imageUrl && typeof imageUrl === 'string' && imageUrl.trim() !== '') {
        imgElement.src = imageUrl;
        imgElement.classList.remove('hidden');
        imgElement.onerror = () => {
            imgElement.removeAttribute('src');
            imgElement.classList.add('hidden');
            imgElement.onerror = null;
        };
    } else {
        imgElement.removeAttribute('src');
        imgElement.classList.add('hidden');
        imgElement.onerror = null;
    }
}

async function viewEvaluation(id) {

    try {

        const response = await safeFetch(`/evaluations/${id}`);
        const data = await response.json();

        const evaluation = data.evaluation || {};
        const approvals = data.digital_approvals || [];

        // ===============================
        // ROLE MATCHING
        // ===============================
        const preparedBy = approvals.find(a => a.role === 'Prepared by');

        const headApproval = approvals.find(a => a.role === 'Head');

        const representative = approvals.find(a =>
            a.role === 'presentative_staff' || a.role === 'representative_staff'
        ) || data.representative_staff;

        const headFallback = data.head_info || {};

        // ===============================
        // BASIC FIELDS
        // ===============================
        const fields = [
            'supplier_name',
            'po_no',
            'date_evaluation',
            'covered_period',
            'office_name'
        ];

        fields.forEach(f => {

            const el = document.getElementById(`view_${f}`);
            if (!el) return;

            let value = evaluation[f] ?? '';

            if (f === 'date_evaluation' && value) {
                const d = new Date(value);
                if (!isNaN(d.getTime())) {
                    value = d.toISOString().split('T')[0];
                }
            }

            el.value = value;
            el.disabled = true;
        });

        // ===============================
        // RESET INPUTS
        // ===============================
        document.querySelectorAll('#viewEvaluationModal input[type="radio"]')
            .forEach(r => {
                r.checked = false;
                r.disabled = true;
            });

        document.querySelectorAll('#viewEvaluationModal textarea')
            .forEach(t => {
                t.value = '';
                t.readOnly = true;
            });

        // ===============================
        // SCORES
        // ===============================
        let scores = { 1: 0, 2: 0, 3: 0, 4: 0 };

        if (evaluation.criteria_scores) {

            evaluation.criteria_scores.forEach(score => {

                scores[score.criteria_id] = score.number_rating ?? 0;

                const radio = document.querySelector(
                    `#viewEvaluationModal input[name="${getCriteriaName(score.criteria_id)}_1"][value="${score.number_rating}"]`
                );

                if (radio) radio.checked = true;

                const remarks = document.getElementById(
                    `view_form_remarks_${getCriteriaName(score.criteria_id)}_1`
                );

                if (remarks) {
                    remarks.value = score.remarks ?? '';
                }
            });
        }

        // ===============================
        // COMPUTE RATING
        // ===============================
        const total =
            (5 * scores[1]) +
            (7.5 * scores[2]) +
            (6.25 * scores[3]) +
            (6.25 * scores[4]);

        const currentRatingEl = document.getElementById('view_currentRating');
        if (currentRatingEl) currentRatingEl.innerText = total.toFixed(2);
        const statusTextEl = document.getElementById('view_statusText');
        if (statusTextEl) statusTextEl.innerText = total >= 60 ? 'Approved' : 'Fail!';

        // ===============================
        // PREPARED BY (END-USER)
        // ===============================
        const preparedByNameEl = document.getElementById('view_preparedByName');
        const preparedByDesigEl = document.getElementById('view_preparedByDesignation');
        const preparedByDateEl = document.getElementById('view_preparedByDate');
        const preparedByImgEl = document.getElementById('view_preparedByImage');
        const preparedBySecEl = document.getElementById('view_preparedBySection');

        const hasPreparedBySig = Boolean(preparedBy && preparedBy.image && String(preparedBy.image).trim() !== '');

        if (hasPreparedBySig) {
            if (preparedByNameEl) preparedByNameEl.innerText = preparedBy.full_name ?? '-';
            if (preparedByDesigEl) preparedByDesigEl.innerText = preparedBy.designation ?? '-';
            if (preparedByDateEl) preparedByDateEl.innerText = preparedBy.created_at ? `Signed: ${formatApprovalDate(preparedBy.created_at)}` : '-';
            setDigitalSignatureImage(preparedByImgEl, preparedBy.image);
            preparedBySecEl?.classList.remove('hidden');
        } else {
            if (preparedByNameEl) preparedByNameEl.innerText = '-';
            if (preparedByDesigEl) preparedByDesigEl.innerText = '-';
            if (preparedByDateEl) preparedByDateEl.innerText = '-';
            setDigitalSignatureImage(preparedByImgEl, null);
            preparedBySecEl?.classList.add('hidden');
        }

        // ===============================
        // HEAD AUTHORIZATION
        // ===============================
        const headSecEl = document.getElementById('view_headSection');
        const headNameEl = document.getElementById('view_headName');
        const headDesigEl = document.getElementById('view_headDesignation');
        const headDateEl = document.getElementById('view_headDate');
        const headImgEl = document.getElementById('view_headImage');

        const repSectionEl = document.getElementById('view_representativeSection');
        const repNameEl = document.getElementById('view_representativeName');
        const repDesigEl = document.getElementById('view_representativeDesignation');
        const repDateEl = document.getElementById('view_representativeDate');
        const repImgEl = document.getElementById('view_representativeImage');

        if (!hasPreparedBySig) {
            // Hide Head Authorization data panel if Prepared By has no linked signature
            headSecEl?.classList.add('hidden');
            if (headNameEl) headNameEl.innerText = '-';
            if (headDesigEl) headDesigEl.innerText = '-';
            if (headDateEl) headDateEl.innerText = '-';
            setDigitalSignatureImage(headImgEl, null);

            repSectionEl?.classList.add('hidden');
            if (repNameEl) repNameEl.innerText = '-';
            if (repDesigEl) repDesigEl.innerText = '-';
            if (repDateEl) repDateEl.innerText = '-';
            setDigitalSignatureImage(repImgEl, null);
        } else {
            headSecEl?.classList.remove('hidden');

            if (headApproval) {
                if (headNameEl) headNameEl.innerText = headApproval.full_name ?? '-';
                if (headDesigEl) headDesigEl.innerText = headApproval.designation ?? '-';
                if (headDateEl) headDateEl.innerText = headApproval.created_at ? `Approved: ${formatApprovalDate(headApproval.created_at)}` : '-';
                setDigitalSignatureImage(headImgEl, headApproval.image);

                repSectionEl?.classList.add('hidden');
                if (repNameEl) repNameEl.innerText = '-';
                if (repDesigEl) repDesigEl.innerText = '-';
                if (repDateEl) repDateEl.innerText = '-';
                setDigitalSignatureImage(repImgEl, null);
            } else if (representative) {
                if (headNameEl) headNameEl.innerText = headFallback.name || headFallback.head_name || '-';
                if (headDesigEl) headDesigEl.innerText = headFallback.designation || headFallback.head_designation || '-';
                if (headDateEl) headDateEl.innerText = '-';
                setDigitalSignatureImage(headImgEl, null);

                repSectionEl?.classList.remove('hidden');
                if (repNameEl) repNameEl.innerText = representative.full_name || representative.name || '-';
                if (repDesigEl) repDesigEl.innerText = representative.designation || '-';
                const repSignedDate = representative.created_at || representative.signed_at;
                if (repDateEl) repDateEl.innerText = repSignedDate ? `Signed: ${formatApprovalDate(repSignedDate)}` : '-';
                setDigitalSignatureImage(repImgEl, representative.image);
            } else if (headFallback && (headFallback.name || headFallback.head_name)) {
                if (headNameEl) headNameEl.innerText = headFallback.name || headFallback.head_name || '-';
                if (headDesigEl) headDesigEl.innerText = headFallback.designation || headFallback.head_designation || '-';
                if (headDateEl) headDateEl.innerText = '-';
                setDigitalSignatureImage(headImgEl, null);

                repSectionEl?.classList.add('hidden');
                if (repNameEl) repNameEl.innerText = '-';
                if (repDesigEl) repDesigEl.innerText = '-';
                if (repDateEl) repDateEl.innerText = '-';
                setDigitalSignatureImage(repImgEl, null);
            } else {
                if (headNameEl) headNameEl.innerText = '-';
                if (headDesigEl) headDesigEl.innerText = '-';
                if (headDateEl) headDateEl.innerText = '-';
                setDigitalSignatureImage(headImgEl, null);

                repSectionEl?.classList.add('hidden');
                if (repNameEl) repNameEl.innerText = '-';
                if (repDesigEl) repDesigEl.innerText = '-';
                if (repDateEl) repDateEl.innerText = '-';
                setDigitalSignatureImage(repImgEl, null);
            }
        }

        document.querySelectorAll('#viewEvaluationModal select')
            .forEach(s => s.disabled = true);

        document.getElementById('viewEvaluationModal')
            ?.classList.remove('hidden');

        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.close();
        }

    } catch (err) {
        console.error(err);
        alert('Unable to load evaluation.');
    }
}

</script>

