<div id="authorizeUsersModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-7xl p-5">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Authorization Management
                </h2>
                <p class="text-xs text-gray-500">
                    Manage authorized users and approval requests
                </p>
            </div>

            <button onclick="closeAuthorizeUsersModal()"
                    class="w-9 h-9 rounded-full hover:bg-gray-100">
                ✕
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-2 mb-4 bg-gray-100 p-1 rounded-lg w-fit">

            <button
                onclick="switchAuthorizeUsersTab('pending')"
                id="authorizeUsersTabPending"
                class="authorize-users-tab-btn px-4 py-1 rounded-md text-xs bg-white shadow">

                Pending
                (<span id="authorizeUsersCountPending">0</span>)
            </button>

            <button
                onclick="switchAuthorizeUsersTab('active')"
                id="authorizeUsersTabActive"
                class="authorize-users-tab-btn px-4 py-1 rounded-md text-xs text-gray-500">

                Active
                (<span id="authorizeUsersCountActive">0</span>)
            </button>

            <button
                onclick="switchAuthorizeUsersTab('rejected')"
                id="authorizeUsersTabRejected"
                class="authorize-users-tab-btn px-4 py-1 rounded-md text-xs text-gray-500">

                Rejected / Freeze
                (<span id="authorizeUsersCountRejected">0</span>)
            </button>

        </div>

        <!-- Search -->
        <div class="mb-3">
            <input
                type="text"
                id="authorizeUsersSearch"
                placeholder="Search user, office, role..."
                class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring">
        </div>

        <!-- Table -->
        <div class="max-h-[500px] overflow-y-auto border rounded-lg">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 sticky top-0">
                    <tr class="text-gray-500 text-xs uppercase">

                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Office</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Authorization Letter</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Action</th>

                    </tr>
                </thead>

                <tbody id="authorizeUsersTableBody"></tbody>

            </table>

            <div id="authorizeUsersEmpty"
                 class="hidden text-center py-6 text-gray-400">
                No users found
            </div>

        </div>

        <!-- Footer -->
        <div class="mt-4 flex justify-between text-xs text-gray-500">

            <span id="authorizeUsersTotal">
                Total: 0 users
            </span>

            <button
                onclick="closeAuthorizeUsersModal()"
                class="px-4 py-1 bg-gray-900 text-white rounded-lg">
                Close
            </button>

        </div>

    </div>

</div>

<script>

let AUTHORIZE_USERS_DATA = {
    pending: [],
    active: [],
    rejected: []
};

let CURRENT_AUTHORIZE_USERS_TAB = 'pending';

document.addEventListener('DOMContentLoaded', () => {

    const searchInput =
        document.getElementById('authorizeUsersSearch');

    if(searchInput)
    {
        searchInput.addEventListener('input', () => {
            renderAuthorizeUsersTable();
        });
    }
});

/* =========================
   OPEN / CLOSE
========================= */

