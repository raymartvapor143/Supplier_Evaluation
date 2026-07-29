<!-- BULK ADD EVALUATE MODAL -->
<div id="bulkAddEvaluateModal"
    class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm hidden z-50 items-center justify-center p-4">


    <div class="bg-white rounded-3xl shadow-2xl
                w-full max-w-6xl
                max-h-[92vh]
                overflow-hidden
                flex flex-col
                border border-gray-100">


        <!-- HEADER -->
        <div class="bg-gradient-to-r from-green-600 to-green-700
                    px-7 py-6 text-white">


            <div class="flex items-center justify-between">


                <div>

                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        📋 Bulk Add Evaluation
                    </h2>

                    <p class="text-green-100 text-sm mt-1">
                        Select multiple Purchase Orders and add them to evaluation.
                    </p>

                </div>


                <button type="button"
                    onclick="closeBulkAddEvaluateModal()"
                    class="
                    w-10 h-10
                    rounded-full
                    bg-white/20
                    hover:bg-white/30
                    transition
                    text-2xl">

                    &times;

                </button>


            </div>


        </div>



        <!-- BODY -->
        <div class="flex-1 overflow-y-auto p-7">


            <!-- FILTER CARD -->
              <div class="
                  sticky
                  top-0
                  z-20
                  bg-gray-50
                  border
                  border-gray-200
                  rounded-2xl
                  p-5
                  mb-6
                  shadow-sm
              ">


                <div class="flex items-center gap-2 mb-4">

                    <span class="text-lg">
                        🔎
                    </span>

                    <h3 class="font-semibold text-gray-700">
                        Filter Purchase Orders
                    </h3>

                </div>



                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    <!-- END USER -->
                    <div>

                        <label class="
                            block
                            text-sm
                            font-semibold
                            text-gray-700
                            mb-2">

                            End User

                        </label>


                        <select id="bulk_end_user"
                            class="
                            w-full
                            rounded-xl
                            border-gray-300
                            bg-white
                            px-4
                            py-3
                            shadow-sm
                            focus:ring-2
                            focus:ring-green-400
                            focus:border-green-400
                            outline-none
                            transition"
                            onchange="handleEndUserChange()">


                            <option value="">
                                All End Users
                            </option>


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


                        <label class="
                            block
                            text-sm
                            font-semibold
                            text-gray-700
                            mb-2">

                            Supplier

                        </label>


                        <select id="bulk_supplier"
                            class="
                            w-full
                            rounded-xl
                            border-gray-300
                            bg-white
                            px-4
                            py-3
                            shadow-sm
                            focus:ring-2
                            focus:ring-green-400
                            focus:border-green-400
                            outline-none
                            transition"
                            onchange="loadBulkPOs()">



                            <option value="">
                                All Suppliers
                            </option>



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


            </div>





            <!-- TABLE CARD -->
<!-- TABLE CARD -->
<div class="
    bg-white
    border
    border-gray-200
    rounded-2xl
    overflow-hidden
    shadow-md
