

<div id="usersModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-6xl p-5">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Users</h2>
                <p class="text-xs text-gray-500">Manage users</p>
            </div>
            <button onclick="closeUsersModal()" class="w-9 h-9 rounded-full hover:bg-gray-100">✕</button>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-2 mb-4 bg-gray-100 p-1 rounded-lg w-fit">
            <button onclick="switchTab('requests')" id="tab-requests"
                class="user-modal-tab-btn px-4 py-1 rounded-md text-xs bg-white shadow">
                Requests (<span id="count-requests">0</span>)
            </button>

            <button onclick="switchTab('active')" id="tab-active"
                class="user-modal-tab-btn px-4 py-1 rounded-md text-xs text-gray-500">
                Active (<span id="count-active">0</span>)
            </button>

            <button onclick="switchTab('rejected')" id="tab-rejected"
                class="user-modal-tab-btn px-4 py-1 rounded-md text-xs text-gray-500">
                Rejected / Freeze / Inactive (<span id="count-rejected">0</span>)
            </button>
        </div>

        <!-- Content -->
            <div class="mb-3">
                <input
                    type="text"
                    id="userSearch"
                    placeholder="Search users (name, email, dept, role...)"
                    class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring"/>
            </div>
        <div class="max-h-[500px] overflow-y-auto border rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-gray-500 text-xs uppercase">
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Dept</th>
                        <th class="p-3 text-left">Designation</th>
                        <th class="p-3 text-left">Signature</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="activeTable"></tbody>
            </table>

            <div id="empty-active" class="hidden text-center py-6 text-gray-400">
                No users
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-4 flex justify-between text-xs text-gray-500">
            <span id="totalUsers"></span>
            <button onclick="closeUsersModal()" class="px-4 py-1 bg-gray-900 text-white rounded-lg">
                Close
            </button>
        </div>

    </div>
</div>

{{-- <script>
// Toast (cleaner UX)
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});

let USERS = {
    active: [],
    requests: [],
    rejected: []
};

let CURRENT_TAB = 'requests';

// Automatically fetch users on page load
document.addEventListener('DOMContentLoaded', () => {
    loadUsers(); // Initial fetch
    // setInterval(loadUsers, 30000); // Refresh every 30 seconds
});

