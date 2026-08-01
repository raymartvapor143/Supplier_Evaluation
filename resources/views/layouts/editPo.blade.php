<!-- Modern View/Edit PO Modal -->
<div id="poEditModal_v2"
     class="hidden fixed inset-0 z-50 items-center justify-center bg-orange-100/80 backdrop-blur-sm px-4">

    <div class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl animate-[fadeIn_.2s_ease-out]">

        <!-- Header -->
        <div class="flex items-center justify-between bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-5">

            <div>
                <h2 id="poModalTitle_v2" class="text-xl font-bold text-white">
                    Purchase Order Details
                </h2>

                <p id="poModalSubtitle_v2" class="text-sm text-orange-100">
                    View purchase order information
                </p>
            </div>

            <button type="button"
                onclick="closePOEditModal_v2()"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 hover:rotate-90 duration-200">
                ✕
            </button>

        </div>

        <!-- MAIN FORM (ONLY ONE FORM) -->
        <form id="poEditForm_v2"
              method="POST"
              enctype="multipart/form-data"
              class="bg-orange-50">

            @csrf
            @method('PUT')
<input type="hidden"
       name="remove_pdf"
       id="remove_pdf_v2"
       value="0">

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-700">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <div class="space-y-5 px-6 py-6">

                <!-- PO Number -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">P.O Number</label>
                    <input type="text"
                        name="po_no"
                        id="edit_po_no_v2"
                        readonly
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">
                </div>

                <!-- PR Number -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">PR Number</label>
                    <input type="text"
                        name="pr_no"
                        id="edit_pr_no_v2"
                        readonly
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">
                </div>

                <!-- Supplier -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Supplier</label>
                    <input type="text"
                        name="supplier"
                        id="edit_supplier_v2"
                        readonly
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">
                </div>

                <!-- End User -->
                <div class="relative">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">End User</label>
                    <input type="text"
                        name="end_user"
                        id="edit_end_user_v2"
                        placeholder="Search End User / Office Abbreviation..."
                        readonly
                        autocomplete="off"
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">

                    <div id="endUserDropdown_v2"
                        class="absolute z-50 w-full bg-white border border-orange-200 rounded-xl shadow-xl mt-1 hidden max-h-56 overflow-y-auto p-2">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
                    <select name="status"
                            id="edit_status_v2"
                            class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none">
                        <option value="Pending">Pending</option>
                        <option value="Added">Added</option>
                        <option value="Approved">Approved</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- PDF SECTION -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Purchase Order PDF
                    </label>

                    <!-- VIEW PDF AREA -->
                    <div id="pdf_container_v2"></div>

                    <!-- UPLOAD AREA (hidden until edit/remove) -->
                    <div id="pdf_upload_section_v2" class="hidden mt-3">

                        <input type="file"
                            name="pdf_po"
                            id="pdf_po_v2"
                            accept="application/pdf"
                            class="block w-full rounded-lg border border-orange-200 p-2 text-sm">

                        <button type="button"
                            onclick="cancelReplacePdf()"
                            class="mt-2 rounded-lg border px-4 py-2 text-sm hover:bg-gray-100">
                            Cancel
                        </button>

                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex justify-end gap-3 border-t border-orange-100 bg-white px-6 py-4">

                <button type="button"
                    onclick="closePOEditModal_v2()"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Close
                </button>

                <button type="button"
                    id="enableEditBtn_v2"
                    onclick="enablePOEdit_v2()"
                    class="rounded-xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-600">
                    Edit
                </button>

                <button type="submit"
                    id="updateBtn_v2"
                    class="hidden rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:scale-[1.02]">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>



<script>

let currentPoId = null;
let cachedOffices_v2 = [];

async function loadOfficesForEditPO_v2() {
    if (cachedOffices_v2.length > 0) return cachedOffices_v2;
    try {
        const res = await fetch('/offices/list');
        cachedOffices_v2 = await res.json();
    } catch (e) {
        console.error('Failed to load offices for PO edit', e);
        cachedOffices_v2 = [];
    }
    return cachedOffices_v2;
}

function renderEndUserDropdown_v2(filter = '') {
    const dropdown = document.getElementById('endUserDropdown_v2');
    const search = filter.trim().toLowerCase();
    
    const filtered = cachedOffices_v2.filter(o => {
        const abbr = (o.abbreviation ?? '').toLowerCase();
        const name = (o.name ?? '').toLowerCase();
        return abbr.includes(search) || name.includes(search);
    });

    if (filtered.length === 0) {
        dropdown.innerHTML = `<div class="px-3 py-2 text-xs text-gray-400">No matching offices</div>`;
    } else {
        dropdown.innerHTML = filtered.map(o => {
            const displayVal = o.abbreviation ? o.abbreviation : o.name;
            const subText = o.abbreviation && o.name ? `<span class="text-xs text-gray-500 block">${o.name}</span>` : '';
            return `
                <div class="px-3 py-2 text-sm rounded-lg hover:bg-orange-100 cursor-pointer font-medium text-gray-800 transition"
                     onclick="selectEndUser_v2('${displayVal.replace(/'/g, "\\'")}')">
                    ${displayVal}
                    ${subText}
                </div>
            `;
        }).join('');
    }
}

function selectEndUser_v2(val) {
    document.getElementById('edit_end_user_v2').value = val;
    document.getElementById('endUserDropdown_v2').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const endUserInput = document.getElementById('edit_end_user_v2');
    const dropdown = document.getElementById('endUserDropdown_v2');

    if (endUserInput && dropdown) {
        endUserInput.addEventListener('input', () => {
            if (endUserInput.readOnly) return;
            renderEndUserDropdown_v2(endUserInput.value);
            dropdown.classList.remove('hidden');
        });

        endUserInput.addEventListener('focus', async () => {
            if (endUserInput.readOnly) return;
            await loadOfficesForEditPO_v2();
            renderEndUserDropdown_v2(endUserInput.value);
            dropdown.classList.remove('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#edit_end_user_v2') && !e.target.closest('#endUserDropdown_v2')) {
                dropdown.classList.add('hidden');
            }
        });
    }
});

