@include('layouts.pobulk')

<div id="poListModal_v2"
    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">


    <div class="bg-white rounded-3xl shadow-2xl
                w-full max-w-7xl max-h-[92vh]
                flex flex-col overflow-hidden
                border border-gray-100">

        <!-- HEADER -->
<!-- STICKY HEADER -->
<div class="sticky top-0 z-30
            bg-gradient-to-r from-orange-500 to-orange-600
            px-6 py-5 text-white">


    <div class="flex items-center justify-between">


        <div>

            <h2 class="text-2xl font-bold flex items-center gap-2">
                📦 Purchase Orders
            </h2>

            <p class="text-orange-100 text-sm mt-1">
                Manage, review, and evaluate purchase orders
            </p>

        </div>



        <div class="flex items-center gap-2">


            @if(auth()->user()->role === 'administrator')

            <button onclick="openPOInsertModal_v2()"
                class="px-4 py-2
                bg-white text-orange-600
                rounded-xl
                font-semibold text-sm
                hover:bg-orange-50
                transition shadow">

                + Add PO

            </button>

            @endif



            <button onclick="openBulkAddEvaluateModal()"
                class="px-4 py-2
                bg-green-500
                text-white
                rounded-xl
                font-semibold text-sm
                hover:bg-green-600
                transition shadow">

                Bulk Evaluate

            </button>



            <button onclick="closePOModal_v2()"
                class="w-10 h-10
                rounded-full
                bg-white/20
                hover:bg-white/30
                text-white
                text-2xl">

                &times;

            </button>


        </div>

    </div>


</div>

<div class="px-6 py-4 bg-gray-50 border-b">

<input type="text"
    id="poSearchInput_v2"
    onkeyup="searchPO_v2()"
    placeholder="Search PO number, PR number, office, supplier..."
    class="
    w-full
    rounded-2xl
    border-gray-200
    bg-white
    px-5 py-3
    shadow-sm
    focus:ring-2
    focus:ring-orange-400
    focus:border-orange-400
    outline-none
    transition">

</div>


        <!-- Upload PDF Modal -->
        <div id="uploadPOModal"
             class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

                <h2 class="text-lg font-semibold mb-4">
                    Upload Purchase Order PDF
                </h2>

                <form id="uploadPOForm"
                      method="POST"
                      enctype="multipart/form-data"
                      onsubmit="if(typeof showGlobalLoading === 'function') showGlobalLoading('Uploading PDF...', 'Processing document, please wait');">

                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">
                            Select PDF
                        </label>

                        <input type="file"
                               name="pdf_po"
                               accept=".pdf"
                               required
                               class="w-full border rounded p-2">
                    </div>

                    <div class="flex justify-end gap-2">

                        <button type="button"
                                onclick="closeUploadPOModal()"
                                class="px-4 py-2 bg-gray-300 rounded">
                            Cancel
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                            Upload
                        </button>

                    </div>

                </form>

            </div>

        </div>
<script>

function openUploadPOModal(poId, hasPdf = false)
{
    const modal = document.getElementById('uploadPOModal');
    const title = modal.querySelector('h2');
    if (title) {
        title.innerText = hasPdf ? 'Change Purchase Order PDF' : 'Upload Purchase Order PDF';
    }

    document.getElementById('uploadPOForm').action =
        `/purchase-orders/${poId}/upload-pdf`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUploadPOModal()
{
    const modal = document.getElementById('uploadPOModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>



        <!-- TABLE -->
        <div class="flex-1 overflow-y-auto overflow-x-auto">
            <table class="w-full text-sm">

<thead class="
bg-gray-50
text-gray-600
uppercase
text-xs
tracking-wider
sticky top-0 z-20">
                    <tr>
                        <th class="p-3 border">PO No</th>
                        <th class="p-3 border">PR No</th>
                        <th class="p-3 border">End User</th>
                        <th class="p-3 border">Supplier</th>
                        <th class="p-3 border">PO PDF</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Action</th>
                    </tr>
                </thead>

<tbody id="poTableBody_v2">
@foreach($pos as $po)
<tr class="
border-b
hover:bg-orange-50/50
transition
duration-200
po-row-v2
{{ $po->pdf_po ? 'has-pdf bg-green-50/30' : 'no-pdf' }}">

                    <td class="px-5 py-4 text-gray-700">{{ $po->po_no }}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $po->pr_no ?? 'N/A' }}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $po->end_user }}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $po->supplier }}</td>

                    <td class="px-5 py-4 text-gray-700">
                        @if($po->pdf_po)
                            <a href="{{ route('po.view.pdf', $po->encrypted_id) }}"
                               target="_blank"
                               class="text-blue-600 hover:underline">
                                View PDF
                            </a>
                        @else
                            <span class="text-gray-400">No PDF</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-gray-700">
                        @php
                            $status = $po->status ?? 'Pending';
                        @endphp

