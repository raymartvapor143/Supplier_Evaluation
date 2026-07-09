<div id="request-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<!-- Modal Box -->
<div id="request-modal-content"
     class="bg-white w-full h-full rounded-none shadow-xl flex flex-col overflow-hidden transform transition-all duration-300 scale-95 opacity-0">

  <!-- Header -->
  <div class="flex justify-between items-center px-6 py-4 border-b">
    <h2 class="text-xl font-semibold">Requests</h2>
    <div class="flex items-center gap-3">
      <!-- Create Request Button -->
<button
  id="toggle-create-btn"
  onclick="toggleCreateRequestPanel()"
  class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
  Create Request
</button>

      <!-- Close Button -->
<button
    onclick="closeRequestModal()"
    class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 hover:bg-red-500 text-gray-700 hover:text-white transition-colors duration-300 shadow-md hover:shadow-lg text-2xl">
    &times;
</button>
    </div>
  </div>

<!-- Body -->
<div class="p-6 overflow-y-auto flex-1 space-y-6 bg-gray-50">



  <!-- Request List Table -->
  <div id="request-list-panel" class="transition-all duration-300 ease-in-out">

    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row md:items-end gap-4 mb-4">
      <div class="flex-1 relative">
        <label class="block text-sm font-medium text-gray-700 mb-1">Search / PO Number</label>
        <input
          id="po-search-input"
          type="text"
          placeholder="Search or select PO Number..."
          autocomplete="off"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
          onfocus="showDropdown()"
          oninput="filterDropdown()"
        />
        <div
          id="po-dropdown"
          class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 shadow-lg max-h-60 overflow-y-auto hidden"
        ></div>
      </div>

      <div>
        <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
          Search
        </button>
      </div>
    </div>

    <!-- Requests Table -->
    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm bg-white">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 border-b">
          <tr>
            <th class="px-4 py-3">No.</th>
            <th class="px-4 py-3">PO Number</th>
            <th class="px-4 py-3">Supplier Name</th>
            <th class="px-4 py-3">Office Name</th>
            <th class="px-4 py-3">Request Type</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Date Created</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody id="request-table-body" class="transition-all duration-300"></tbody>

      </table>
        <!-- Loading Spinner -->
  <div id="loading-spinner" class="hidden flex justify-center items-center py-6">
    <div class="w-12 h-12 border-4 border-blue-300 border-t-blue-600 rounded-full animate-spin"></div>
  </div>
    </div>
  </div>

  <!-- Create Request Panel (hidden by default) -->
  <div id="create-request-panel" class="hidden transition-all duration-300 ease-in-out space-y-6 bg-white p-4 rounded-lg shadow-md">

    <!-- PO Search -->
    <div class="flex flex-col md:flex-row md:items-end gap-4">
      <div class="flex-1 relative">
        <label class="block text-sm font-medium text-gray-700 mb-1">PO Number</label>
        <input
          id="create-po-search-input"
          type="text"
          placeholder="Search or select PO Number..."
          autocomplete="off"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
          onfocus="showCreateDropdown()"
          oninput="filterCreateDropdown()"
        />
        <div
          id="create-po-dropdown"
          class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 shadow-lg max-h-60 overflow-y-auto hidden"
        ></div>
      </div>
    </div>

<div>
  <label class="block text-sm font-medium text-gray-700 mb-1">
    Request for:
  </label>

  <select
    id="request_type"
    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
    required
  >
    <option value="" disabled selected>Select request type</option>
    <option value="update">Update Evaluation</option>
    <option value="delete">Delete Evaluation</option>
  </select>
</div>

    <!-- Reason -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
      <textarea
        id="request-reason"
        rows="6"
        placeholder="Enter request details....."
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
      ></textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Request To:
      </label>
      <select
            name="requested_to"
            id="requested_to"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 shadow-sm focus:ring focus:ring-orange-200"
            required>
            <option value="" disabled selected>Select recipient</option>

            @foreach($users as $user)
                @if($user->role !== 'pgso')
                    <option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->role }})
                    </option>
                @endif
            @endforeach

        </select>

    </div>



    <!-- Status (readonly) -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
      <input
        type="text"
        value="Request"
        readonly
        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-700 shadow-sm"
      />
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
      <button
        onclick="submitCreateRequest()"
        class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition shadow-md"
      >
        Submit Request
      </button>
    </div>

  </div>
