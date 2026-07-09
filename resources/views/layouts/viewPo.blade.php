{{-- <!-- Modern Edit PO Modal -->
<div id="poViewModal_v2"
     class="hidden fixed inset-0 z-50 items-center justify-center bg-orange-100/80 backdrop-blur-sm px-4">

    <!-- Modal Card -->
    <div class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl animate-[fadeIn_.2s_ease-out]">

        <!-- Header -->
        <div class="flex items-center justify-between bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-5">
            <div>
                <h2 class="text-xl font-bold text-white">
                    Edit Purchase Order
                </h2>
                <p class="text-sm text-orange-100">
                    Update purchase order information
                </p>
            </div>

            <button type="button"
                onclick="closePOEditModal_v2()"
                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 hover:rotate-90 duration-200">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form id="poEditForm_v2" method="POST" class="bg-orange-50">
            @csrf
            @method('PUT')

            <div class="space-y-5 px-6 py-6">

                <!-- PO Number -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">

                    </label>

                    <input type="text"
                        name="po_no"
                        id="edit_po_no_v2"
                        placeholder="Enter PO Number"
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                </div>

                <!-- PR Number -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        PR Number
                    </label>

                    <input type="text"
                        name="pr_no"
                        id="edit_pr_no_v2"
                        placeholder="Enter PR Number"
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                </div>

                <!-- Supplier -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Supplier
                    </label>

                    <input type="text"
                        name="supplier"
                        id="edit_supplier_v2"
                        placeholder="Enter supplier name"
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                </div>

                <!-- End User -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        End User
                    </label>

                    <input type="text"
                        name="end_user"
                        id="edit_end_user_v2"
                        placeholder="Enter end user"
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-100">
                </div>

                <!-- Status -->
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Status
                    </label>

                    <select name="status"
                        id="edit_status_v2"
                        class="w-full rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-100">

                        <option value="Pending">Pending</option>
                        <option value="Added">Added</option>
                        <option value="Approved">Approved</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

<!-- PDF -->
<div>
    <label class="mb-2 block text-sm font-semibold text-gray-700">
        Purchase Order PDF
    </label>

    <div id="pdf_container_v2" class="mt-1">
        <span class="text-gray-500 text-sm">
            No PDF uploaded
        </span>
    </div>
</div>

            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 border-t border-orange-100 bg-white px-6 py-4">

                <button type="button"
                    onclick="closePOEditModal_v2()"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit"
                    class="rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:scale-[1.02] hover:from-orange-600 hover:to-amber-600">
                    Update
                </button>

            </div>
        </form>
    </div>
</div>

<script>
function closePOEditModal_v2() {
    const modal = document.getElementById('poViewModal_v2');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    // restore scroll
    document.body.classList.remove('overflow-hidden');

    // optional reset
    const form = document.getElementById('poEditForm_v2');
    if (form) form.reset();
}

// ===============================
// OPEN EDIT MODAL
// ===============================
// function openPOEditModal_v2(poId, poNo, prNo, supplier, endUser, status) {

//     document.getElementById('edit_po_no_v2').value = poNo;
//     document.getElementById('edit_supplier_v2').value = supplier;
//     document.getElementById('edit_end_user_v2').value = endUser;

//     // status
//     document.getElementById('edit_status_v2').value = status;

//     // form action
//     document.getElementById('poEditForm_v2').action =
//         `/purchase-orders/${poId}`;

//     // show modal
//     const modal = document.getElementById('poViewModal_v2');

//     modal.classList.remove('hidden');
//     modal.classList.add('flex');

//     // prevent background scroll
//     document.body.classList.add('overflow-hidden');
// }

// close on outside click
document.getElementById('poViewModal_v2')
    .addEventListener('click', function(event) {
        if (event.target === this) {
            closePOEditModal_v2();
        }
    });

// close on ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePOEditModal_v2();
    }
});
</script> --}}