<span class="
inline-flex items-center
px-3 py-1
rounded-full
text-xs
font-semibold

@if($status == 'Added')
bg-blue-100 text-blue-700

@elseif($status == 'Approved')
bg-green-100 text-green-700

@elseif($status == 'Cancelled')
bg-red-100 text-red-700

@else
bg-yellow-100 text-yellow-700

@endif
">

{{ $status }}

</span>
                    </td>

                <td class="p-3 border relative">

                    @php
                        $status = $po->status ?? 'Pending';
                        $isAdmin = auth()->user()->role === 'administrator';
                    @endphp

                    {{-- 🚫 NON-ADMIN: HIDE ACTION IF ADDED --}}
                    @if(in_array($status, ['Added', 'Cancelled']) && !$isAdmin)

                        <span class="text-xs text-gray-400 italic">Locked</span>

                    @else

                        <div class="po-action-wrapper-v2 relative inline-block text-left">

<button onclick="togglePOAction_v2(this)"
class="
px-4 py-2
rounded-xl
bg-gray-100
hover:bg-orange-100
text-gray-700
text-sm
font-medium
transition">

⋮ Actions

</button>

                            <div class="
po-action-menu-v2
hidden
absolute
right-0
mt-2
w-48
bg-white
rounded-2xl
shadow-xl
border
border-gray-100
overflow-hidden
z-50">

                                {{-- 🚫 EVALUATE RULE --}}
                                @if($status !== 'Added' && $status !== 'Cancelled')
                                    <a href="#"
                                       onclick='openPOEvaluateModal_v2(
                                           @json($po->id),
                                           @json($po->po_no),
                                           @json($po->supplier),
                                           @json($po->end_user)
                                       )'
                                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                                        Add Evaluation
                                    </a>
                                @endif

<a href="#"
   onclick='openPOEditModal_v2(
       @json($po->id),
       @json($po->po_no),
       @json($po->pr_no),
       @json($po->supplier),
       @json($po->end_user),
       @json($po->status),
       @json($po->pdf_po ? route("po.view.pdf", $po->encrypted_id) : null),
       @json(auth()->user()->role)
   )'
   class="block px-4 py-2 text-sm hover:bg-gray-100">
    View
</a>

                                {{-- ADMIN ONLY ACTIONS --}}
                                @if($isAdmin)



                                    <a href="#"
                                       onclick="openUploadPOModal({{ $po->id }}, {{ $po->pdf_po ? 'true' : 'false' }})"
                                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                                        {{ $po->pdf_po ? 'Change PDF' : 'Upload PDF' }}
                                    </a>

                                    <form action="{{ route('po.delete', $po->id) }}" method="POST"
                                          onsubmit="confirmDeletePO(event, this)">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Delete
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </div>

                    @endif

                </td>

                </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        <!-- FOOTER & PAGINATION -->
        <div class="px-6 py-4 bg-gray-50 border-t flex flex-wrap items-center justify-between gap-3 shrink-0">
            <div id="poPagination_v2" class="flex items-center gap-3">
                <button id="poPrevPage_v2" onclick="changePOPage_v2(-1)"
                    class="px-4 py-2 rounded-xl border bg-white hover:bg-gray-100 text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>

                <span id="poPageInfo_v2" class="text-sm font-medium text-gray-600">Page 1 of 1</span>

                <button id="poNextPage_v2" onclick="changePOPage_v2(1)"
                    class="px-4 py-2 rounded-xl border bg-white hover:bg-gray-100 text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>

            <div class="text-xs text-gray-500 font-medium" id="poTotalInfo_v2">
                Showing 0 of 0 entries
            </div>
        </div>

    </div>