</div>
</div>
</div>

<script>
    window.userRole = "{{ auth()->user()->role }}";
</script>

<script>
const modal = document.getElementById('request-modal');
const modalContent = document.getElementById('request-modal-content');
const tableBody = document.getElementById('request-table-body');
const input = document.getElementById('po-search-input');
const dropdown = document.getElementById('po-dropdown');
const requestListPanel = document.getElementById('request-list-panel');
const createRequestPanel = document.getElementById('create-request-panel');
const createInput = document.getElementById('create-po-search-input');
const createDropdown = document.getElementById('create-po-dropdown');
const reasonInput = document.getElementById('request-reason');
const toggleCreateBtn = document.getElementById('toggle-create-btn');
const spinner = document.getElementById('loading-spinner');

let tableData = [];      // status = 'request'
let dropdownData = [];   // status = 'submitted'
let isDataFetched = false; // track if data has been fetched already

// ===== SPINNER =====
function showLoadingSpinner() { spinner.classList.remove('hidden'); }
function hideLoadingSpinner() { spinner.classList.add('hidden'); }

// ===== DROPDOWNS =====
function showDropdown() { dropdown.classList.remove('hidden'); }
function hideDropdown() { dropdown.classList.add('hidden'); }
function showCreateDropdown() { createDropdown.classList.remove('hidden'); }
function hideCreateDropdown() { createDropdown.classList.add('hidden'); }

function selectPO(value) {
  input.value = value;
  hideDropdown();
  populateTable(tableData.filter(e => e.po_no === value));
}

function filterDropdown() {
  const filter = input.value.toLowerCase();
  dropdown.querySelectorAll('div').forEach(item => {
    item.style.display = item.textContent.toLowerCase().includes(filter) ? 'block' : 'none';
  });
  populateTable(tableData.filter(e => e.po_no.toLowerCase().includes(filter)));
  showDropdown();
}

function selectCreatePO(value) {
  createInput.value = value;
  hideCreateDropdown();
}

function filterCreateDropdown() {
  const filter = createInput.value.toLowerCase();
  createDropdown.querySelectorAll('div').forEach(item => {
    item.style.display = item.textContent.toLowerCase().includes(filter) ? 'block' : 'none';
  });
  showCreateDropdown();
}

// ===== MODAL OPEN/CLOSE =====
async function openRequestModal() {
  modal.classList.remove('hidden');
  modalContent.style.transform = 'scale(0.95)';
  modalContent.style.opacity = '0';
  void modalContent.offsetWidth;
  modalContent.style.transition = 'transform 0.2s ease, opacity 0.2s ease';
  modalContent.style.transform = 'scale(1)';
  modalContent.style.opacity = '1';

  requestListPanel.classList.remove('hidden');
  createRequestPanel.classList.add('hidden');
  toggleCreateBtn.textContent = 'Create Request';

  // Populate table & dropdown from cached data
  populateTable(tableData);
  populateDropdown(tableData);
  populateCreateDropdown(dropdownData);
}

function closeRequestModal() {
  modalContent.style.transform = 'scale(0.95)';
  modalContent.style.opacity = '0';
  setTimeout(() => {
    modal.classList.add('hidden');
    tableBody.innerHTML = '';
    input.value = '';
    createInput.value = '';
    reasonInput.value = '';
    hideDropdown();
    hideCreateDropdown();
    toggleCreateBtn.textContent = 'Create Request';
    // cached data stays in tableData & dropdownData
  }, 200);
}

// ===== FETCH DATA =====
async function fetchRequests() {
  showLoadingSpinner();
  try {
    const resTable = await fetch('/requests-for-table');
    tableData = await resTable.json();
    populateTable(tableData);
    populateDropdown(tableData);

    const resDropdown = await safeFetch('/requests-for-dropdown');
    dropdownData = await resDropdown.json();
    populateCreateDropdown(dropdownData);
    isDataFetched = true;
  } catch (err) {
    console.error(err);
    tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-red-500">Failed to load requests.</td></tr>`;
  } finally {
    hideLoadingSpinner();
  }
}

