<div id="officeModal" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center">

  <div class="bg-white w-full max-w-6xl rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

    <!-- HEADER (fixed) -->
    <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50 shrink-0">
      <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
        <i class="ri-building-2-line text-primary text-xl"></i>
        Office Management
      </h2>

      <button onclick="closeOffice()" class="text-gray-500 hover:text-red-500 text-2xl">
        &times;
      </button>
    </div>

    <!-- TABS (fixed) -->
    <div class="px-6 pt-4 flex gap-3 border-b bg-white shrink-0">
      <button onclick="showTab('list')" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-primary text-primary">
        Office List
      </button>

      <button onclick="showTab('add')" class="tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-primary">
        Add Office
      </button>

      <button onclick="showTab('import')" class="tab-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-primary">
        Import Excel
      </button>
    </div>

    <!-- BODY (SCROLLABLE AREA) -->
    <div class="p-6 overflow-y-auto flex-1">

      <!-- ================= OFFICE LIST ================= -->
      <div id="tab-list">

<div class="flex items-center mb-4">
  <div class="relative w-full">

    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
      <i class="ri-search-line"></i>
    </span>

    <input
      id="office_search"
      type="text"
      placeholder="Search by office name and head..."
      onkeyup="filterOffices()"
      class="w-full pl-10 pr-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary outline-none transition"
    >

  </div>
</div>

        <div class="border rounded-xl overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
              <tr>
                <th class="text-left px-4 py-3">Office Name</th>
                <th class="text-left px-4 py-3">Head</th>
                <th class="text-left px-4 py-3">Responsibility Number</th>
                <th class="text-right px-4 py-3">Action</th>
              </tr>
            </thead>

            <tbody id="officeTable" class="divide-y">
            </tbody>
          </table>
        </div>

      </div>

      <!-- ================= ADD OFFICE ================= -->
      <div id="tab-add" class="hidden">
        <div class="max-w-xl mx-auto bg-gray-50 p-6 rounded-xl border">

          <h3 class="text-md font-semibold mb-4">Add New Office</h3>

          <div class="space-y-3">
            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Office Name *</label>
              <input id="office_name" type="text" placeholder="Office name"
                class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Abbreviation</label>
              <input id="office_abbr" type="text" placeholder="Abbreviation (e.g. IT, HR)"
                class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Head of Office</label>
              <input id="office_head" type="text" placeholder="Head of office"
                class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Designation</label>
              <input id="office_designation" type="text" placeholder="Designation / Position"
                class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1">Responsibility Number</label>
              <input id="office_responsibility_number" type="text" placeholder="Responsibility center code/number"
                class="w-full border rounded-lg px-3 py-2 text-sm bg-white">
            </div>

            <button onclick="addOffice()"
              class="w-full bg-primary text-white py-2 rounded-lg hover:opacity-90 mt-2 font-medium">
              Save Office
            </button>
          </div>

        </div>
      </div>

      <!-- ================= IMPORT EXCEL ================= -->
      <div id="tab-import" class="hidden">
        <div class="max-w-xl mx-auto bg-gray-50 p-6 rounded-xl border text-center">

          <h3 class="text-md font-semibold mb-3">Import Offices (Excel)</h3>

          <p class="text-sm text-gray-500 mb-4">
            Columns required: abbreviation, office_name, head, designation, responsibility_number
          </p>

          <input type="file" id="officeExcel" accept=".xlsx,.xls"
            class="w-full border rounded-lg p-2 mb-4 bg-white">

          <button onclick="importOfficeExcel()"
            class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
            Upload & Import
          </button>

        </div>
      </div>

    </div>
  </div>
</div>

<script>
function showTab(tab) {
  document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
  document.getElementById('tab-' + tab).classList.remove('hidden');
}

function openOffice() {
  document.getElementById('officeModal').classList.remove('hidden');
  loadOffices();
}

function closeOffice() {
  document.getElementById('officeModal').classList.add('hidden');
}


// ================= LOAD DATA =================
async function loadOffices() {
  const res = await fetch('/offices/list');
  const data = await res.json();

  const table = document.getElementById('officeTable');
  let html = '';

  data.forEach(o => {
    html += `
      <tr class="hover:bg-orange-50"
          data-name="${(o.name ?? '').toLowerCase()}"
          data-head="${(o.head ?? '').toLowerCase()}"
          data-responsibility_number="${(o.responsibility_number ?? '').toLowerCase()}">

        <td class="px-4 py-2 office-name">${o.name}</td>
        <td class="px-4 py-2">${o.head ?? '-'}</td>
        <td class="px-4 py-2">${o.responsibility_number ?? '-'}</td>

        <td class="px-4 py-2 text-right">
          <button onclick="deleteOffice(${o.id})"
            class="text-red-500 hover:underline">
            Delete
          </button>
        </td>

      </tr>
    `;
  });

  table.innerHTML = html;
}

// ================= ADD =================
async function addOffice() {
  const name = document.getElementById('office_name').value.trim();
  const abbreviation = document.getElementById('office_abbr').value.trim();
  const head = document.getElementById('office_head').value.trim();
  const designation = document.getElementById('office_designation').value.trim();
  const responsibility_number = document.getElementById('office_responsibility_number').value.trim();

  if (!name) {
    return Swal.fire('Required', 'Office name required', 'warning');
  }

  await fetch('/offices/store', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      name,
      abbreviation,
      head,
      designation,
      responsibility_number
    })
  });

  document.getElementById('office_name').value = '';
  document.getElementById('office_abbr').value = '';
  document.getElementById('office_head').value = '';
  document.getElementById('office_designation').value = '';
  document.getElementById('office_responsibility_number').value = '';

  loadOffices();
  showTab('list');
}

// ================= DELETE =================
async function deleteOffice(id) {
  const confirm = await Swal.fire({
    title: 'Delete office?',
    icon: 'warning',
    showCancelButton: true
  });

  if (!confirm.isConfirmed) return;

  await fetch(`/offices/delete/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  });

  loadOffices();
}

// ================= IMPORT =================
async function importOfficeExcel() {
  const file = document.getElementById('officeExcel').files[0];

  if (!file) {
    return Swal.fire('Warning', 'Select file first', 'warning');
  }

  Swal.fire({
    title: 'Importing...',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  const formData = new FormData();
  formData.append('file', file);

  await fetch('/offices/import', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: formData
  });

  Swal.close();
  Swal.fire('Success', 'Imported successfully', 'success');

  loadOffices();
  showTab('list');
}

// ================= FILTER =================
let searchTimeout;

function filterOffices() {
  clearTimeout(searchTimeout);

  searchTimeout = setTimeout(() => {
    const val = document
      .getElementById('office_search')
      .value
      .toLowerCase()
      .trim();

    const rows = document.querySelectorAll('#officeTable tr[data-name]');

    rows.forEach(row => {
      const name = row.dataset.name || '';
      const head = row.dataset.head || '';
      const responsibility = row.dataset.responsibility_number || '';

      const match =
        name.includes(val) ||
        head.includes(val) ||
        responsibility.includes(val);

      row.style.display = match ? '' : 'none';
    });

  }, 120);
}
</script>