">


    <!-- TABLE HEADER TITLE -->
    <div class="
        px-5
        py-4
        bg-gradient-to-r
        from-gray-50
        to-white
        border-b
        flex
        items-center
        justify-between">

        <div>

            <h3 class="
                font-bold
                text-gray-700
                flex
                items-center
                gap-2">

                📦 Purchase Order List

            </h3>


            <p class="
                text-xs
                text-gray-500
                mt-1">

                Select purchase orders for bulk evaluation.

            </p>

        </div>


        <div class="
            text-xs
            bg-green-100
            text-green-700
            px-3
            py-1
            rounded-full
            font-semibold">

            Available PO

        </div>


    </div>




    <!-- TABLE WRAPPER -->
    <div class="
        overflow-auto
        max-h-[450px]">


        <table class="
            w-full
            text-sm
            border-collapse">


            <!-- HEADER -->
            <thead class="sticky top-0 z-20">


                <tr class="
                    bg-gradient-to-r
                    from-gray-100
                    to-gray-200
                    text-gray-600
                    uppercase
                    text-xs
                    tracking-wide">


                    <!-- CHECK ALL -->
                    <th class="
                        px-5
                        py-4
                        border-b
                        text-center
                        w-14">


                        <input type="checkbox"
                            id="checkAllPO"
                            class="
                            w-5
                            h-5
                            rounded
                            border-gray-300
                            text-green-600
                            focus:ring-green-500
                            cursor-pointer">


                    </th>



                    <th class="
                        px-5
                        py-4
                        border-b
                        text-left
                        font-bold">

                        PO No

                    </th>



                    <th class="
                        px-5
                        py-4
                        border-b
                        text-left
                        font-bold">

                        PR No

                    </th>



                    <th class="
                        px-5
                        py-4
                        border-b
                        text-left
                        font-bold">

                        End User

                    </th>



                    <th class="
                        px-5
                        py-4
                        border-b
                        text-left
                        font-bold">

                        Supplier

                    </th>



                    <th class="
                        px-5
                        py-4
                        border-b
                        text-center
                        font-bold">

                        Status

                    </th>


                </tr>


            </thead>





            <!-- BODY -->
            <tbody id="bulkPOBody"
                class="
                divide-y
                divide-gray-100
                text-gray-700">


                <tr>

                    <td colspan="6"
                        class="
                        py-12
                        text-center
                        text-gray-400">


                        <div class="
                            flex
                            flex-col
                            items-center
                            gap-2">


                            <span class="text-4xl">
                                📄
                            </span>


                            <span>
                                No data loaded.
                            </span>


                        </div>


                    </td>


                </tr>


            </tbody>


        </table>


    </div>


</div>



        </div>





        <!-- FOOTER -->
        <div class="
            border-t
            bg-gray-50
            px-7
            py-5
            flex
            items-center
            justify-between">


            <div class="
                flex
                items-center
                gap-2
                text-sm
                text-gray-600">


                <span>
                    Selected:
                </span>


                <span id="selectedPOCount"
                    class="
                    bg-green-100
                    text-green-700
                    px-3
                    py-1
                    rounded-full
                    font-bold">

                    0

                </span>


            </div>



            <div class="flex gap-3">


                <button type="button"
                    onclick="closeBulkAddEvaluateModal()"
                    class="
                    px-5
                    py-2.5
                    rounded-xl
                    border
                    border-gray-300
                    text-gray-600
                    hover:bg-gray-100
                    transition">


                    Cancel


                </button>



                <button type="button"
                    id="submitBulkBtn"
                    onclick="submitBulkEvaluation()"
                    class="
                    px-6
                    py-2.5
                    rounded-xl
                    bg-green-600
                    text-white
                    font-semibold
                    shadow
                    hover:bg-green-700
                    hover:shadow-lg
                    transition">


                    ✓ Add Selected


                </button>


            </div>


        </div>


    </div>


</div>




<script>

function openBulkAddEvaluateModal() {

    const modal = document.getElementById('bulkAddEvaluateModal');

    // Show modal + enable center alignment
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Load purchase orders
    loadBulkPOs();

    // Close PO list modal
    closePOModal_v2();
}


function closeBulkAddEvaluateModal() {

    const modal = document.getElementById('bulkAddEvaluateModal');

    // Hide modal
    modal.classList.add('hidden');

    // Remove flex display
    modal.classList.remove('flex');
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

<tr class="
    hover:bg-green-50
    transition
    duration-200">


    <td class="
        px-5
        py-4
        text-center">


        <input type="checkbox"
            class="
            poCheckbox
            w-5
            h-5
            rounded
            border-gray-300
            text-green-600
            focus:ring-green-500
            cursor-pointer"
            value="${po.id}">


    </td>



    <td class="
        px-5
        py-4
        font-semibold
        text-gray-800">

        ${po.po_no ?? '-'}

    </td>



    <td class="
        px-5
        py-4">

        ${po.pr_no ?? '-'}

    </td>



    <td class="
        px-5
        py-4">

        ${po.end_user ?? '-'}

    </td>



    <td class="
        px-5
        py-4">

        ${po.supplier ?? '-'}

    </td>



    <td class="
        px-5
        py-4
        text-center">


        <span class="
            inline-flex
            items-center
            px-3
            py-1
            rounded-full
            text-xs
            font-semibold
            bg-green-100
            text-green-700">


            ✓ Available


        </span>


    </td>


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
