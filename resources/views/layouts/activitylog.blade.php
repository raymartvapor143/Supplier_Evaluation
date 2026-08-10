<!-- Activity Logs / Audit Log Modal -->
<div id="activityLogsModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">

    <div id="activityLogsContent"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl
                transform scale-95 opacity-0 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-5 shrink-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                        <i class="ri-history-line"></i>
                        Audit Logs & Activity Monitoring
                    </h2>
                    <p class="text-orange-100 text-sm mt-1">
                        Comprehensive record of user actions, login events, and system activities
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="exportAuditLogs()"
                            title="Export Filtered Logs to CSV"
                            class="px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white font-medium text-sm transition flex items-center gap-2">
                        <i class="ri-download-2-line text-lg"></i>
                        <span>Export CSV</span>
                    </button>

                    <button onclick="closeActivityLogs()"
                            class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 text-white transition flex items-center justify-center">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-5 bg-gray-50 border-b shrink-0 flex flex-wrap items-center justify-between gap-3 text-sm">

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="font-medium text-gray-600">Role:</label>
                    <select id="roleFilter" onchange="resetAndLoadLogs()" class="px-3 py-2 rounded-xl border bg-white text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                        <option value="all">All Roles</option>
                        <option value="administrator">Administrator</option>
                        <option value="head">Head</option>
                        <option value="end_user">End-user</option>
                        <option value="presentative_staff">Representative Staff</option>
                        <option value="pgso">PGSO</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="font-medium text-gray-600">Status:</label>
                    <select id="statusFilter" onchange="resetAndLoadLogs()" class="px-3 py-2 rounded-xl border bg-white text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                        <option value="all">All Statuses</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                        <option value="warning">Warning</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="font-medium text-gray-600">From:</label>
                    <input id="fromDateFilter" type="date" onchange="resetAndLoadLogs()" class="px-3 py-1.5 rounded-xl border bg-white text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                </div>

                <div class="flex items-center gap-2">
                    <label class="font-medium text-gray-600">To:</label>
                    <input id="toDateFilter" type="date" onchange="resetAndLoadLogs()" class="px-3 py-1.5 rounded-xl border bg-white text-sm focus:ring-2 focus:ring-orange-500 outline-none">
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input id="searchInput"
                       onkeyup="handleSearchInput()"
                       type="text"
                       placeholder="Search user, activity, IP..."
                       class="px-4 py-2 rounded-xl border bg-white text-sm w-full sm:w-64 focus:ring-2 focus:ring-orange-500 outline-none">
                <button onclick="clearAuditFilters()" class="px-3 py-2 text-xs font-medium text-gray-500 hover:text-orange-600 border rounded-xl hover:bg-gray-100 transition">
                    Clear
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-auto flex-1 min-h-[320px]">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs sticky top-0 border-b">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Activity</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>

                <tbody id="logsTableBody" class="divide-y">
                </tbody>
            </table>

            <div id="loadingLogs" class="hidden p-12 text-center text-gray-500">
                <i class="ri-loader-4-line animate-spin text-3xl text-orange-500"></i>
                <p class="mt-2 text-sm">Fetching audit logs...</p>
            </div>

            <div id="emptyLogs" class="hidden p-12 text-center">
                <i class="ri-file-list-3-line text-5xl text-gray-300"></i>
                <p class="mt-3 text-gray-500 font-medium">No matching audit logs found.</p>
            </div>
        </div>

        <!-- Footer & Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t flex flex-wrap items-center justify-between gap-3 shrink-0">
            <div id="logsPagination" class="flex items-center gap-3">
                <button id="prevPage"
                    class="px-4 py-2 rounded-xl border bg-white hover:bg-gray-100 text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>

                <span id="pageInfo" class="text-sm font-medium text-gray-600">Page 1 of 1</span>

                <button id="nextPage"
                    class="px-4 py-2 rounded-xl border bg-white hover:bg-gray-100 text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>

            <button onclick="closeActivityLogs()"
                    class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-medium text-sm transition shadow-sm">
                Close
            </button>
        </div>

    </div>
</div>

<script>
let auditLogCurrentPage = 1;
let auditLogLastPage = 1;
let auditLogSearchTimeout = null;