// Open modal
function openUsersModal() {
    const modal = document.getElementById('usersModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

}

// Close modal
function closeUsersModal() {
    const modal = document.getElementById('usersModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Switch tabs
function switchTab(tab) {
    CURRENT_TAB = tab;

    // reset buttons
    document.querySelectorAll('.user-modal-tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'shadow');
        btn.classList.add('text-gray-500');
    });

    const activeBtn = document.getElementById('tab-' + tab);
    activeBtn.classList.add('bg-white', 'shadow');
    activeBtn.classList.remove('text-gray-500');

    // 🔥 apply search-aware rendering
    applySearch();
}


function applySearch() {
    const query = document.getElementById('userSearch').value.toLowerCase();

    let data = [];

    if (CURRENT_TAB === 'requests') {
        data = USERS.requests;
        renderRequests(filterUsers(data, query));
    }

    if (CURRENT_TAB === 'active') {
        data = USERS.active;
        renderActive(filterUsers(data, query));
    }

    if (CURRENT_TAB === 'rejected') {
        data = USERS.rejected;
        renderRejected(filterUsers(data, query));
    }
}

function filterUsers(users, query) {
    if (!query) return users;

    return users.filter(user => {
        return (
            user.name?.toLowerCase().includes(query) ||
            user.email?.toLowerCase().includes(query) ||
            user.role?.toLowerCase().includes(query) ||
            user.office?.name?.toLowerCase().includes(query)
        );
    });
}
document.getElementById('userSearch').addEventListener('input', function () {
    applySearch();
});

// Fetch users
async function loadUsers() {
    try {
        const res = await fetch('/users/fetch');
        const data = await res.json();

        // store data
        USERS.active = data.active || [];
        USERS.requests = data.requests || [];
        USERS.rejected = data.rejected || [];

        // counts
        document.getElementById('count-active').textContent = USERS.active.length;
        document.getElementById('count-requests').textContent = USERS.requests.length;
        document.getElementById('count-rejected').textContent = USERS.rejected.length;

        document.getElementById('totalUsers').textContent =
            `Total: ${USERS.active.length + USERS.requests.length + USERS.rejected.length} users`;

        // default tab
        switchTab('requests');

    } catch (error) {
        console.error(error);
        Toast.fire({ icon: 'error', title: 'Failed to load users' });
    }
}

// Avatar initials
function avatar(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
}

function getStatusColor(status) {

    switch (status) {

        case 'active':
            return 'bg-green-100 text-green-700';

        case 'freeze':
            return 'bg-yellow-100 text-yellow-700';

        case 'inactive':
            return 'bg-gray-100 text-gray-700';

        case 'rejected':
            return 'bg-red-100 text-red-700';

        default:
            return 'bg-gray-100 text-gray-700';
    }
}

// ACTIVE USERS
function renderActive(users) {
    const table = document.getElementById('activeTable');
    const empty = document.getElementById('empty-active');

    table.innerHTML = '';

    if (!users.length) {
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    const fragment = document.createDocumentFragment();

    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';

        tr.innerHTML = `
            <td class="p-3 flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xs font-bold">
                    ${avatar(user.name)}
                </div>
                <span>${user.name}</span>
            </td>

            <td class="p-3">${user.email}</td>
            <td class="p-3">${user.office?.name ?? 'N/A'}</td>

            <td class="p-3">
                ${
                    user.signature
                    ? `<img src="${user.signature}" loading="lazy"
                            onclick="openImagePreview('${user.signature}')"
                            class="w-9 h-9 rounded-full object-cover border cursor-pointer">`
                    : `<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                          ${avatar(user.name)}
                       </div>`
                }
            </td>

            <td class="p-3 text-gray-500">${user.role}</td>

            <td class="p-3 text-center">
                <select
                    data-original="${user.status}"
                    data-user-id="${user.id}"
                    class="status-dropdown px-2 py-1 rounded text-xs border ${getStatusColor(user.status)}">
                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                    <option value="freeze" ${user.status === 'freeze' ? 'selected' : ''}>Freeze</option>
                    <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                </select>
            </td>
        `;

        fragment.appendChild(tr);
    });

    table.appendChild(fragment);
}


function renderRejected(users) {
    const table = document.getElementById('activeTable');
    const empty = document.getElementById('empty-active');

    table.innerHTML = '';

    if (!users.length) {
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    const fragment = document.createDocumentFragment();

    users.forEach(user => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="p-3">${user.name}</td>
            <td class="p-3">${user.email}</td>
            <td class="p-3">${user.office?.name ?? 'N/A'}</td>

            <!-- IMAGE -->
            <td class="p-3">
                ${
                    user.signature
                    ? `<img src="${user.signature}"
                        onclick="openImagePreview('${user.signature}')"
                        class="w-9 h-9 rounded-full object-cover border cursor-pointer">`
                    : `<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                        ${avatar(user.name)}
                      </div>`
                }
            </td>

            <td class="p-3">${user.role}</td>

            <!-- ✅ ONLY "ACTIVE" OPTION -->
            <td class="p-3 text-center">
                <select
                    data-user-id="${user.id}"
                    data-original="${user.status}"
                    class="rejected-status px-2 py-1 text-xs border rounded">

                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>
                        Active
                    </option>

                    <option value="freeze" ${user.status === 'freeze' ? 'selected' : ''}>
                        Freeze
                    </option>

                    <option value="rejected" ${user.status === 'rejected' ? 'selected' : ''}>
                        Rejected
                    </option>

                </select>
            </td>
        `;

        fragment.appendChild(tr);
    });

    table.appendChild(fragment);
}

async function updateStatus(selectEl) {

    const userId = selectEl.dataset.userId;
    const newStatus = selectEl.value;
    const original = selectEl.dataset.original;

    // live color update
    selectEl.className =
        `${selectEl.classList[0]} px-2 py-1 rounded text-xs border ${getStatusColor(newStatus)}`;

    try {

        const res = await fetch(`/users/${userId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: newStatus
            })
        });

        const data = await res.json();

        if (!data.success) {
            throw new Error(data.message || 'Update failed');
        }

        selectEl.dataset.original = data.status;

        Toast.fire({
            icon: 'success',
            title: `User updated to ${data.status}`
        });

        // refresh users + stay on current tab
        await loadUsers();

        switchTab(CURRENT_TAB);

    } catch (err) {

        console.error(err);

        selectEl.value = original;

        Toast.fire({
            icon: 'error',
            title: 'Failed to update status'
        });
    }
}