function setPOViewMode_v2()
{
    document.getElementById('edit_po_no_v2').readOnly = true;
    document.getElementById('edit_pr_no_v2').readOnly = true;
    document.getElementById('edit_supplier_v2').readOnly = true;
    document.getElementById('edit_end_user_v2').readOnly = true;

    document.getElementById('endUserDropdown_v2').classList.add('hidden');

    document.getElementById('edit_status_v2').disabled = true;

    document.getElementById('enableEditBtn_v2').classList.remove('hidden');
    document.getElementById('updateBtn_v2').classList.add('hidden');

    document.getElementById('poModalTitle_v2').innerText =
        'Purchase Order Details';

    document.getElementById('poModalSubtitle_v2').innerText =
        'View purchase order information';

    // hide PDF edit controls
    document.getElementById('pdf_upload_section_v2').classList.add('hidden');
}

function enablePOEdit_v2()
{
    document.getElementById('edit_po_no_v2').readOnly = false;
    document.getElementById('edit_pr_no_v2').readOnly = false;
    document.getElementById('edit_supplier_v2').readOnly = false;
    document.getElementById('edit_end_user_v2').readOnly = false;

    document.getElementById('edit_status_v2').disabled = false;

    document.getElementById('enableEditBtn_v2').classList.add('hidden');
    document.getElementById('updateBtn_v2').classList.remove('hidden');

    document.getElementById('poModalTitle_v2').innerText =
        'Edit Purchase Order';

    document.getElementById('poModalSubtitle_v2').innerText =
        'Update purchase order information';

    // Fetch offices in background so dropdown is ready
    loadOfficesForEditPO_v2();

    // Show Remove button if PDF exists
    const removeBtn = document.getElementById('removePdfBtn');

    if (removeBtn) {
        removeBtn.classList.remove('hidden');
    }

    // Always allow selecting a new PDF
    document.getElementById('pdf_upload_section_v2')
        .classList.remove('hidden');
}


/**
 * CLICK ✕ BUTTON ON PDF
 * -> hide view PDF
 * -> show file input
 */
function removePdf()
{
    document.getElementById('pdf_container_v2').innerHTML = `
        <span class="text-red-500 text-sm">
            PDF will be removed after clicking Update.
        </span>
    `;

    document.getElementById('pdf_upload_section_v2')
        .classList.remove('hidden');

    document.getElementById('remove_pdf_v2').value = "1";
}

/**
 * CANCEL PDF REPLACE
 */
function cancelReplacePdf()
{
    document.getElementById('pdf_po_v2').value = '';

    document.getElementById('pdf_upload_section_v2')
        .classList.add('hidden');

    document.getElementById('remove_pdf_v2').value = "0";
}

function openPOEditModal_v2(
    poId,
    poNo,
    prNo,
    supplier,
    endUser,
    status,
    pdfUrl,
    role
) {
    currentPoId = poId;

    document.getElementById('edit_po_no_v2').value = poNo;
    document.getElementById('edit_pr_no_v2').value = prNo ?? '';
    document.getElementById('edit_supplier_v2').value = supplier;
    document.getElementById('edit_end_user_v2').value = endUser;
    document.getElementById('edit_status_v2').value = status;

    const form = document.getElementById('poEditForm_v2');
    form.action = `/purchase-orders/${poId}`;

    setPOViewMode_v2();

    const pdfContainer = document.getElementById('pdf_container_v2');
    const uploadSection = document.getElementById('pdf_upload_section_v2');

    uploadSection.classList.add('hidden');

if (pdfUrl) {

    pdfContainer.innerHTML = `
        <div class="flex items-center gap-2">

            <a href="${pdfUrl}"
               target="_blank"
               class="inline-flex items-center rounded-lg bg-red-500 px-3 py-2 text-sm font-medium text-white hover:bg-red-600">
                📄 View PDF
            </a>

            <button
                type="button"
                id="removePdfBtn"
                onclick="removePdf()"
                class="hidden flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200">
                ✕
            </button>

        </div>
    `;

} else {

    pdfContainer.innerHTML = `
        <span class="text-gray-500 text-sm">
            No PDF uploaded
        </span>
    `;
}

uploadSection.classList.add('hidden');
document.getElementById('remove_pdf_v2').value = "0";
document.getElementById('pdf_po_v2').value = "";

    const modal = document.getElementById('poEditModal_v2');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.classList.add('overflow-hidden');

    const editBtn = document.getElementById('enableEditBtn_v2');

    if (role === 'end_user' || role === 'presentative_staff') {
        editBtn.classList.add('hidden');
    } else {
        editBtn.classList.remove('hidden');
    }
}

function closePOEditModal_v2()
{
    const modal = document.getElementById('poEditModal_v2');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');

    document.getElementById('poEditForm_v2').reset();

    document.getElementById('pdf_upload_section_v2')
        .classList.add('hidden');

    document.getElementById('pdf_container_v2').innerHTML = '';

    setPOViewMode_v2();
}

/**
 * CLOSE ON OUTSIDE CLICK
 */
document.getElementById('poEditModal_v2')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            closePOEditModal_v2();
        }

    });

/**
 * CLOSE ON ESC
 */
document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {
        closePOEditModal_v2();
    }

});

</script>