</div>

<div id="poInsertModal_v2" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Add Purchase Order</h2>

            <button onclick="closePOInsertModal_v2()" class="text-gray-500 hover:text-red-500 text-xl">
                &times;
            </button>
        </div>

        <form action="{{ route('po.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label class="text-sm">PO Number</label>
                <input type="text"
                       name="po_no"
                       class="w-full border rounded-lg px-3 py-2"
                       required>
            </div>

            <div class="mb-3">
                <label class="text-sm">PR Number</label>
                <input type="text"
                       name="pr_no"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">End User</label>
                <input type="text"
                       name="end_user"
                       class="w-full border rounded-lg px-3 py-2"
                       required>
            </div>

            <div class="mb-3">
                <label class="text-sm">Supplier</label>
                <input type="text"
                       name="supplier"
                       class="w-full border rounded-lg px-3 py-2"
                       required>
            </div>

            <!-- PDF Upload -->
            <div class="mb-3">
                <label class="text-sm">Purchase Order PDF</label>
                <input type="file"
                       name="pdf_po"
                       accept=".pdf"
                       class="w-full border rounded-lg px-3 py-2">

                <small class="text-gray-500">
                    PDF only (Maximum 10MB)
                </small>
            </div>

            <input type="hidden" name="status" value="Active">

            <div class="flex justify-end space-x-2 mt-4">

                <button type="button"
                        onclick="closePOInsertModal_v2()"
                        class="px-3 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button type="submit"
                        class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Save
                </button>

            </div>

        </form>

    </div>

</div>


<div id="poEvaluateModal_v2" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Evaluate Purchase Order</h2>

            <button onclick="closePOEvaluateModal_v2()" class="text-gray-500 hover:text-red-500 text-xl">
                &times;
            </button>
        </div>

        <form id="poEvaluateForm_v2" method="POST">
            @csrf

            <div class="mb-3">
                <label class="text-sm font-semibold">Supplier Name</label>
                <input readonly type="text" name="supplier_name" id="eval_supplier_v2"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="text-sm font-semibold">PO No</label>
                <input readonly type="text" name="po_no" id="eval_po_no_v2"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="text-sm font-semibold">Department</label>
                <input readonly type="text" name="end_user" id="eval_end_user_v2"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- OFFICE AUTO -->
            <input type="hidden" name="office_id" value="{{ auth()->user()->office_id }}">

            <div class="mb-3">
                <label for="department" class="text-sm font-semibold">
                    Your Department
                </label>

                <input
                    type="text"
                    id="department"
                    name="department"
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                    value="{{ auth()->user()->office->name ?? 'No Office Assigned' }}"
                    disabled>
            </div>

            <div class="flex justify-end space-x-2 mt-4">

                <button type="button"
                    onclick="closePOEvaluateModal_v2()"
                    class="px-3 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button type="submit"
                    class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Save Evaluation
                </button>

            </div>

        </form>

    </div>
</div>
<script>

// ===============================
// PAGINATION & SEARCH (15 ITEMS PER PAGE)
// ===============================
var poCurrentPage_v2 = 1;
var poItemsPerPage_v2 = 15;