function renderRequests(users) {
    const table = document.getElementById('activeTable');
    const empty = document.getElementById('empty-active');

    table.innerHTML = '';

    if (!users.length) {
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    const fragment = document.createDocumentFragment();

    users.forEach(user => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="p-3">${user.name}</td>
            <td class="p-3">${user.email}</td>
            <td class="p-3">${user.office?.name ?? 'N/A'}</td>

            <!-- IMAGE -->
            <td class="p-3">
                ${
                    user.signature
                    ? `<img src="${user.signature}"
                        onclick="openImagePreview('${user.signature}')"
                        class="w-9 h-9 rounded-full object-cover border cursor-pointer">`
                    : `<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                        ${avatar(user.name)}
                      </div>`
                }
            </td>

            <td class="p-3">${user.role}</td>

            <!-- ✅ STATUS SELECT (NEW) -->
            <td class="p-3 text-center">
                <select
                    data-user-id="${user.id}"
                    data-original="${user.status ?? 'inactive'}"
                    class="request-status px-2 py-1 text-xs border rounded">
                    <option disabled selected value="">Select Status</option>
                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                    <option value="freeze" ${user.status === 'freeze' ? 'selected' : ''}>Freeze</option>
                </select>
            </td>
        `;

        fragment.appendChild(tr);
    });

    table.appendChild(fragment);
}

// document.getElementById('activeTable').addEventListener('change', async function (e) {
//     if (e.target.classList.contains('status-dropdown')) {

//         const userId = e.target.dataset.userId;
//         const status = e.target.value;

//         const select = e.target;

//         try {
//             const res = await fetch(`/users/${userId}/status`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'Accept': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//                 },
//                 body: JSON.stringify({ status })
//             });

//             const data = await res.json();

//             if (!data.success) throw new Error();

//             Toast.fire({
//                 icon: 'success',
//                 title: `User set to ${status}`
//             });

//             // OPTIONAL UX: refresh list after update
//             loadUsers();

//         } catch (err) {
//             Toast.fire({
//                 icon: 'error',
//                 title: 'Failed to update status'
//             });

//             // revert selection on error
//             select.value = select.dataset.original || 'inactive';
//         }
//     }
// });

document.getElementById('activeTable').addEventListener('change', async function (e) {

    if (
        e.target.classList.contains('status-dropdown') ||
        e.target.classList.contains('request-status') ||
        e.target.classList.contains('rejected-status')
    ) {

        await updateStatus(e.target);
    }
});

// APPROVE
async function approveUser(id, btn) {
    const result = await Swal.fire({
        title: 'Approve user?',
        text: "This user will become active.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        confirmButtonText: 'Yes, approve'
    });

    if (!result.isConfirmed) return;

    btn.disabled = true;
    btn.innerText = '...';

    try {
        await safeFetch(`/users/approve/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        Toast.fire({ icon: 'success', title: 'User approved' });
        loadUsers();

    } catch {
        Swal.fire('Error', 'Something went wrong.', 'error');
    }
}

</script> --}}


<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});

let USERS = {
    active: [],
    requests: [],
    rejected: []
};

let CURRENT_TAB = 'requests';

// INIT
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
});