window.openActivityLogs = function () {
    const modal = document.getElementById('activityLogsModal');
    const content = document.getElementById('activityLogsContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);

    auditLogCurrentPage = 1;
    loadLogs();
};

window.closeActivityLogs = function () {
    const modal = document.getElementById('activityLogsModal');
    const content = document.getElementById('activityLogsContent');

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
};

document.getElementById('activityLogsModal').addEventListener('click', function(e){
    if(e.target === this){
        closeActivityLogs();
    }
});

function handleSearchInput() {
    clearTimeout(auditLogSearchTimeout);
    auditLogSearchTimeout = setTimeout(() => {
        resetAndLoadLogs();
    }, 300);
}

function resetAndLoadLogs() {
    auditLogCurrentPage = 1;
    loadLogs();
}

function clearAuditFilters() {
    document.getElementById('roleFilter').value = 'all';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('fromDateFilter').value = '';
    document.getElementById('toDateFilter').value = '';
    document.getElementById('searchInput').value = '';
    resetAndLoadLogs();
}

document.getElementById('prevPage').onclick = () => {
    if(auditLogCurrentPage > 1){
        auditLogCurrentPage--;
        loadLogs();
    }
};

document.getElementById('nextPage').onclick = () => {
    if(auditLogCurrentPage < auditLogLastPage){
        auditLogCurrentPage++;
        loadLogs();
    }
};

function getAuditQueryParams() {
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    const fromDate = document.getElementById('fromDateFilter').value;
    const toDate = document.getElementById('toDateFilter').value;
    const search = document.getElementById('searchInput').value;

    const params = new URLSearchParams();
    params.append('page', auditLogCurrentPage);
    if (role && role !== 'all') params.append('role', role);
    if (status && status !== 'all') params.append('status', status);
    if (fromDate) params.append('from_date', fromDate);
    if (toDate) params.append('to_date', toDate);
    if (search) params.append('search', search);

    return params.toString();
}

function exportAuditLogs() {
    const queryParams = getAuditQueryParams();
    window.location.href = `/admin/activity-logs/export?${queryParams}`;
}

async function loadLogs(){
    const tbody = document.getElementById('logsTableBody');
    const loading = document.getElementById('loadingLogs');
    const empty = document.getElementById('emptyLogs');

    tbody.innerHTML = '';
    empty.classList.add('hidden');
    loading.classList.remove('hidden');

    try {
        const queryParams = getAuditQueryParams();
        const response = await fetch(`/admin/activity-logs?${queryParams}`);
        if (!response.ok) throw new Error('HTTP error ' + response.status);

        const result = await response.json();
        loading.classList.add('hidden');

        auditLogLastPage = result.last_page || 1;
        auditLogCurrentPage = result.current_page || 1;

        document.getElementById('pageInfo').innerHTML =
            `Page ${result.current_page || 1} of ${result.last_page || 1}`;

        document.getElementById('prevPage').disabled = (auditLogCurrentPage === 1);
        document.getElementById('nextPage').disabled = (auditLogCurrentPage >= auditLogLastPage);

        if (!result.data || result.data.length === 0) {
            empty.classList.remove('hidden');
            return;
        }

        result.data.forEach(log => {
            let roleColor = 'bg-gray-100 text-gray-700';

            switch(log.role){
                case 'administrator':
                    roleColor = 'bg-red-100 text-red-700 border border-red-200';
                    break;
                case 'head':
                    roleColor = 'bg-purple-100 text-purple-700 border border-purple-200';
                    break;
                case 'presentative_staff':
                    roleColor = 'bg-blue-100 text-blue-700 border border-blue-200';
                    break;
                case 'end_user':
                    roleColor = 'bg-orange-100 text-orange-700 border border-orange-200';
                    break;
                case 'pgso':
                    roleColor = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
                    break;
            }

            let statusBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Success</span>';

            if (log.status === 'failed') {
                statusBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Failed</span>';
            } else if (log.status === 'warning') {
                statusBadge = '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">Warning</span>';
            }

            const formattedTime = log.created_at ? new Date(log.created_at).toLocaleString() : '-';

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50/80 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        ${log.user ? log.user.name : (log.role === 'system/guest' ? 'Guest/System' : 'Unknown')}
                        ${log.user && log.user.email ? `<div class="text-xs text-gray-400 font-normal">${log.user.email}</div>` : ''}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-medium capitalize ${roleColor}">
                            ${(log.role || 'guest').replaceAll('_', ' ')}
                        </span>
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        ${log.activity}
                    </td>

                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="${log.description ?? ''}">
                        ${log.description ?? '-'}
                    </td>

                    <td class="px-6 py-4 text-xs font-mono text-gray-500">
                        ${log.ip_address ?? 'N/A'}
                    </td>

                    <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                        ${formattedTime}
                    </td>

                    <td class="px-6 py-4">
                        ${statusBadge}
                    </td>
                </tr>
            `;
        });

    } catch(e) {
        loading.classList.add('hidden');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-10 text-red-500 font-medium">
                    <i class="ri-error-warning-line text-2xl block mb-1"></i>
                    Failed to load audit logs. Please try again.
                </td>
            </tr>
        `;
        console.error(e);
    }
}
</script>
