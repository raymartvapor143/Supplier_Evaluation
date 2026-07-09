<!-- Activity Logs Modal -->
<div id="activityLogsModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">

    <div id="activityLogsContent"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl
                transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                        <i class="ri-history-line"></i>
                        Activity Logs
                    </h2>
                    <p class="text-orange-100 text-sm mt-1">
                        Monitor system activities and user actions
                    </p>
                </div>

                <button onclick="closeActivityLogs()"
                        class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 text-white transition">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-5 bg-gray-50 border-b flex flex-wrap items-center justify-between gap-3">

            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-600">Filter by Role:</label>

                <select id="roleFilter" onchange="loadLogs()" class="px-4 py-2 rounded-xl border bg-white text-sm">
                    <option value="all">All</option>
                    <option value="end_user">End-user</option>
                    <option value="head">Head</option>
                    <option value="administrator">Administrator</option>
                    <option value="presentative_staff">Representative Staff</option>
                </select>
            </div>

                <input id="searchInput"
                       onkeyup="loadLogs()"
                       type="text"
                       placeholder="Search logs..."
                       class="px-4 py-2 rounded-xl border bg-white text-sm w-full sm:w-72">
        </div>

        <!-- Table -->
        <div class="overflow-auto max-h-[520px]">

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs sticky top-0">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Activity</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>

                <tbody id="logsTableBody" class="divide-y">
                                    <div id="loadingLogs" class="hidden p-8 text-center text-gray-500">
                    <i class="ri-loader-4-line animate-spin text-2xl"></i>
                    <p class="mt-2">Loading activity logs...</p>
                </div>

                <div id="emptyLogs" class="hidden p-10 text-center">
                    <i class="ri-file-list-3-line text-5xl text-gray-300"></i>
                    <p class="mt-3 text-gray-500">No activity logs found.</p>
                </div>

                <div id="logsPagination" class="flex justify-between items-center px-6 py-4 border-t bg-gray-50">
                    <button id="prevPage"
                        class="px-4 py-2 rounded-lg border hover:bg-gray-100 disabled:opacity-50">
                        Previous
                    </button>

                    <span id="pageInfo" class="text-sm text-gray-500"></span>

                    <button id="nextPage"
                        class="px-4 py-2 rounded-lg border hover:bg-gray-100 disabled:opacity-50">
                        Next
                    </button>
                </div>
                </tbody>

            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
            <button onclick="closeActivityLogs()"
                    class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-medium transition">
                Close
            </button>
        </div>

    </div>
</div>



<script>
let lastPage = 1;


window.openActivityLogs = function () {
    const modal = document.getElementById('activityLogsModal');
    const content = document.getElementById('activityLogsContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);

    currentPage = 1;
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

document.getElementById('searchInput').addEventListener('keyup', function () {

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        currentPage = 1;
        loadLogs();
    },300);

});

document.getElementById('roleFilter').addEventListener('change', function () {
    currentPage = 1;
    loadLogs();
});

document.getElementById('prevPage').onclick = () => {
    if(currentPage > 1){
        currentPage--;
        loadLogs();
    }
};

document.getElementById('nextPage').onclick = () => {
    if(currentPage < lastPage){
        currentPage++;
        loadLogs();
    }
};

async function loadLogs(){

    const role = document.getElementById('roleFilter').value;
    const search = document.getElementById('searchInput').value;

    const tbody = document.getElementById('logsTableBody');
    const loading = document.getElementById('loadingLogs');
    const empty = document.getElementById('emptyLogs');

    tbody.innerHTML='';
    empty.classList.add('hidden');
    loading.classList.remove('hidden');

    try{

        const response = await fetch(`/admin/activity-logs?page=${currentPage}&role=${role}&search=${search}`);

        const result = await response.json();

        loading.classList.add('hidden');

        lastPage = result.last_page;

        document.getElementById('pageInfo').innerHTML =
            `Page ${result.current_page} of ${result.last_page}`;

        document.getElementById('prevPage').disabled =
            result.current_page===1;

        document.getElementById('nextPage').disabled =
            result.current_page===result.last_page;

        if(result.data.length===0){
            empty.classList.remove('hidden');
            return;
        }

        result.data.forEach(log=>{

            let roleColor='bg-gray-100 text-gray-700';

            switch(log.role){

                case 'administrator':
                    roleColor='bg-red-100 text-red-700';
                    break;

                case 'head':
                    roleColor='bg-purple-100 text-purple-700';
                    break;

                case 'presentative_staff':
                    roleColor='bg-blue-100 text-blue-700';
                    break;

                case 'end_user':
                    roleColor='bg-orange-100 text-orange-700';
                    break;
            }

            let statusColor='bg-yellow-100 text-yellow-700';

            if(log.status==='success')
                statusColor='bg-green-100 text-green-700';

            if(log.status==='failed')
                statusColor='bg-red-100 text-red-700';

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium">
                        ${log.user ? log.user.name : 'Unknown'}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs ${roleColor}">
                            ${log.role.replaceAll('_',' ')}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        ${log.activity}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        ${log.description ?? '-'}
                    </td>

                    <td class="px-6 py-4 text-gray-500">
                        ${new Date(log.created_at).toLocaleString()}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs ${statusColor}">
                            ${log.status}
                        </span>
                    </td>

                </tr>
            `;
        });

    }catch(e){

        loading.classList.add('hidden');

        tbody.innerHTML=`
            <tr>
                <td colspan="6" class="text-center py-10 text-red-500">
                    Failed to load activity logs.
                </td>
            </tr>
        `;

        console.error(e);
    }
}
</script>