// OPEN / CLOSE MODAL
function openUsersModal() {
    const modal = document.getElementById('usersModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUsersModal() {
    const modal = document.getElementById('usersModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// TAB SWITCH
function switchTab(tab) {
    CURRENT_TAB = tab;

    document.querySelectorAll('.user-modal-tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'shadow');
        btn.classList.add('text-gray-500');
    });

    const activeBtn = document.getElementById('tab-' + tab);
    if (activeBtn) {
        activeBtn.classList.add('bg-white', 'shadow');
        activeBtn.classList.remove('text-gray-500');
    }

    applySearch();
}

// SEARCH
document.getElementById('userSearch').addEventListener('input', applySearch);

function applySearch() {
    const query = document.getElementById('userSearch').value.toLowerCase();

    let data = [];

    if (CURRENT_TAB === 'requests') {
        data = USERS.requests;
        renderRequests(filterUsers(data, query));
    }

    if (CURRENT_TAB === 'active') {
        data = USERS.active;
        renderActive(filterUsers(data, query));
    }

    if (CURRENT_TAB === 'rejected') {
        data = USERS.rejected;
        renderRejected(filterUsers(data, query));
    }
}

function filterUsers(users, query) {
    if (!query) return users;

    return users.filter(u =>
        u.name?.toLowerCase().includes(query) ||
        u.email?.toLowerCase().includes(query) ||
        u.role?.toLowerCase().includes(query) ||
        u.designation?.toLowerCase().includes(query) ||
        u.office?.name?.toLowerCase().includes(query)
    );
}

// LOAD USERS
async function loadUsers() {
    try {
        const res = await fetch('/users/fetch');
        const data = await res.json();

        USERS.active = data.active || [];
        USERS.requests = data.requests || [];
        USERS.rejected = data.rejected || [];

        document.getElementById('count-active').textContent = USERS.active.length;
        document.getElementById('count-requests').textContent = USERS.requests.length;
        document.getElementById('count-rejected').textContent = USERS.rejected.length;

        document.getElementById('totalUsers').textContent =
            `Total: ${USERS.active.length + USERS.requests.length + USERS.rejected.length} users`;

        switchTab(CURRENT_TAB);

    } catch (err) {
        console.error(err);
        Toast.fire({ icon: 'error', title: 'Failed to load users' });
    }
}

// AVATAR
function avatar(name = '') {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
}

// STATUS COLOR
function getStatusColor(status) {
    switch (status) {
        case 'active': return 'bg-green-100 text-green-700';
        case 'freeze': return 'bg-yellow-100 text-yellow-700';
        case 'inactive': return 'bg-gray-100 text-gray-700';
        case 'rejected': return 'bg-red-100 text-red-700';
        default: return 'bg-gray-100 text-gray-700';
    }
}

// UPDATE STATUS
async function updateStatus(selectEl) {
    const userId = selectEl.dataset.userId;
    const newStatus = selectEl.value;
    const original = selectEl.dataset.original;

    try {
        const res = await fetch(`/users/${userId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatus })
        });

        const data = await res.json();

        if (!data.success) throw new Error();

        selectEl.dataset.original = data.status;

        Toast.fire({
            icon: 'success',
            title: `User updated to ${data.status}`
        });

        await loadUsers();

    } catch (err) {
        console.error(err);
        selectEl.value = original;

        Toast.fire({
            icon: 'error',
            title: 'Failed to update status'
        });
    }
}

// EVENT DELEGATION
document.getElementById('activeTable').addEventListener('change', async (e) => {
    if (
        e.target.classList.contains('status-dropdown') ||
        e.target.classList.contains('request-status') ||
        e.target.classList.contains('rejected-status')
    ) {
        await updateStatus(e.target);
    }
});

// RENDER ACTIVE
function renderActive(users) {
    const table = document.getElementById('activeTable');
    const empty = document.getElementById('empty-active');

    table.innerHTML = '';

    if (!users.length) {
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    const fragment = document.createDocumentFragment();

    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';

        tr.innerHTML = `
            <td class="p-3 flex items-center space-x-2">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-600">
                    ${avatar(user.name)}
                </div>
                <span>${user.name}</span>
            </td>

            <td class="p-3">${user.email}</td>
            <td class="p-3">${user.office?.name ?? 'N/A'}</td>
            <td class="p-3">${user.designation}</td>

            <td class="p-3">
                ${
                    user.signature
                    ? `<img src="/signature/${user.id}"
                        onclick="openImagePreview('/signature/${user.id}')"
                        class="w-9 h-9 rounded-full object-cover border cursor-pointer">`
                    : `<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                          ${avatar(user.name)}
                       </div>`
                }
            </td>

            <td class="p-3 text-gray-500">${user.role}</td>

            <td class="p-3 text-center">
                <select
                    data-original="${user.status}"
                    data-user-id="${user.id}"
                    class="status-dropdown px-2 py-1 rounded text-xs border ${getStatusColor(user.status)}">
                    <option selected disabled value="">Select status</option>
                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                    <option value="freeze" ${user.status === 'freeze' ? 'selected' : ''}>Freeze</option>
                    <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                </select>
            </td>
        `;

        fragment.appendChild(tr);
    });

    table.appendChild(fragment);
}

// RENDER REQUESTS
function renderRequests(users) {
    const table = document.getElementById('activeTable');
    table.innerHTML = '';

    const fragment = document.createDocumentFragment();

    users.forEach(user => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="p-3">${user.name}</td>
            <td class="p-3">${user.email}</td>
            <td class="p-3">${user.office?.name ?? 'N/A'}</td>
            <td class="p-3">${user.designation}</td>

            <td class="p-3">
                ${
                    user.signature
                    ? `<img src="/signature/${user.id}"
                        onclick="openImagePreview('/signature/${user.id}')"
                        class="w-9 h-9 rounded-full object-cover border cursor-pointer">`
                    : `<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                        ${avatar(user.name)}
                      </div>`
                }
            </td>

            <td class="p-3">${user.role}</td>

            <td class="p-3 text-center">
                <select
                    data-user-id="${user.id}"
                    class="request-status px-2 py-1 text-xs border rounded">
                    <option selected disabled value="">Select status</option>
                    <option value="active">Active</option>
                    <option value="freeze">Freeze</option>
                </select>
            </td>
        `;

        fragment.appendChild(tr);
    });

    table.appendChild(fragment);
}

// RENDER REJECTED
function renderRejected(users) {
    const table = document.getElementById('activeTable');
    table.innerHTML = '';

    const fragment = document.createDocumentFragment();

    users.forEach(user => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="p-3">${user.name}</td>
            <td class="p-3">${user.email}</td>
            <td class="p-3">${user.office?.name ?? 'N/A'}</td>
            <td class="p-3">${user.designation}</td>

            <td class="p-3">
                ${
                    user.signature
                    ? `<img src="/signature/${user.id}"
                        onclick="openImagePreview('/signature/${user.id}')"
                        class="w-9 h-9 rounded-full object-cover border cursor-pointer">`
                    : `<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                        ${avatar(user.name)}
                      </div>`
                }
            </td>

            <td class="p-3">${user.role}</td>

            <td class="p-3 text-center">
                <select
                    data-user-id="${user.id}"
                    class="rejected-status px-2 py-1 text-xs border rounded">
                    <option selected disabled value="">Select status</option>
                    <option value="active">Active</option>
                    <option value="freeze">Freeze</option>
                    <option value="rejected">Rejected</option>
                </select>
            </td>
        `;

        fragment.appendChild(tr);
    });

    table.appendChild(fragment);
}
</script>


<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center opacity-0 pointer-events-none transition duration-300 z-[99999]">

    <!-- Close Button -->
    <button onclick="closeImagePreview()" class="absolute top-5 right-5 text-white text-3xl">&times;</button>

    <!-- Image -->
    <img id="previewImage" src=""
         class="max-w-[90%] max-h-[90%] rounded-lg shadow-2xl transform scale-95 transition duration-300">
</div>
<script>
function openImagePreview(src) {
    const modal = document.getElementById("imagePreviewModal");
    const img = document.getElementById("previewImage");

    img.src = src;
    modal.classList.remove("opacity-0", "pointer-events-none");

    // animate in
    setTimeout(() => {
        img.classList.remove("scale-95");
    }, 10);
}

function closeImagePreview() {
    const modal = document.getElementById("imagePreviewModal");
    const img = document.getElementById("previewImage");

    img.classList.add("scale-95");
    modal.classList.add("opacity-0", "pointer-events-none");
}

/* ✅ Close when clicking outside (background only) */
document.getElementById("imagePreviewModal").addEventListener("click", function (e) {
    if (e.target === this) {
        closeImagePreview();
    }
});

/* ✅ Prevent closing when clicking the image */
document.getElementById("previewImage").addEventListener("click", function (e) {
    e.stopPropagation();
});

/* ✅ Close on ESC key */
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeImagePreview();
    }
});
</script>