function openAuthorizeUsersModal()
{
    const modal =
        document.getElementById('authorizeUsersModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    loadAuthorizeUsersData();
}

function closeAuthorizeUsersModal()
{
    const modal =
        document.getElementById('authorizeUsersModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

/* =========================
   LOAD USERS
========================= */

async function loadAuthorizeUsersData()
{
    try {

        const response = await fetch(
            '/authorization-users/fetch'
        );

        const data = await response.json();

        AUTHORIZE_USERS_DATA.pending =
            data.pending || [];

        AUTHORIZE_USERS_DATA.active =
            data.active || [];

        AUTHORIZE_USERS_DATA.rejected =
            data.rejected || [];

        document.getElementById(
            'authorizeUsersCountPending'
        ).textContent =
            AUTHORIZE_USERS_DATA.pending.length;

        document.getElementById(
            'authorizeUsersCountActive'
        ).textContent =
            AUTHORIZE_USERS_DATA.active.length;

        document.getElementById(
            'authorizeUsersCountRejected'
        ).textContent =
            AUTHORIZE_USERS_DATA.rejected.length;

        document.getElementById(
            'authorizeUsersTotal'
        ).textContent =
            `Total: ${
                AUTHORIZE_USERS_DATA.pending.length +
                AUTHORIZE_USERS_DATA.active.length +
                AUTHORIZE_USERS_DATA.rejected.length
            } users`;

        switchAuthorizeUsersTab(
            CURRENT_AUTHORIZE_USERS_TAB
        );

    } catch(error) {

        console.error(error);

        Swal.fire(
            'Error',
            'Failed to load authorization users.',
            'error'
        );
    }
}

/* =========================
   TABS
========================= */

function switchAuthorizeUsersTab(tab)
{
    CURRENT_AUTHORIZE_USERS_TAB = tab;

    document
        .querySelectorAll('.authorize-users-tab-btn')
        .forEach(btn => {

            btn.classList.remove(
                'bg-white',
                'shadow'
            );

            btn.classList.add(
                'text-gray-500'
            );
        });

    const activeBtn =
        document.getElementById(
            'authorizeUsersTab' +
            tab.charAt(0).toUpperCase() +
            tab.slice(1)
        );

    if(activeBtn)
    {
        activeBtn.classList.add(
            'bg-white',
            'shadow'
        );

        activeBtn.classList.remove(
            'text-gray-500'
        );
    }

    renderAuthorizeUsersTable();
}

/* =========================
   STATUS BADGE
========================= */

function getAuthorizeUserStatusBadge(status)
{
    switch(status)
    {
        case 'active':
            return `
                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                    Active
                </span>
            `;

        case 'inactive':
            return `
                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                    Pending
                </span>
            `;

        case 'rejected':
        case 'freeze':
            return `
                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">
                    ${status}
                </span>
            `;

        default:
            return `
                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                    ${status}
                </span>
            `;
    }
}

/* =========================
   ACTIONS
========================= */

function getAuthorizeUserActions(user)
{
    if(user.status === 'inactive')
    {
        return `
            <div class="flex justify-center gap-2">

                <button
                    onclick="updateAuthorizeUserStatus(${user.id}, 'active')"
                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">
                    Approve
                </button>

                <button
                    onclick="updateAuthorizeUserStatus(${user.id}, 'rejected')"
                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs">
                    Reject
                </button>

            </div>
        `;
    }

    if(user.status === 'active')
    {
        return `
            <button
                onclick="updateAuthorizeUserStatus(${user.id}, 'freeze')"
                class="px-3 py-1 bg-orange-500 hover:bg-orange-600 text-white rounded text-xs">
                Freeze
            </button>
        `;
    }

    return `
        <button
            onclick="updateAuthorizeUserStatus(${user.id}, 'active')"
            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">
            Activate
        </button>
    `;
}

/* =========================
   SEARCH + RENDER
========================= */

function renderAuthorizeUsersTable()
{
    const tbody =
        document.getElementById(
            'authorizeUsersTableBody'
        );

    const empty =
        document.getElementById(
            'authorizeUsersEmpty'
        );

    const search =
        document.getElementById(
            'authorizeUsersSearch'
        )
        ?.value
        ?.toLowerCase() || '';

    let users =
        AUTHORIZE_USERS_DATA[
            CURRENT_AUTHORIZE_USERS_TAB
        ] || [];

    users = users.filter(user => {

        return (
            user.name?.toLowerCase().includes(search) ||
            user.role?.toLowerCase().includes(search) ||
            user.office?.name?.toLowerCase().includes(search)
        );
    });

    tbody.innerHTML = '';

    if(!users.length)
    {
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    users.forEach(user => {

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">

                <td class="p-3 font-medium text-gray-800">
                    ${user.name}
                </td>

                <td class="p-3">
                    ${user.office?.name ?? 'N/A'}
                </td>

                <td class="p-3">
                    ${user.role}
                </td>

<td class="p-3">
    ${
        user.authorization_letter
        ? `
            <a href="/authorization-letter/${user.id}"
               target="_blank"
               class="text-blue-600 hover:underline text-xs font-medium">
               View PDF
            </a>
        `
        : `<span class="text-gray-400 text-xs">No file</span>`
    }
</td>

                <td class="p-3">
                    ${getAuthorizeUserStatusBadge(
                        user.status
                    )}
                </td>

                <td class="p-3 text-center">
                    ${getAuthorizeUserActions(
                        user
                    )}
                </td>

            </tr>
        `;
    });
}

/* =========================
   UPDATE STATUS
========================= */

async function updateAuthorizeUserStatus(
    userId,
    status
)
{
    const result = await Swal.fire({
        title: 'Confirm Action',
        text: `Change status to ${status}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm'
    });

    if(!result.isConfirmed)
    {
        return;
    }

    try {

        const response = await fetch(
            `/authorization-users/${userId}/status`,
            {
                method: 'POST',
                headers: {
                    'Content-Type':
                        'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                },
                body: JSON.stringify({
                    status
                })
            }
        );

        const data =
            await response.json();

        if(!data.success)
        {
            throw new Error();
        }

        Swal.fire({
            icon: 'success',
            title: 'Updated',
            timer: 1500,
            showConfirmButton: false
        });

        loadAuthorizeUsersData();

    } catch(error) {

        Swal.fire(
            'Error',
            'Failed to update user.',
            'error'
        );
    }
}

</script>