// ===== TABLE =====
function populateTable(data) {
  tableBody.innerHTML = '';
  if (!data.length) {
    tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-gray-500">No requests found.</td></tr>`;
    return;
  }

data.forEach((item, idx) => {
    const row = document.createElement('tr');
    row.className = 'border-b opacity-0 transform translate-y-2 hover:bg-gray-50 transition-all';

    // ✅ Only show action buttons if status is NOT rejected
let actionButtons = '';

// Define statuses where request is considered "completed"
const completedStatuses = ['rejected', 'approved', 'done', 'cancelled'];

if (window.userRole === 'administrator' || window.userRole === 'pgso') {
    if (!completedStatuses.includes(item.status)) {
        // Admin: active requests → Approve + Reject + View
        actionButtons = `
            <button class="bg-green-500 text-white p-2 rounded hover:bg-green-600 transition" onclick="approveRequest(${item.id})" title="Approve">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
            <button class="bg-red-500 text-white p-2 rounded hover:bg-red-600 transition" onclick="rejectRequest(${item.id})" title="Reject">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition" onclick="openRequestViewModal(${item.id})" title="View">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        `;
    } else {
        // Admin: completed requests → only View
        actionButtons = `
            <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition" onclick="openRequestViewModal(${item.id})" title="View">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        `;
    }
} else if (window.userRole === 'end_user' || window.userRole === 'presentative_staff') {
    if (!completedStatuses.includes(item.status)) {
        // End user: active requests → Cancel + View
        actionButtons = `
            <button class="bg-red-500 text-white p-2 rounded hover:bg-red-600 transition" onclick="cancelRequest(${item.id})" title="Cancel Request">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition" onclick="openRequestViewModal(${item.id})" title="View">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        `;
    } else {
        // End user: completed requests → only View
        actionButtons = `
            <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition" onclick="openRequestViewModal(${item.id})" title="View">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        `;
    }
}
    const requestTypeLabel = item.request_type === 'delete'
        ? 'Delete Evaluation'
        : 'Update Evaluation';

    row.innerHTML = `

      <td class="px-4 py-2">${idx + 1}</td>
      <td class="px-4 py-2">${item.po_no}</td>
      <td class="px-4 py-2">${item.supplier_name}</td>
      <td class="px-4 py-2">${item.office_name || '-'}</td>
      <td class="px-4 py-2">${requestTypeLabel}</td>
      <td class="px-4 py-2">${item.status}</td>
      <td class="px-4 py-2">${item.request_date ? new Date(item.request_date).toLocaleDateString() : '-'}</td>
      <td class="px-4 py-2 text-center space-x-2">
        ${actionButtons}
      </td>`;

    tableBody.appendChild(row);
    setTimeout(() => {
        row.classList.remove('opacity-0', 'translate-y-2');
        row.classList.add('opacity-100', 'translate-y-0');
    }, idx * 50);
});
}

// ===== DROPDOWN POPULATE =====
function populateDropdown(data) {
  dropdown.innerHTML = '';
  data.forEach(item => {
    const div = document.createElement('div');
    div.textContent = item.po_no;
    div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer';
    div.onclick = () => selectPO(item.po_no);
    dropdown.appendChild(div);
  });
}

function populateCreateDropdown(data) {
  createDropdown.innerHTML = '';
  data.forEach(item => {
    const div = document.createElement('div');
    div.textContent = item.po_no;
    div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer';
    div.onclick = () => selectCreatePO(item.po_no);
    createDropdown.appendChild(div);
  });
}

// ===== CREATE PANEL TOGGLE =====
function toggleCreateRequestPanel() {
  const isVisible = !createRequestPanel.classList.contains('hidden');
  if (isVisible) {
    createRequestPanel.classList.add('hidden');
    requestListPanel.classList.remove('hidden');
    toggleCreateBtn.textContent = 'Create Request';
    createInput.value = '';
    reasonInput.value = '';
    hideCreateDropdown();
  } else {
    requestListPanel.classList.add('hidden');
    createRequestPanel.classList.remove('hidden');
    toggleCreateBtn.textContent = 'Cancel';
    populateCreateDropdown(dropdownData);
  }
}