function updatePOPagination_v2() {
    const tbody = document.getElementById('poTableBody_v2');
    if (!tbody) return;

    let input = document.getElementById("poSearchInput_v2");
    let filter = input ? input.value.toLowerCase().trim() : "";

    let allRows = Array.from(tbody.querySelectorAll('.po-row-v2'));
    let filteredRows = allRows.filter(row => {
        return row.textContent.toLowerCase().includes(filter);
    });

    // Hide all non-matching rows
    allRows.forEach(row => {
        if (!filteredRows.includes(row)) {
            row.style.display = "none";
        }
    });

    const totalItems = filteredRows.length;
    const totalPages = Math.ceil(totalItems / poItemsPerPage_v2) || 1;

    if (poCurrentPage_v2 > totalPages) {
        poCurrentPage_v2 = totalPages;
    }
    if (poCurrentPage_v2 < 1) {
        poCurrentPage_v2 = 1;
    }

    const startIndex = (poCurrentPage_v2 - 1) * poItemsPerPage_v2;
    const endIndex = startIndex + poItemsPerPage_v2;

    filteredRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });

    const pageInfo = document.getElementById('poPageInfo_v2');
    if (pageInfo) {
        pageInfo.textContent = `Page ${poCurrentPage_v2} of ${totalPages}`;
    }

    const totalInfo = document.getElementById('poTotalInfo_v2');
    if (totalInfo) {
        const startNum = totalItems === 0 ? 0 : startIndex + 1;
        const endNum = Math.min(endIndex, totalItems);
        totalInfo.textContent = `Showing ${startNum}-${endNum} of ${totalItems} entries`;
    }

    const prevBtn = document.getElementById('poPrevPage_v2');
    const nextBtn = document.getElementById('poNextPage_v2');

    if (prevBtn) prevBtn.disabled = (poCurrentPage_v2 <= 1);
    if (nextBtn) nextBtn.disabled = (poCurrentPage_v2 >= totalPages);
}

function changePOPage_v2(direction) {
    poCurrentPage_v2 += direction;
    updatePOPagination_v2();
}

function searchPO_v2() {
    poCurrentPage_v2 = 1;
    updatePOPagination_v2();
}

// ===============================
// SORT PO WITH PDF FIRST
// ===============================
function sortPOByPDF_v2() {

    const tbody = document.getElementById('poTableBody_v2');

    if (!tbody) return;

    let rows = Array.from(
        tbody.querySelectorAll('.po-row-v2')
    );

    rows.sort((a, b) => {
        let aHasPDF = a.classList.contains('has-pdf');
        let bHasPDF = b.classList.contains('has-pdf');

        // Not yet uploaded PDF rows first
        if (!aHasPDF && bHasPDF) return -1;
        if (aHasPDF && !bHasPDF) return 1;

        return 0;
    });

    rows.forEach(row => {
        tbody.appendChild(row);
    });

    updatePOPagination_v2();
}

// ===============================
// MODAL CONTROL
// ===============================
function openPOModal_v2() {
    document.getElementById('poListModal_v2').classList.remove('hidden');
    document.getElementById('poListModal_v2').classList.add('flex');
    sortPOByPDF_v2();
}

function closePOModal_v2() {
    document.getElementById('poListModal_v2').classList.add('hidden');
    document.getElementById('poListModal_v2').classList.remove('flex');
}

function openPOInsertModal_v2() {
    document.getElementById('poInsertModal_v2').classList.remove('hidden');
    document.getElementById('poInsertModal_v2').classList.add('flex');
}

function closePOInsertModal_v2() {
    document.getElementById('poInsertModal_v2').classList.add('hidden');
    document.getElementById('poInsertModal_v2').classList.remove('flex');
}

// ===============================
// EVALUATE MODAL
// ===============================
function openPOEvaluateModal_v2(poId, poNo, supplier, endUser) {
    document.getElementById('eval_po_no_v2').value = poNo;
    document.getElementById('eval_supplier_v2').value = supplier;
    document.getElementById('eval_end_user_v2').value = endUser;

    document.getElementById('poEvaluateForm_v2').action =
        `/purchase-orders/${poId}/evaluate`;

    document.getElementById('poEvaluateModal_v2').classList.remove('hidden');
    document.getElementById('poEvaluateModal_v2').classList.add('flex');
}

