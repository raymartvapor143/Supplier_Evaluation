<script>
async function openRecycleBinModal() {
    try {
        Swal.fire({
            title: 'Recycle Bin',
            width: '95%', // 🔥 extra large
            showConfirmButton: false,
            showCloseButton: true,
            allowOutsideClick: true,
            didOpen: async () => {
                await loadDeletedEvaluations();
            },
            html: `
                <div class="text-left">

                    <!-- INFO PANEL -->
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-4">
                        <h3 class="font-semibold text-orange-800 mb-2">🗑️ Recycle Bin Info</h3>
                        <ul class="text-sm text-orange-700 space-y-1">
                            <li>• Deleted evaluations are stored temporarily.</li>
                            <li>• You can restore them anytime within <b>30 days</b>.</li>
                            <li>• After 30 days, they will be <b>permanently deleted automatically</b>.</li>
                        </ul>
                    </div>

                    <!-- TABLE -->
                    <div class="overflow-x-auto max-h-[500px] border rounded-xl">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2">Supplier</th>
                                    <th class="px-4 py-2">PO No</th>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Deleted At</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="deletedEvaluationsTable">
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-500">
                                        Loading...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            `
        });

    } catch (err) {
        console.error(err);
        Swal.fire("Error", "Failed to open recycle bin.", "error");
    }
}




async function loadDeletedEvaluations() {
    try {
        const res = await safeFetch(`/evaluations/deleted/list`);
        const data = await res.json();

        const table = document.getElementById('deletedEvaluationsTable');

        if (!data.length) {
            table.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-500">
                        No deleted evaluations found.
                    </td>
                </tr>
            `;
            return;
        }

        table.innerHTML = data.map(item => `
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">${item.supplier_name}</td>
                <td class="px-4 py-2">${item.po_no}</td>
                <td class="px-4 py-2">${item.date_evaluation}</td>
                <td class="px-4 py-2 text-red-500">${item.deleted_at ?? '—'}</td>
                <td class="px-4 py-2 text-center space-x-2">

                    <button onclick="restoreEvaluation(${item.id})"
                        class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 text-xs">
                        Restore
                    </button>

                    <button onclick="forceDeleteEvaluation(${item.id})"
                        class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-xs">
                        Delete Permanently
                    </button>

                </td>
            </tr>
        `).join('');

    } catch (err) {
        console.error(err);
    }
}


async function restoreEvaluation(id) {
    const confirm = await Swal.fire({
        title: "Restore evaluation?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#22c55e"
    });

    if (!confirm.isConfirmed) return;

    await safeFetch(`/evaluations/${id}/restore`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    });

    loadDeletedEvaluations();
}

async function forceDeleteEvaluation(id) {
    const confirm = await Swal.fire({
        title: "Delete permanently?",
        text: "This cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444"
    });

    if (!confirm.isConfirmed) return;

    await safeFetch(`/evaluations/${id}/force-delete`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    });

    loadDeletedEvaluations();
}

</script>
