<div id="bulkEvaluateModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white w-96 rounded-xl shadow-lg p-5">

        <h2 class="text-lg font-semibold mb-4">Bulk Evaluate Suppliers</h2>

        <select id="supplierSelect" class="w-full border rounded-lg p-2">
            <option value="">Loading suppliers...</option>
        </select>

        <div class="flex justify-end mt-4 space-x-2">
            <button onclick="closeBulkEvaluateModal()" class="px-4 py-2 text-gray-600">
                Cancel
            </button>

            <button onclick="startBulkEvaluation()" class="px-4 py-2 bg-orange-500 text-white rounded-lg">
                Evaluate
            </button>
        </div>

    </div>
</div>


<script>
const bulkSupplierUrl = "{{ url('/evaluations/bulk-suppliers') }}";

window.openBulkEvaluateModal = function () {
    document.getElementById('bulkEvaluateModal').classList.remove('hidden');

    fetch(bulkSupplierUrl)
        .then(res => res.json())
        .then(data => {
            let select = document.getElementById('supplierSelect');

            select.innerHTML = '<option value="">Select Supplier</option>';

            data.forEach(item => {
                let option = document.createElement('option');

                option.value = item.supplier_name;

                // 👇 show count here
                option.textContent = `${item.supplier_name} (${item.total})`;

                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error("Failed loading suppliers:", err);
        });
};

window.closeBulkEvaluateModal = function () {
    document.getElementById('bulkEvaluateModal').classList.add('hidden');
};

window.startBulkEvaluation = function () {

    let supplier = document.getElementById('supplierSelect').value;

    if (!supplier) {
        alert("Please select a supplier.");
        return;
    }

    const url = `/evaluations/bulk?supplier=${encodeURIComponent(supplier)}`;

    window.open(url, '_blank'); // NEW TAB
};
</script>
