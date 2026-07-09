<!-- BULK ADD EVALUATE MODAL -->
<div id="bulkAddEvaluateModal"
    class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl">

        <!-- HEADER -->
        <div class="flex items-center justify-between border-b px-6 py-4">

            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Bulk Add Evaluation
                </h2>

                <p class="text-sm text-gray-500">
                    Select Purchase Orders to add into evaluation.
                </p>
            </div>

            <button type="button"
                onclick="closeBulkAddEvaluateModal()"
                class="text-gray-400 hover:text-red-500 text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- BODY -->
        <div class="p-6">

            <!-- FILTERS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

                <!-- END USER -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        End User
                    </label>

                    <select id="bulk_end_user"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-400 focus:outline-none"
                        onchange="handleEndUserChange()">

                        <option value="">All End Users</option>

                        @forelse($endUsers as $endUser)
                            <option value="{{ $endUser }}">
                                {{ $endUser }}
                            </option>
                        @empty
                            <option disabled>
                                No End Users Found
                            </option>
                        @endforelse

                    </select>
                </div>

                <!-- SUPPLIER -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Supplier
                    </label>

                    <select id="bulk_supplier"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-400 focus:outline-none"
                        onchange="loadBulkPOs()">

                        <option value="">All Suppliers</option>

                        @forelse($suppliers as $supplier)
                            <option value="{{ $supplier }}">
                                {{ $supplier }}
                            </option>
                        @empty
                            <option disabled>
                                No Suppliers Found
                            </option>
                        @endforelse

                    </select>
                </div>
            </div>

            <!-- TABLE -->
            <div class="border rounded-lg overflow-hidden">

                <div class="overflow-auto max-h-[450px]">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-100 sticky top-0 z-10">
                            <tr>

                                <th class="border px-3 py-2 text-center w-12">
                                    <input type="checkbox"
                                        id="checkAllPO">
                                </th>

                                <th class="border px-3 py-2 text-left">
                                    PO No
                                </th>

                                <th class="border px-3 py-2 text-left">
                                    PR No
                                </th>

                                <th class="border px-3 py-2 text-left">
                                    End User
                                </th>

                                <th class="border px-3 py-2 text-left">
                                    Supplier
                                </th>

                                <th class="border px-3 py-2 text-center">
                                    Status
                                </th>

                            </tr>
                        </thead>

                        <tbody id="bulkPOBody">

                            <tr>
                                <td colspan="6"
                                    class="text-center text-gray-400 py-6">
                                    No data loaded.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="flex items-center justify-between mt-5">

                <div class="text-sm text-gray-500">
                    <span id="selectedPOCount">0</span> selected
                </div>

                <div class="flex gap-2">

                    <button type="button"
                        onclick="closeBulkAddEvaluateModal()"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

                        Cancel
                    </button>

                    <button type="button"
                        id="submitBulkBtn"
                        onclick="submitBulkEvaluation()"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">

                        Add Selected
                    </button>

                </div>

            </div>

        </div>
    </div>
</div>

<script>

function openBulkAddEvaluateModal() {

    // open bulk modal
    document.getElementById('bulkAddEvaluateModal').classList.remove('hidden');

    // load data
    loadBulkPOs();

    // close PO modal
    closePOModal_v2();
}

function closeBulkAddEvaluateModal() {
    document.getElementById('bulkAddEvaluateModal').classList.add('hidden');
}

// ==========================
// END USER CHANGE → LOAD SUPPLIERS + POs
// ==========================
async function handleEndUserChange() {

    await loadSuppliersByEndUser();
    loadBulkPOs();
}

// ==========================
// LOAD SUPPLIERS BASED ON END USER
// ==========================
async function loadSuppliersByEndUser() {

    try {

        const endUser = document.getElementById('bulk_end_user').value;
        const supplierSelect = document.getElementById('bulk_supplier');

        supplierSelect.innerHTML = `<option>Loading...</option>`;

        const res = await fetch(
            `/bulk-evaluation/suppliers-by-end-user?end_user=${encodeURIComponent(endUser)}`
        );

        const data = await res.json();

        if (!res.ok) throw new Error(data.message);

        let options = `<option value="">All Suppliers</option>`;

        data.forEach(s => {
            options += `<option value="${s}">${s}</option>`;
        });

        supplierSelect.innerHTML = options;

    } catch (err) {
        console.error(err);
        document.getElementById('bulk_supplier').innerHTML =
            `<option value="">Error loading suppliers</option>`;
    }
}

// ==========================
// LOAD PO LIST
// ==========================
async function loadBulkPOs() {

    try {

        const endUser = document.getElementById('bulk_end_user').value;
        const supplier = document.getElementById('bulk_supplier').value;

        const tbody = document.getElementById('bulkPOBody');

        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">Loading...</td></tr>`;

        const res = await fetch(
            `/bulk-evaluation/po-list?end_user=${encodeURIComponent(endUser)}&supplier=${encodeURIComponent(supplier)}`
        );

        const data = await res.json();

        if (!res.ok) throw new Error(data.message);

        if (!data.length) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">No data found</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(po => `
            <tr>
                <td class="text-center border px-2">
                    <input type="checkbox" class="poCheckbox" value="${po.id}">
                </td>
                <td class="border px-2">${po.po_no}</td>
                <td class="border px-2">${po.pr_no ?? ''}</td>
                <td class="border px-2">${po.end_user ?? ''}</td>
                <td class="border px-2">${po.supplier ?? ''}</td>
                <td class="border px-2 text-center">Available</td>
            </tr>
        `).join('');

        updateSelectedCount();

    } catch (err) {
        console.error(err);
    }
}

// ==========================
// CHECK ALL + COUNT
// ==========================
document.addEventListener('change', function (e) {

    if (e.target.id === 'checkAllPO') {
        document.querySelectorAll('.poCheckbox')
            .forEach(cb => cb.checked = e.target.checked);
    }

    if (e.target.classList.contains('poCheckbox') || e.target.id === 'checkAllPO') {
        updateSelectedCount();
    }
});

function updateSelectedCount() {
    const count = document.querySelectorAll('.poCheckbox:checked').length;
    document.getElementById('selectedPOCount').innerText = count;
}

// ==========================
// SUBMIT BULK
// ==========================
async function submitBulkEvaluation() {

    const button = document.getElementById('submitBulkBtn');

    try {

        const selected = [...document.querySelectorAll('.poCheckbox:checked')]
            .map(cb => cb.value);

        if (!selected.length) {
            alert('Select at least one PO');
            return;
        }

        button.disabled = true;
        button.innerText = 'Processing...';

        const res = await fetch('/bulk-evaluation/store-pos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ po_ids: selected })
        });

        const data = await res.json();

        if (!res.ok) throw new Error(data.message);

        alert(data.message);

        closeBulkAddEvaluateModal();
        location.reload();

    } catch (err) {
        alert(err.message);
    } finally {
        button.disabled = false;
        button.innerText = 'Add Selected';
    }
}

</script>