// ===== CREATE REQUEST SUBMIT =====
async function submitCreateRequest() {
const poNo = createInput.value.trim();
const reason = reasonInput.value.trim();
const requestType = document.getElementById('request_type').value;

if (!poNo || !reason || !requestType) {
  return Swal.fire({
    icon:'warning',
    title:'Missing Fields',
    text:'Please fill PO number, request type, and reason.'
  });
}

  Swal.fire({ title:'Saving...', text:'Please wait', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });

  try {
    const res = await safeFetch('/requests/store', {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
      body: JSON.stringify({
        po_no: poNo,
        reason: reason,
        requested_to: document.getElementById('requested_to').value,
        request_type: requestType
      })
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message||'Failed to create request');

    createInput.value=''; reasonInput.value=''; hideCreateDropdown();
    toggleCreateRequestPanel(); tableBody.innerHTML='';
    await fetchRequests();

    Swal.fire({ icon:'success', title:'Success!', text:data.message||'Request created successfully!', timer:2000, showConfirmButton:false });

  } catch(err){ console.error(err); Swal.fire({ icon:'error', title:'Error', text:err.message }); }
}

// ===== APPROVE / REJECT =====
async function approveRequest(requestId) {
  const result = await Swal.fire({ title:'Approve this request?', icon:'question', showCancelButton:true, confirmButtonText:'Yes, approve', cancelButtonText:'Cancel' });
  if (!result.isConfirmed) return;
  Swal.fire({ title:'Processing...', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
  try {
    const res = await safeFetch(`/requests/${requestId}/approve`, { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' } });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message||'Failed to approve');
    Swal.fire({ icon:'success', title:'Approved!', text:data.message, timer:2000, showConfirmButton:false });
    await fetchRequests();
  } catch(err){ console.error(err); Swal.fire({ icon:'error', title:'Error', text:err.message }); }
}

async function rejectRequest(requestId) {
  const result = await Swal.fire({ title:'Reject this request?', icon:'warning', showCancelButton:true, confirmButtonText:'Yes, reject', cancelButtonText:'Cancel' });
  if (!result.isConfirmed) return;
  Swal.fire({ title:'Processing...', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
  try {
    const res = await safeFetch(`/requests/${requestId}/reject`, { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' } });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message||'Failed to reject');
    Swal.fire({ icon:'success', title:'Rejected!', text:data.message, timer:2000, showConfirmButton:false });
    await fetchRequests();
  } catch(err){ console.error(err); Swal.fire({ icon:'error', title:'Error', text:err.message }); }
}

// ===== CANCEL REQUEST =====
async function cancelRequest(requestId) {
  // 1️⃣ Ask for confirmation
  const result = await Swal.fire({
    title: 'Cancel this request?',
    text: "You won't be able to undo this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, cancel it',
    cancelButtonText: 'No'
  });
  if (!result.isConfirmed) return;

  // 2️⃣ Show processing/loading
  Swal.fire({
    title: 'Processing...',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  try {
    // 3️⃣ Send POST request to Laravel endpoint
    const res = await fetch(`/requests/${requestId}/cancel`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    const data = await res.json();

    // 4️⃣ Handle errors
    if (!res.ok) throw new Error(data.message || 'Failed to cancel request');

    // 5️⃣ Success message
    Swal.fire({
      icon: 'success',
      title: 'Cancelled!',
      text: data.message || 'Your request has been cancelled.',
      timer: 2000,
      showConfirmButton: false
    });

    // 6️⃣ Refresh table
    await fetchRequests(); // assumes you have fetchRequests() to reload table
  } catch (err) {
    console.error(err);
    Swal.fire({ icon: 'error', title: 'Error', text: err.message });
  }
}

// ===== GLOBAL LISTENERS =====
document.addEventListener('click', e => {
  if (!e.target.closest('#po-search-input') && !e.target.closest('#po-dropdown')) hideDropdown();
  if (!e.target.closest('#create-po-search-input') && !e.target.closest('#create-po-dropdown')) hideCreateDropdown();
});
modal.addEventListener('click', e => { if (e.target === modal) closeRequestModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRequestModal(); });

// ===== EXPOSE FUNCTIONS =====
window.openRequestModal = openRequestModal;
window.closeRequestModal = closeRequestModal;
window.toggleCreateRequestPanel = toggleCreateRequestPanel;
window.submitCreateRequest = submitCreateRequest;
window.showDropdown = showDropdown;
window.showCreateDropdown = showCreateDropdown;
window.filterDropdown = filterDropdown;
window.filterCreateDropdown = filterCreateDropdown;

// ===== AUTOMATIC DATA FETCH ON PAGE LOAD =====
document.addEventListener('DOMContentLoaded', fetchRequests);
</script>


<!-- Modern Request Details Modal -->
<div id="request-view-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 transition-opacity duration-300">

  <!-- Modal Card -->
  <div class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 animate-[fadeIn_.2s_ease-out_forwards]">

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 bg-gradient-to-r from-indigo-600 to-blue-600">
      <div>
        <h3 class="text-xl font-semibold text-white">
          Request Details
        </h3>
        <p class="text-sm text-indigo-100 mt-1">
          View complete request information
        </p>
      </div>

      <button
        onclick="closeRequestViewModal()"
        class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 hover:rotate-90 duration-200">
        ✕
      </button>
    </div>

    <!-- Content -->
    <div id="request-view-content"
         class="max-h-[70vh] overflow-y-auto px-6 py-6 bg-orange-100/80">
      <!-- Dynamic content -->
    </div>

    <!-- Footer -->
    <div class="flex justify-end gap-3 border-t border-gray-100 bg-white px-6 py-4">
      <button
        onclick="closeRequestViewModal()"
        class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
        Close
      </button>
    </div>
  </div>
</div>

<script>
  function openRequestViewModal(id) {
    const modal = document.getElementById('request-view-modal');
    const content = document.getElementById('request-view-content');

    // Find request
    const request = tableData.find(item => item.id === id);

    // Status badge colors
    const getStatusColor = (status) => {
      switch ((status || '').toLowerCase()) {
        case 'approved':
          return 'bg-green-100 text-green-700';
        case 'pending':
          return 'bg-yellow-100 text-yellow-700';
        case 'rejected':
          return 'bg-red-100 text-red-700';
        default:
          return 'bg-gray-100 text-gray-700';
      }
    };

    if (!request) {
      content.innerHTML = `
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-600">
          Request data not found.
        </div>
      `;
    } else {
      content.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

          <!-- PO Number -->
          <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
              PO Number
            </p>
            <p class="mt-2 text-base font-semibold text-gray-800">
              ${request.po_no || '-'}
            </p>
          </div>

          <!-- Supplier -->
          <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
              Supplier Name
            </p>
            <p class="mt-2 text-base font-semibold text-gray-800">
              ${request.supplier_name || '-'}
            </p>
          </div>

          <!-- Office -->
          <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
              Office Name
            </p>
            <p class="mt-2 text-base font-semibold text-gray-800">
              ${request.office_name || '-'}
            </p>
          </div>

          <!-- Request Type -->
          <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
              Request Type
            </p>
            <p class="mt-2 text-base font-semibold text-gray-800">
              ${request.request_type || '-'}
            </p>
          </div>

          <!-- Status -->
          <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
              Status
            </p>

            <span class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-sm font-medium ${getStatusColor(request.status)}">
              ${request.status || '-'}
            </span>
          </div>

          <!-- Date -->
          <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
              Date Created
            </p>
            <p class="mt-2 text-base font-semibold text-gray-800">
              ${request.request_date
                ? new Date(request.request_date).toLocaleDateString()
                : '-'}
            </p>
          </div>
        </div>

        ${
          request.reason
            ? `
          <!-- Reason -->
          <div class="mt-6 rounded-xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-3">
              Reason
            </p>

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm leading-relaxed text-gray-700 whitespace-pre-wrap">
              ${request.reason}
            </div>
          </div>
        `
            : ''
        }
      `;
    }

    // Show modal
    modal.classList.remove('hidden');

    // Animate in
    setTimeout(() => {
      modal.querySelector('div > div')?.classList.remove('scale-95');
      modal.querySelector('div > div')?.classList.add('scale-100');
    }, 10);

    // Prevent body scroll
    document.body.classList.add('overflow-hidden');
  }

  function closeRequestViewModal() {
    const modal = document.getElementById('request-view-modal');

    modal.classList.add('hidden');

    // Restore body scroll
    document.body.classList.remove('overflow-hidden');
  }

  // Close on outside click
  document.getElementById('request-view-modal')
    .addEventListener('click', function (event) {
      if (event.target === this) {
        closeRequestViewModal();
      }
    });

  // Close on ESC key
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeRequestViewModal();
    }
  });
</script>