function closePOEvaluateModal_v2() {
    document.getElementById('poEvaluateModal_v2').classList.add('hidden');
    document.getElementById('poEvaluateModal_v2').classList.remove('flex');
}

// ===============================
// ACTION DROPDOWN (SMART POSITION)
// ===============================
function togglePOAction_v2(button) {
    let menu = button.nextElementSibling;
    let rect = button.getBoundingClientRect();

    document.querySelectorAll('.po-action-menu-v2').forEach(el => {
        if (el !== menu) el.classList.add('hidden');
    });

    if (menu.classList.contains('hidden')) {
        const menuWidth = 160;
        const offset = 8;
        let leftPosition = rect.right + offset;

        if (leftPosition + menuWidth > window.innerWidth) {
            leftPosition = rect.left - menuWidth - offset;
        }

        menu.style.position = 'fixed';
        menu.style.top = rect.top + 'px';
        menu.style.left = leftPosition + 'px';

        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}

// CLOSE OUTSIDE CLICK
document.addEventListener('click', function (e) {
    if (!e.target.closest('.po-action-wrapper-v2')) {
        document.querySelectorAll('.po-action-menu-v2').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

function confirmDeletePO(event, form) {
    event.preventDefault();

    Swal.fire({
        title: 'Delete Purchase Order?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        background: '#fff',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-4 py-2',
            cancelButton: 'rounded-lg px-4 py-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

// ===============================
// SWEETALERT TOAST & AUTO-OPEN HANDLERS
// ===============================
const POToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    customClass: {
        container: 'z-[100000]',
        popup: 'rounded-2xl shadow-xl border border-gray-100 font-sans'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    sortPOByPDF_v2();

    @if(session('po_deleted'))
    POToast.fire({
        icon: 'success',
        title: 'Purchase Order Deleted',
        text: '{{ session("po_deleted") }}'
    });
    openPOModal_v2();
    @endif

    @if(session('po_success'))
    POToast.fire({
        icon: 'success',
        title: 'Purchase Order Added',
        text: 'The Purchase Order has been successfully added.'
    });
    openPOModal_v2();
    @endif

    @if(session('po_success_added'))
    POToast.fire({
        icon: 'success',
        title: 'Saved to Evaluation',
        text: 'The P.O. has been saved to Evaluation Management.'
    });
    openPOModal_v2();
    @endif

    @if(session('error'))
    POToast.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}'
    });
    openPOModal_v2();
    @endif

    @if(session('po_updated'))
    POToast.fire({
        icon: 'success',
        title: 'Updated Successfully',
        text: 'Purchase Order has been saved.'
    });
    openPOModal_v2();
    @endif

    @if(session('success_pdf'))
    POToast.fire({
        icon: 'success',
        title: 'Uploaded Successfully',
        text: 'Purchase Order PDF uploaded successfully.'
    });
    openPOModal_v2();
    @endif

    @if(session('error_pdf'))
    POToast.fire({
        icon: 'error',
        title: 'Upload Failed!',
        text: "{{ session('error_pdf') }}"
    });
    openPOModal_v2();
    @endif

    @if(session('po_error'))
    POToast.fire({
        icon: 'error',
        title: 'Update Failed!',
        text: "{{ session('po_error') }}"
    });
    openPOModal_v2();
    @endif

    @if(session('po_error_update'))
    POToast.fire({
        icon: 'error',
        title: 'Update Failed!',
        text: "{{ session('po_error_update') }}"
    });
    openPOModal_v2();
    @endif
});

window.addEventListener('pageshow', function () {
    if (typeof sortPOByPDF_v2 === 'function') {
        sortPOByPDF_v2();
    }
});

</script>

