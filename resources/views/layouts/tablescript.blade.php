


<script>
    window.canViewAll = @json(auth()->user()->isAdmin() || auth()->user()->isPgso());
</script>


<script id="ajax-evaluations-realtime">
document.addEventListener('DOMContentLoaded', function () {
    let rowCache = {
    pending: new Set(),
    'head review': new Set(),
    submitted: new Set()
};

    const searchInput = document.getElementById('eva-search');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const clearButton = document.getElementById('clearFilters');

    const tbodyCache = {
        pending: document.querySelector('#pendingTable tbody'),
        'head review': document.querySelector('#reviewTable tbody'),
        submitted: document.querySelector('#approvedTable tbody')
    };

    const tables = {
        'pending': document.querySelector('#pendingTable tbody'),
        'head review': document.querySelector('#reviewTable tbody'),
        'submitted': document.querySelector('#approvedTable tbody')
    };

    const paginationContainers = {
        'pending': document.getElementById('pendingPagination'),
        'head review': document.getElementById('headPagination'),
        'submitted': document.getElementById('approvedPagination')
    };

    const pagination = {
        'pending': { page: 1, perPage: 10 },
        'head review': { page: 1, perPage: 10 },
        'submitted': { page: 1, perPage: 10 },
    };

    let allEvaluations = {
        'pending': [],
        'head review': [],
        'submitted': []
    };

    let isUpdating = false;
    let modalOpen = false;

    function resetPagination() {
        Object.keys(pagination).forEach(status => pagination[status].page = 1);
    }

    /* ================= FETCH ONLY ================= */
    async function loadEvaluations({ force = false } = {}) {
        if (!force && (isUpdating || modalOpen)) return;
        isUpdating = true;

        try {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            await Promise.all(Object.keys(tables).map(async (status) => {

                // 🚀 Fetch only if empty or forced
                if (allEvaluations[status].length === 0 || force) {
                    const params = new URLSearchParams({
                        start_date: startDate,
                        end_date: endDate,
                        status,
                        page: pagination[status].page,
                        per_page: pagination[status].perPage
                    });

                    const res = await safeFetch('/evaluations/list?' + params.toString());
                    allEvaluations[status] = await res.json();
                }

                renderTable(status); // render from cache
            }));

        } catch (err) {
            console.error(err);
        } finally {
            isUpdating = false;
        }
    }

    /* ================= FAST RENDER ================= */
    window.isAdmin = @json(auth()->user()->isAdmin());
    window.isHead = @json(auth()->user()->isHead());
    window.isPgso = @json(auth()->user()->isPgso());
    window.isPresentativeStaff = @json(auth()->user()->isPresentativeStaff());
function renderTable(status) {

    const tableBody = tbodyCache[status];
    const search = searchInput.value.toLowerCase();

    let filtered = allEvaluations[status].filter(item =>
        (item.po_no ?? '').toLowerCase().includes(search) ||
        (item.supplier_name ?? '').toLowerCase().includes(search) ||
        (item.evaluator ?? '').toLowerCase().includes(search) ||
        (item.office_name ?? '').toLowerCase().includes(search)
    );

    if (filtered.length === 0) {
        tableBody.innerHTML = `
            <tr id="empty-${status}">
                <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <i class="ri-inbox-line text-3xl text-gray-300"></i>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">No evaluations found</span>
                    </div>
                </td>
            </tr>
        `;
        rowCache[status].clear();
        if (paginationContainers[status]) paginationContainers[status].innerHTML = '';
        return;
    }

    // Clean up empty state row if present
    const emptyRow = document.getElementById(`empty-${status}`);
    if (emptyRow) {
        emptyRow.remove();
    }

    const { page, perPage } = pagination[status];
    const startIndex = (page - 1) * perPage;
    const pageData = filtered.slice(startIndex, startIndex + perPage);

    pageData.forEach(item => {
        const rowId = `row-${status}-${item.id}`.replace(/\s+/g,'-');

        const score = parseFloat(item.average_score);
        let scoreBadge = '<span class="text-gray-400 text-xs font-medium">-</span>';
        if (!isNaN(score)) {
            let badgeStyle = "bg-green-100 text-green-800 border-green-300";
            if (score < 60) badgeStyle = "bg-red-100 text-red-800 border-red-300";
            else if (score < 75) badgeStyle = "bg-amber-100 text-amber-800 border-amber-300";
            else if (score < 90) badgeStyle = "bg-blue-100 text-blue-800 border-blue-300";

            scoreBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border ${badgeStyle}">${item.average_score}%</span>`;
        }

let actionOptions = `
    <option value="" selected class="text-black bg-white">
        Select
    </option>
`;

const optionClass = `class="text-black bg-white"`; // reusable

const itemStatus = item.status?.toLowerCase();

/* ================= PGSO ================= */
if (window.isPgso) {

    if (itemStatus === 'pending') {
        actionOptions += `
            <option value="edit" ${optionClass}>Evaluate</option>
            <option value="delete" ${optionClass}>Delete</option>
        `;
    }

}

if (window.isHead) {

    if (itemStatus === 'head review') {
        actionOptions += `
            <option value="edit" ${optionClass}>Evaluate</option>
        `;
    }
    else if (itemStatus === 'submitted') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="download" ${optionClass}>Download</option>
        `;
    }

}

/* ================= ADMIN ================= */
else if (window.isAdmin) {

    if (itemStatus === 'pending') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Evaluate</option>
            <option value="delete" ${optionClass}>Delete</option>
        `;
    }
    else if (itemStatus === 'head review') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Evaluate</option>

            <option value="delete" ${optionClass}>Delete</option>
        `;
    }
    else if (itemStatus === 'submitted') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Edit</option>

            <option value="download" ${optionClass}>Download</option>
            <option value="delete" ${optionClass}>Delete</option>
        `;
    }
}
else if (window.isPresentativeStaff) {

    if (itemStatus === 'pending') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
        `;
    }
    else if (itemStatus === 'head review') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Evaluate</option>
        `;
    }
    else if (itemStatus === 'submitted') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="download" ${optionClass}>Download</option>
        `;
    }
}

/* ================= END USER ================= */
else {

    if (itemStatus === 'pending') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Evaluate</option>
        `;
    }
    else if (itemStatus === 'head review') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Edit</option>
        `;
    }
    else if (itemStatus === 'submitted') {
        actionOptions += `
            <option value="view" ${optionClass}>View</option>
            <option value="edit" ${optionClass}>Edit</option>
            <option value="download" ${optionClass}>Download</option>
        `;
    }
}


        let row = document.getElementById(rowId);

        if (!row) {
            rowCache[status].add(rowId);
            // ✅ CREATE ROW
            tableBody.insertAdjacentHTML('beforeend', `
<tr id="${rowId}" class="hover:bg-blue-50/50 transition-colors duration-150 border-b border-gray-100">
    <td class="px-4 py-3 text-xs font-bold text-gray-800">${item.po_no ?? '-'}</td>
    <td class="px-4 py-3 text-xs font-semibold text-gray-900">${item.supplier_name ?? '-'}</td>
    <td class="px-4 py-3 text-xs text-gray-700">
        <div class="font-medium text-gray-800">${item.office_name ?? '-'}</div>
        ${item.end_user ? `<div class="text-[10px] text-gray-500 uppercase font-semibold">(${item.end_user})</div>` : ''}
    </td>
    <td class="px-4 py-3 text-center">${scoreBadge}</td>
    <td class="px-4 py-3 text-center text-xs font-semibold text-gray-700">${item.period_year ? `CY ${item.period_year}` : '-'}</td>
    <td class="px-4 py-3 text-center text-xs text-gray-600 font-medium">${item.date_evaluation ?? '-'}</td>
    <td class="px-4 py-3 text-center action-cell">
        <select
            class="evaluationAction
                   bg-gradient-to-r from-orange-400 to-blue-500
                   text-white text-xs font-semibold
                   px-3 py-1.5 rounded-lg shadow-sm border border-transparent
                   focus:outline-none focus:ring-2 focus:ring-blue-300
                   hover:shadow-md transition duration-200
                   cursor-pointer"
            data-id="${item.id}"
            data-status="${item.status}"
            data-table-status="${status}"
            data-request-status="${item.request_status ?? ''}">
            ${actionOptions}
        </select>
    </td>
</tr>
            `);

        } else {
            // ✅ UPDATE EXISTING ROW (NO RECREATE)
            const cells = row.querySelectorAll('td');

            // 0 - PO No
            cells[0].textContent = item.po_no ?? '-';

            // 1 - Supplier
            cells[1].textContent = item.supplier_name ?? '-';

            // 2 - Office + End User
            cells[2].innerHTML = `
                <div class="font-medium text-gray-800">${item.office_name ?? '-'}</div>
                ${item.end_user ? `<div class="text-[10px] text-gray-500 uppercase font-semibold">(${item.end_user})</div>` : ''}
            `;

            // 3 - Score
            cells[3].innerHTML = scoreBadge;

            // 4 - CY PERIOD (FIXED)
            cells[4].textContent = item.period_year ? `CY ${item.period_year}` : '-';

            // 5 - Date
            cells[5].textContent = item.date_evaluation ?? '-';

            // ✅ ONLY update action column IF STATUS CHANGED
            const select = row.querySelector('select');

            if (select.dataset.status !== item.status) {
                select.dataset.status = item.status;
                select.innerHTML = actionOptions;
            }
        }
    });

    // ✅ REMOVE OLD ROWS


const pageIds = new Set(
    pageData.map(item => `row-${status}-${item.id}`.replace(/\s+/g,'-'))
);

// remove old rows not in current page
rowCache[status].forEach(id => {
    if (!pageIds.has(id)) {
        document.getElementById(id)?.remove();
        rowCache[status].delete(id);
    }
});

    renderPagination(status, filtered.length);
}

    /* ================= PAGINATION ================= */
function renderPagination(status, totalItems) {

    const container = paginationContainers[status];
    const { page, perPage } = pagination[status];

    const totalPages = Math.ceil(totalItems / perPage);

    container.innerHTML = '';

    // =========================
    // PAGE INFO (optional UI)
    // =========================
    const pageLabel = document.getElementById(`${status.toLowerCase()}CurrentPage`);
    const pageInfo = document.getElementById(`${status.toLowerCase()}PageInfo`);

    const startItem = (page - 1) * perPage + 1;
    const endItem = Math.min(page * perPage, totalItems);

    if (pageLabel) {
        pageLabel.textContent = page;
    }

    if (pageInfo) {
        pageInfo.textContent = `${startItem}-${endItem} of ${totalItems}`;
    }

    if (totalPages <= 1) return;

    // =========================
    // BUTTON CREATOR
    // =========================
    const createBtn = (label, targetPage, extraClass = '', disabled = false) => {

        const btn = document.createElement('button');
        btn.innerHTML = label;

        btn.className = `pagination-btn ${extraClass}`;

        if (disabled) {
            btn.classList.add('disabled');
            btn.disabled = true;
        }

        if (targetPage === page) {
            btn.classList.add('active');
        }

        btn.onclick = () => {
            if (targetPage < 1 || targetPage > totalPages) return;
            if (targetPage === page) return;

            pagination[status].page = targetPage;
            renderTable(status);
        };

        return btn;
    };

    // =========================
    // PREV BUTTON
    // =========================
    container.appendChild(
        createBtn('‹', page - 1, '', page === 1)
    );

    // =========================
    // PAGE WINDOW LOGIC
    // =========================
    const maxVisible = 5;

    let start = Math.max(1, page - 2);
    let end = Math.min(totalPages, start + maxVisible - 1);

    if (end - start < maxVisible - 1) {
        start = Math.max(1, end - (maxVisible - 1));
    }

    // =========================
    // FIRST PAGE + DOTS
    // =========================
    if (start > 1) {
        container.appendChild(createBtn(1, 1));

        if (start > 2) {
            const dots = document.createElement('span');
            dots.className = 'px-2 text-gray-400';
            dots.textContent = '...';
            container.appendChild(dots);
        }
    }

    // =========================
    // PAGE NUMBERS
    // =========================
    for (let i = start; i <= end; i++) {
        container.appendChild(createBtn(i, i));
    }

    // =========================
    // LAST PAGE + DOTS
    // =========================
    if (end < totalPages) {

        if (end < totalPages - 1) {
            const dots = document.createElement('span');
            dots.className = 'px-2 text-gray-400';
            dots.textContent = '...';
            container.appendChild(dots);
        }

        container.appendChild(createBtn(totalPages, totalPages));
    }

    // =========================
    // NEXT BUTTON
    // =========================
    container.appendChild(
        createBtn('›', page + 1, '', page === totalPages)
    );
}


/* ---------------- EVENT DELEGATION ---------------- */

document.addEventListener('change', async function (e) {
    if (!e.target.classList.contains('evaluationAction')) return;

    const evaluationId = e.target.dataset.id;
    const action = e.target.value;

    try {
        /* ---------------- VIEW ---------------- */
if (action === "view") {

    try {
        Swal.fire({
            title: 'Processing...',
            text: 'Opening evaluation...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        await viewEvaluation(evaluationId);

    } catch (err) {
        console.error(err);

        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to open evaluation."
        });
    } finally {
        Swal.close();
    }
}

        /* ---------------- EDIT ---------------- */
else if (action === "edit") {
    const status = e.target.dataset.status?.toLowerCase();
    const requestStatus = e.target.dataset.requestStatus?.toLowerCase();
    const isAdmin = window.isAdmin === true;
    const tableStatus = e.target.dataset.tableStatus;

    // 🔥 helper function
const openEditWithLoading = async () => {
    try {
        Swal.fire({
            title: 'Processing...',
            text: 'Opening evaluation...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        await updateEvaluation(evaluationId, tableStatus);

        Swal.close();

    } catch (err) {
        console.error(err);
        Swal.close();

        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Failed to open evaluation."
        });
    }
};

    // ✅ ADMIN → always allowed
    if (isAdmin) {
        return openEditWithLoading();
    }

    /* ================= NEW RULE ================= */

    // ✅ HEAD REVIEW → allow edit directly
     if (status === "pending" || status === "head review") {
        return openEditWithLoading();
    }

    /* ================= END USER RULES ================= */

    // 🚫 Submitted without request
    if (status === "submitted" && !requestStatus) {
        Swal.fire({
            icon: "warning",
            title: "Action Restricted",
            text: "Submit a request to the administrator first."
        });
        return;
    }

    // ⏳ Waiting approval
    if (requestStatus === "request") {
        Swal.fire({
            icon: "info",
            title: "Pending Request",
            text: "Your request is still pending approval."
        });
        return;
    }

    // ❌ Rejected
    if (requestStatus === "rejected") {
        Swal.fire({
            icon: "error",
            title: "Request Rejected",
            text: "Your request was rejected."
        });
        return;
    }

    // 🔒 Done → must request again
    if (requestStatus === "done") {
        Swal.fire({
            icon: "warning",
            title: "Locked",
            html: `This evaluation is already completed.<br><br>
                   Please submit a new request to edit again.`
        });
        return;
    }

    // ✅ Approved → allow edit
    if (requestStatus === "approved") {
        return openEditWithLoading();
    }

    // fallback
    Swal.fire({
        icon: "warning",
        title: "Not Allowed",
        text: "You cannot edit this evaluation."
    });
}

        /* ---------------- LINK ---------------- */
        // else if (action === 'link') {
        //     try {
        //         modalOpen = true;

        //         Swal.fire({
        //             title: 'Processing...',
        //             text: 'Generating review link...',
        //             allowOutsideClick: false,
        //             allowEscapeKey: false,
        //             didOpen: () => Swal.showLoading()
        //         });

        //         const res = await safeFetch(`/evaluations/${evaluationId}/review-link`);
        //         const linkData = await res.json();

        //         Swal.close();

        //         if (!linkData.token) {
        //             Swal.fire('Error!', 'No active Head review link available.', 'error');
        //             modalOpen = false;
        //             return;
        //         }

        //         const origin = window.location.origin;
        //         const reviewUrl = `${origin}/evaluation/head-review/${linkData.token}`;
        //         const reviewCode = linkData.code ?? '';

        //         // Unique IDs
        //         const linkInputId = `copyEvalLink-${evaluationId}`;
        //         const codeInputId = `copyEvalCode-${evaluationId}`;
        //         const linkBtnId = `copyLinkBtn-${evaluationId}`;
        //         const codeBtnId = `copyCodeBtn-${evaluationId}`;

        //         Swal.fire({
        //             title: 'Head Review Access',
        //             width: 520,
        //             showConfirmButton: false,
        //             showCloseButton: true,
        //             allowOutsideClick: true,

        //             html: `
        //                 <style>
        //                     .access-container {
        //                         text-align: left;
        //                         font-family: system-ui, sans-serif;
        //                     }
        //                     .access-card {
        //                         background: #f9fafb;
        //                         border: 1px solid #e5e7eb;
        //                         border-radius: 10px;
        //                         padding: 14px;
        //                         margin-top: 12px;
        //                         transition: all 0.2s ease;
        //                     }
        //                     .access-card:hover {
        //                         border-color: #fb923c;
        //                         box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        //                     }
        //                     .access-label {
        //                         font-weight: 600;
        //                         font-size: 13px;
        //                         color: #374151;
        //                         margin-bottom: 6px;
        //                         display: block;
        //                     }
        //                     .access-input {
        //                         width: 100%;
        //                         border: 1px solid #d1d5db;
        //                         border-radius: 6px;
        //                         padding: 8px 10px;
        //                         font-size: 13px;
        //                         background: white;
        //                         margin-bottom: 10px;
        //                     }
        //                     .copy-btn {
        //                         width: 100%;
        //                         padding: 9px;
        //                         border: none;
        //                         border-radius: 7px;
        //                         cursor: pointer;
        //                         font-weight: 600;
        //                         font-size: 13px;
        //                         color: white;
        //                         background: linear-gradient(135deg,#fb923c,#f97316);
        //                         transition: all .25s ease;
        //                     }
        //                     .copy-btn:hover {
        //                         transform: translateY(-1px);
        //                         box-shadow: 0 6px 14px rgba(0,0,0,.15);
        //                     }
        //                     .copy-btn.copied {
        //                         background: linear-gradient(135deg,#22c55e,#16a34a);
        //                         transform: scale(1.05);
        //                     }
        //                 </style>

        //                 <div class="access-container">
        //                     <div class="access-card">
        //                         <span class="access-label">🔗 Review Link</span>
        //                         <input type="text" id="${linkInputId}" class="access-input" value="${reviewUrl}" readonly>
        //                         <button id="${linkBtnId}" class="copy-btn">Copy Link</button>
        //                     </div>

        //                     <div class="access-card">
        //                         <span class="access-label">🔐 Review Code</span>
        //                         <input type="text" id="${codeInputId}" class="access-input" value="${reviewCode}" readonly>
        //                         <button id="${codeBtnId}" class="copy-btn">Copy Code</button>
        //                     </div>
        //                 </div>
        //             `,

        //             didOpen: () => {
        //                 function animateCopy(button, text) {
        //                     navigator.clipboard.writeText(text);

        //                     const original = button.innerHTML;
        //                     button.classList.add('copied');
        //                     button.innerHTML = "✔ Copied";

        //                     setTimeout(() => {
        //                         button.classList.remove('copied');
        //                         button.innerHTML = original;
        //                     }, 1600);
        //                 }

        //                 document.getElementById(linkBtnId)
        //                     .addEventListener('click', (e) => animateCopy(e.target, reviewUrl));

        //                 document.getElementById(codeBtnId)
        //                     .addEventListener('click', (e) => animateCopy(e.target, reviewCode));
        //             },

        //             didClose: () => {
        //                 modalOpen = false;
        //             }
        //         });

        //     } catch (err) {
        //         console.error(err);
        //         Swal.close();

        //         Swal.fire('Error!', 'Failed to fetch review link.', 'error');
        //         modalOpen = false;
        //     }
        // }

        /* ---------------- DOWNLOAD ---------------- */
        else if (action === "download") {
            window.open(`/evaluations/${evaluationId}/download`, '_blank');
        }

else if (action === "delete") {
    // Show the confirmation popup
    const confirmDelete = await Swal.fire({
        title: "Move to Recycle Bin?",
        text: "You can restore this later.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#f97316",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete it"
    });

    // If the user cancels, do nothing and return
    if (!confirmDelete.isConfirmed) return;

    // If the user confirms, proceed with the delete operation
    try {
        Swal.fire({
            title: 'Processing...',
            text: 'Moving to recycle bin...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const res = await safeFetch(`/delete/evaluations/${evaluationId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        });

        const data = await res.json();

        Swal.close();

        if (data.success) {
            // If the delete was successful, show success message and reload the page
            Swal.fire("Deleted!", "Moved to recycle bin.", "success");
            window.location.reload();
        } else {
            // If there was an error, show error message
            Swal.fire("Error", data.message || "Delete failed.", "error");
        }

    } catch (err) {
        console.error(err);
        Swal.fire("Error", "Something went wrong.", "error");
    }
}

} catch (err) {
    console.error(err);
    alert("Action failed.");
} finally {
    // Always return the dropdown to "Select"
    e.target.selectedIndex = 0;
}
});
    /* ================= EVENTS ================= */

    // ⚡ INSTANT SEARCH (no debounce)
let searchTimeout;
let lastSearch = "";

searchInput.addEventListener('input', () => {

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {

        if (lastSearch === searchInput.value) return;

        lastSearch = searchInput.value;

        resetPagination();

        Object.keys(tables).forEach(status => renderTable(status));

    }, 250);
});

    // Date filter (requires fetch)
    startDateInput.addEventListener('change', () => {
        resetPagination();
        loadEvaluations({ force: true });
    });

    endDateInput.addEventListener('change', () => {
        resetPagination();
        loadEvaluations({ force: true });
    });

    clearButton.addEventListener('click', () => {
        searchInput.value = '';
        startDateInput.value = '';
        endDateInput.value = '';
        resetPagination();
        loadEvaluations({ force: true });
    });

    /* ================= INIT ================= */
    loadEvaluations({ force: true });

});
</script>

<style>
.pagination-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    user-select: none;
}

.pagination-btn:hover:not(.active):not(.disabled) {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
    transform: translateY(-1px);
}

.pagination-btn.active {
    background-color: #0f172a;
    border-color: #0f172a;
    color: #ffffff;
    font-weight: 700;
    box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.25), 0 2px 4px -2px rgba(15, 23, 42, 0.1);
}

.pagination-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background-color: #f8fafc;
    border-color: #e2e8f0;
    color: #94a3b8;
    box-shadow: none;
}
</style>
