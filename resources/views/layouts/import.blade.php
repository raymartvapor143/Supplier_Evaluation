<!-- Import Modal -->
@auth
    @if (auth()->user()->isAdmin())
<div id="importModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative overflow-hidden">

        <!-- HEADER -->
<h2 class="text-xl font-semibold mb-3 text-gray-800">
    Import Purchase Orders
</h2>

<div class="mb-4 p-3 rounded-lg bg-orange-50 border border-orange-200 text-sm text-orange-700">
    📦 Allowed file types: <strong>.xlsx, .csv</strong>
</div>

        <!-- FORM -->
        <form id="importPoForm"
              action="{{ route('po.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">

            @csrf

            <!-- FILE INPUT -->
            <input type="file"
                   name="file"
                   accept=".xlsx,.csv"
                   required
                   class="w-full border border-gray-300 rounded-lg p-3 text-sm
                          focus:ring-2 focus:ring-orange-400 focus:outline-none">

            <!-- ACTION BUTTONS -->
            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                        onclick="closeImportModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg
                               hover:bg-gray-300 transition">
                    Cancel
                </button>

                <button id="uploadBtn"
                        type="submit"
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg
                               hover:bg-orange-600 transition flex items-center gap-2">

                    <span id="btnText">Upload</span>

                    <!-- Spinner -->
                    <svg id="btnSpinner"
                         class="hidden w-4 h-4 animate-spin"
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="white" stroke-width="4"></circle>
                        <path class="opacity-75" fill="white"
                              d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>

                </button>
            </div>
        </form>

        <!-- FULL MODAL LOADING OVERLAY -->
        <div id="importLoading"
             class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center">

            <div class="w-14 h-14 border-4 border-orange-200 border-t-orange-500 rounded-full animate-spin"></div>

            <p class="mt-4 text-gray-700 font-semibold text-sm">
                Importing Purchase Orders...
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Please wait while we process your file
            </p>
        </div>

    </div>
</div>
<script>
function openImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('importPoForm');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // UI elements
        const btn = document.getElementById('uploadBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const loading = document.getElementById('importLoading');

        // START LOADING STATE
        btn.disabled = true;
        btnText.textContent = "Uploading...";
        btnSpinner.classList.remove('hidden');
        loading.classList.remove('hidden');

        fetch(this.action, {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));

            // RESET LOADING STATE
            btn.disabled = false;
            btnText.textContent = "Upload";
            btnSpinner.classList.add('hidden');
            loading.classList.add('hidden');

            if (!res.ok || !data.success) {
                throw new Error(data.message || 'Import failed');
            }

            const inserted = data.inserted_count ?? 0;
            const duplicates = data.duplicates ?? [];

            let duplicatesHtml = '';

            if (duplicates.length > 0) {
                duplicatesHtml = `
                    <div style="
                        margin-top: 15px;
                        padding: 12px;
                        border: 1px solid #f59e0b;
                        border-radius: 12px;
                        max-height: 250px;
                        overflow-y: auto;
                        background: #fff7ed;
                        text-align: left;
                    ">
                        <strong style="color:#b45309;">
                            Duplicate PO Numbers Skipped (${duplicates.length})
                        </strong>
                        <ul style="margin-top:8px; padding-left:18px; color:#92400e;">
                            ${duplicates.map(po => `<li>${po}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }

            Swal.fire({
                title: 'Import Completed',
                icon: 'success',
                width: '750px',
                html: `
                    <div style="font-size:14px; text-align:left;">

                        <div style="
                            display:flex;
                            gap:10px;
                            margin-bottom:12px;
                            flex-wrap:wrap;
                        ">

                            <div style="padding:10px 14px; background:#ecfdf5; border:1px solid #10b981; border-radius:10px;">
                                <strong>Inserted:</strong> ${inserted}
                            </div>

                            <div style="padding:10px 14px; background:#fff7ed; border:1px solid #f59e0b; border-radius:10px;">
                                <strong>Duplicates:</strong> ${duplicates.length}
                            </div>

                        </div>

                        <p style="margin-bottom:10px;">
                            ${data.message}
                        </p>

                        ${duplicatesHtml}

                    </div>
                `,
                confirmButtonColor: '#f97316'
            });

            closeImportModal();
            form.reset();

        })
        .catch(err => {

            btn.disabled = false;
            btnText.textContent = "Upload";
            btnSpinner.classList.add('hidden');
            loading.classList.add('hidden');

            Swal.fire({
                icon: 'error',
                title: 'Import Failed',
                text: err.message || 'Something went wrong during import.'
            });
        });
    });

});
</script>
    @endif
@endauth


<div id="importAuthorizeModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

<h2 class="text-xl font-semibold mb-1">
    Upload Authorize Personnel File
</h2>

<p class="text-sm mb-4">
    <span class="text-red-500 font-medium">PDF only</span> — please upload a valid document.
</p>

        <form action="{{ route('authorize.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="file" name="pdf_file" accept=".pdf"
                   class="w-full border rounded-lg p-3 mb-4" required>

            <div class="flex justify-end gap-2">
<button type="button" onclick="closeImportAuthorizeModal()"
        class="px-4 py-2 bg-gray-200 rounded-lg">
    Cancel
</button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                    Upload
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function importAuthorizeModal() {
    const modal = document.getElementById('importAuthorizeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImportAuthorizeModal() {
    const modal = document.getElementById('importAuthorizeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>


<div id="importOptionsModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-center">

        <h2 class="text-xl font-semibold mb-6 text-gray-800">
            Select Import Type
        </h2>

        <div class="space-y-4">
@auth
    @if (auth()->user()->isAdmin())
            <!-- IMPORT PO -->
            <button onclick="selectImport('po')"
                class="w-full px-4 py-3 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition">
                📦 Import Purchase Orders
            </button>
    @endif
@endauth

            <!-- IMPORT AUTHORIZE -->
            <button onclick="selectImport('authorize')"
                class="w-full px-4 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition">
                📄 Import Authorize Personnel File
            </button>

        </div>

        <button onclick="closeImportOptions()"
            class="mt-6 text-sm text-gray-500 hover:text-gray-700">
            Cancel
        </button>

    </div>
</div>

<script>
function openImportOptions() {
    document.getElementById('importOptionsModal').classList.remove('hidden');
    document.getElementById('importOptionsModal').classList.add('flex');
}

function closeImportOptions() {
    document.getElementById('importOptionsModal').classList.add('hidden');
    document.getElementById('importOptionsModal').classList.remove('flex');
}

function selectImport(type) {
    closeImportOptions();

    if (type === 'po') {
        openImportModal(); // existing PO modal
    } else if (type === 'authorize') {
        importAuthorizeModal(); // existing authorize modal
    }
}
</script>


