 @auth
    @if (!auth()->user()->isEndUser() || !auth()->user()->isPresentativeStaff() || !auth()->user()->isHead())
 <div class="back absolute top-0 left-0 w-full h-full rotate-y-180 hidden rounded-xl shadow-lg p-6 overflow-auto">

<div class="flex justify-between items-center mb-4">
    <!-- Left: Heading -->
    <h1 class="text-xl font-bold text-gray-900">Analytics Dashboard</h1>

    <!-- Right: Buttons -->
    <div id="report-actions" class="flex space-x-3">
        <!-- Download Summary Button -->
        <button type="button"
            class="flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors shadow-md"
            onclick="handleDownload()"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download Summary
        </button>

        <!-- Calculate Evaluations Button -->
        <button type="button"
            class="flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors shadow-md"
            onclick="handleCalculate()"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            Calculate Evaluations
        </button>
    </div>
</div>
<br>



            <div id="report-table">
<div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
  <!-- Search -->
  <div class="relative flex-1">
    <input id="report-search" type="text" placeholder="Search applications..."
           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
      <i class="ri-search-line"></i>
    </div>
  </div>




<!-- Department Filter -->
<div class="relative">
  <select id="report-department"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary text-sm">
    <option value="">All Departments</option>
  </select>
  <label class="absolute -top-2 left-2 bg-gray-50 px-1 text-xs text-gray-600">
    Department
  </label>
</div>

<!-- Supplier Filter -->
<div class="relative">
  <select id="report-supplier"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary text-sm">
    <option value="">All Suppliers</option>
  </select>
  <label class="absolute -top-2 left-2 bg-gray-50 px-1 text-xs text-gray-600">
    Supplier
  </label>
</div>




<div class="relative">
  <select id="report-period-year"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
    <option value="">All Years</option>
  </select>
  <label
    class="absolute -top-2 left-2 bg-gray-50 px-1 text-xs text-gray-600">
    Period Year
  </label>
</div>

  <!-- Clear Filter Button -->
  <div>
    <button id="report-clearFilters"
            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg shadow-sm text-sm transition-all duration-200">
      Clear Filter
    </button>
  </div>
</div>
                <br>
             <div class="w-full overflow-x-auto">
              <table class="min-w-full divide-y divide-orange-200">

                <thead class="bg-orange-100/70">
                  <tr>
                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Purchase Order</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Company Name</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Department</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Evaluation Score</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Covered Period</th>
                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>

                <tbody class="bg-white/80 divide-y divide-orange-100">
                </tbody>

              </table>

              <div id="report" class="flex justify-center items-center mt-2 space-x-2"></div>
             </div>
            </div>
            <br>
            <br>


 <div class="flex flex-col gap-6 w-full">

  <!-- Semester Evaluation Score by Supplier -->
  <div class="bg-white p-4 rounded-lg shadow w-full">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
      <div>
        <h2 id="semesterChartTitle" class="text-lg font-semibold text-gray-900">
          Supplier Evaluation Score by Semester
        </h2>
        <p class="text-xs text-gray-500">Comparison of 1st Semester (Jan–Jun) vs 2nd Semester (Jul–Dec) average scores</p>
      </div>

      <!-- Filters & Download PDF -->
      <div class="flex flex-wrap gap-2 w-full sm:w-auto items-center">
        <select id="departmentSelectSemesterChart" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled selected>Loading departments...</option>
        </select>
        <select id="yearSelectSemesterChart" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option disabled selected>Select Year</option>
        </select>
        <button type="button" onclick="downloadSemesterPdf()" class="flex items-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg shadow-sm transition-colors cursor-pointer">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
          </svg>
          Download PDF
        </button>
      </div>
    </div>

    <!-- Chart Canvas -->
    <div class="relative h-[400px] w-full mb-6">
      <canvas id="semesterChartCanvas" class="w-full h-full"></canvas>
    </div>

    <!-- Semester Summary Table -->
    <div class="mt-4 overflow-x-auto border border-gray-200 rounded-lg">
      <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h3 class="text-sm font-semibold text-gray-700">Semester Score Breakdown</h3>

        <!-- Search Input -->
        <div class="relative w-full sm:w-64">
          <input id="semester-table-search" type="text" placeholder="Search supplier..."
                 class="w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-xs">
          <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs">
            <i class="ri-search-line"></i>
          </div>
        </div>
      </div>

      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">1st Semester (Jan - Jun)</th>
            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">2nd Semester (Jul - Dec)</th>
            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Overall Average</th>
            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating Status</th>
          </tr>
        </thead>
        <tbody id="semesterTableBody" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="5" class="text-center py-4 text-gray-500 text-sm">Loading semester evaluation data...</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination Container -->
      <div id="semesterTablePagination" class="flex justify-center items-center py-2.5 bg-gray-50 border-t border-gray-200 space-x-1.5"></div>
    </div>

  </div>

  <!-- Line Chart -->
  <div class="bg-white p-4 rounded-lg shadow w-full">

    <!-- Header -->
    <h2 id="lineChartTitle" class="text-lg font-semibold mb-2">
      Monthly Evaluation Count
    </h2>

    <!-- Filters -->
    <div class="flex gap-3 mb-4">
      <!-- Department -->
      <select id="departmentSelectLine"
        class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option disabled selected>Loading departments...</option>
      </select>

      <!-- Year -->
      <select id="yearSelectLine"
        class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option disabled selected>Select Year</option>
      </select>
    </div>

    <!-- Chart -->
<div class="relative h-[450px] w-full">
  <canvas id="lineChart" class="w-full h-full"></canvas>
</div>

  </div>

  <!-- Bar Chart -->
  <div class="bg-white p-4 rounded-lg shadow w-full">

    <!-- Title -->
    <h2 id="barChartTitle" class="text-lg font-semibold mb-2">
      Supplier Scores
    </h2>

    <!-- Filters -->
    <div class="flex gap-3 mb-3">
      <select id="departmentSelectBarChart" class="border p-2 rounded w-full"></select>
      <select id="yearSelectBarChart" class="border p-2 rounded w-full"></select>
    </div>

    <!-- Chart -->
<div class="relative h-[450px] w-full">
  <canvas id="barChartCanvas" class="w-full h-full"></canvas>
</div>

  </div>

</div>




<script>
let allEvaluations = []; // global
let currentPage = 1;      // store current page
const rowsPerPage = 5;

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('report-search');
    const clearButton = document.getElementById('report-clearFilters');

    const tableBody = document.querySelector('#report-table tbody');
    const actionContainer = document.getElementById('report-actions');
    const paginationContainer = document.getElementById('report'); // container for pagination buttons

    let isUpdating = false;

const periodYearInput = document.getElementById('report-period-year');

// Generate years dynamically
const currentYear = new Date().getFullYear();
const startYear = 2020; // 👈 change if needed

for (let year = currentYear; year >= startYear; year--) {
    const option = document.createElement('option');
    option.value = year;
    option.textContent = year;
    periodYearInput.appendChild(option);
}

    /* ================= FETCH ONLY ================= */
async function fetchEvaluations({ force = false, resetPage = false } = {}) {
    if (!force && isUpdating) return;
    isUpdating = true;

    try {
        if (allEvaluations.length === 0 || force) {
            const year = periodYearInput?.value || '';

            const params = new URLSearchParams({
                period_year: year
            });

            const res = await safeFetch('/admin-dashboard/data?' + params.toString());
            const result = await res.json();

            allEvaluations = result.data || [];
        }

        populateFilters(); // ✅ ALWAYS call AFTER data loads

        if (resetPage) currentPage = 1;

        renderTable();

    } catch (err) {
        console.error(err);
    } finally {
        isUpdating = false;
    }
}

function populateFilters() {
    const deptSelect = document.getElementById('report-department');
    const supplierSelect = document.getElementById('report-supplier');

    const departments = [...new Set(allEvaluations.map(e => e.office_name).filter(Boolean))];
    const suppliers = [...new Set(allEvaluations.map(e => e.supplier_name).filter(Boolean))];

    // Clear old options
    deptSelect.innerHTML = '<option value="">All Departments</option>';
    supplierSelect.innerHTML = '<option value="">All Suppliers</option>';

    // Populate Departments
    departments.forEach(dep => {
        const opt = document.createElement('option');
        opt.value = dep;
        opt.textContent = dep;
        deptSelect.appendChild(opt);
    });

    // Populate Suppliers
    suppliers.forEach(sup => {
        const opt = document.createElement('option');
        opt.value = sup;
        opt.textContent = sup;
        supplierSelect.appendChild(opt);
    });
}

    /* ================= FILTER + PAGINATION ================= */
function getFilteredData() {
    const search = searchInput?.value.toLowerCase() || '';
    const selectedDept = document.getElementById('report-department')?.value || '';
    const selectedSupplier = document.getElementById('report-supplier')?.value || '';

    return allEvaluations.filter(evaluation => {

        const matchesSearch =
            (evaluation.po_no ?? '').toLowerCase().includes(search) ||
            (evaluation.supplier_name ?? '').toLowerCase().includes(search) ||
            (evaluation.office_name ?? '').toLowerCase().includes(search);

        const matchesDept = !selectedDept || evaluation.office_name === selectedDept;
        const matchesSupplier = !selectedSupplier || evaluation.supplier_name === selectedSupplier;

        return matchesSearch && matchesDept && matchesSupplier;
    });
}

    function renderTable() {
        const filtered = getFilteredData();

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;

        const startIndex = (currentPage - 1) * rowsPerPage;
        const pageData = filtered.slice(startIndex, startIndex + rowsPerPage);

        // render rows
        let rows = '';
        if (pageData.length === 0) {
            rows = `<tr><td colspan="7" class="text-center py-4 text-gray-500">No data found</td></tr>`;
        } else {
            pageData.forEach(evaluation => {
                rows += `
<tr>
<td class="hidden">${evaluation.id}</td>
<td class="px-4 py-3 text-center">${evaluation.po_no}</td>
<td class="px-4 py-3 text-center">${evaluation.supplier_name}</td>
<td class="px-4 py-3 text-center">${evaluation.office_name}</td>
<td class="px-4 py-3 text-center">${evaluation.total_score}%</td>
<td class="px-4 py-3 text-center">${evaluation.covered_period ?? '-'}</td>
<td class="px-4 py-3 text-center">
    <button class="text-blue-600 downloadBtn" data-id="${evaluation.id}">Download</button>
</td>
</tr>`;
            });
        }

        tableBody.innerHTML = rows;
        renderPagination(filtered.length);
    }

function renderPagination(totalItems) {

    paginationContainer.innerHTML = '';

    const totalPages = Math.ceil(totalItems / rowsPerPage);

    // safety: fix invalid page
    if (currentPage > totalPages) currentPage = totalPages || 1;
    if (currentPage < 1) currentPage = 1;

    if (totalPages <= 1) return;

    const createBtn = (label, page, extraClass = '', disabled = false) => {

        const btn = document.createElement('button');
        btn.innerHTML = label;

        btn.className = `px-3 py-1 rounded text-sm transition
            ${extraClass}`;

        if (disabled) {
            btn.classList.add('opacity-40', 'cursor-not-allowed');
            btn.disabled = true;
        }

        if (page === currentPage) {
            btn.classList.add('bg-orange-500', 'text-white', 'font-semibold');
        } else {
            btn.classList.add('bg-gray-200', 'hover:bg-gray-300');
        }

        btn.onclick = () => {
            if (page < 1 || page > totalPages) return;
            if (page === currentPage) return;

            currentPage = page;
            renderTable();
        };

        return btn;
    };

    // =========================
    // PREV
    // =========================
    paginationContainer.appendChild(
        createBtn('‹', currentPage - 1, '', currentPage === 1)
    );

    // =========================
    // SMART WINDOW
    // =========================
    const maxVisible = 5;

    let start = Math.max(1, currentPage - 2);
    let end = Math.min(totalPages, start + maxVisible - 1);

    if (end - start < maxVisible - 1) {
        start = Math.max(1, end - (maxVisible - 1));
    }

    // =========================
    // FIRST PAGE + DOTS
    // =========================
    if (start > 1) {
        paginationContainer.appendChild(createBtn(1, 1));

        if (start > 2) {
            const dots = document.createElement('span');
            dots.textContent = '...';
            dots.className = 'px-2 text-gray-400';
            paginationContainer.appendChild(dots);
        }
    }

    // =========================
    // PAGE NUMBERS
    // =========================
    for (let i = start; i <= end; i++) {
        paginationContainer.appendChild(createBtn(i, i));
    }

    // =========================
    // LAST PAGE + DOTS
    // =========================
    if (end < totalPages) {

        if (end < totalPages - 1) {
            const dots = document.createElement('span');
            dots.textContent = '...';
            dots.className = 'px-2 text-gray-400';
            paginationContainer.appendChild(dots);
        }

        paginationContainer.appendChild(createBtn(totalPages, totalPages));
    }

    // =========================
    // NEXT
    // =========================
    paginationContainer.appendChild(
        createBtn('›', currentPage + 1, '', currentPage === totalPages)
    );
}

    /* ================= EVENTS ================= */
    searchInput?.addEventListener('input', () => {
        currentPage = 1; // reset page on search
        renderTable();
    });

    periodYearInput?.addEventListener('change', () =>
        fetchEvaluations({ force: true, resetPage: true })
    );
    clearButton?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (periodYearInput) periodYearInput.value = '';
        fetchEvaluations({ force: true, resetPage: true });
    });

    /* ================= INIT ================= */
    fetchEvaluations({ force: true });

    document.getElementById('report-department')?.addEventListener('change', () => {
    currentPage = 1;
    renderTable();
    });

    document.getElementById('report-supplier')?.addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });

    // Auto-refresh without resetting page
    // setInterval(() => {
    //     if (document.activeElement !== searchInput) {
    //         fetchEvaluations({ force: true, resetPage: false });
    //     }
    // }, 5000);

});

/* ================= DOWNLOAD ================= */
function handleDownload() {
    const search = document.getElementById('report-search')?.value.toLowerCase() || '';
    const year = document.getElementById('report-period-year')?.value || '';
    const selectedDept = document.getElementById('report-department')?.value || '';
    const selectedSupplier = document.getElementById('report-supplier')?.value || '';

    const filtered = allEvaluations.filter(evaluation => {

        const matchesSearch =
            (evaluation.po_no ?? '').toLowerCase().includes(search) ||
            (evaluation.supplier_name ?? '').toLowerCase().includes(search) ||
            (evaluation.office_name ?? '').toLowerCase().includes(search);

        const matchesDept = !selectedDept || evaluation.office_name === selectedDept;
        const matchesSupplier = !selectedSupplier || evaluation.supplier_name === selectedSupplier;
        const matchesYear = !year || evaluation.period_year == year;

        return matchesSearch && matchesDept && matchesSupplier && matchesYear;
    });

    if (filtered.length === 0) {
        alert("No evaluations to download.");
        return;
    }

    const ids = filtered.map(e => e.id);

    const params = new URLSearchParams({
        period_year: year,
        search,
        department: selectedDept,
        supplier: selectedSupplier,
        ids
    });

    window.open('/admin/evaluations/summary/download?' + params.toString(), '_blank');
}

/* ================= CALCULATE EVALUATIONS ================= */
function handleCalculate() {
    const modal = document.getElementById('calculateModal');
    const modalBox = document.getElementById('modalBox');
    const modalContent = document.getElementById('modalContent');
    const closeBtns = modal.querySelectorAll('.close-calculate-modal');

    const openModal = () => {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    const closeModal = () => {
        modalBox.classList.remove('scale-100', 'opacity-100');
        modalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    closeBtns.forEach(btn => btn.onclick = closeModal);

    // ================= NEW FILTERS =================
    const search = document.getElementById('report-search')?.value.toLowerCase() || '';
    const selectedDept = document.getElementById('report-department')?.value || '';
    const selectedSupplier = document.getElementById('report-supplier')?.value || '';
    const selectedYear = document.getElementById('report-period-year')?.value || '';

    const filtered = allEvaluations.filter(evaluation => {

        const matchesSearch =
            (evaluation.po_no ?? '').toLowerCase().includes(search) ||
            (evaluation.supplier_name ?? '').toLowerCase().includes(search) ||
            (evaluation.office_name ?? '').toLowerCase().includes(search);

        const matchesDept = !selectedDept || evaluation.office_name === selectedDept;
        const matchesSupplier = !selectedSupplier || evaluation.supplier_name === selectedSupplier;
        const matchesYear = !selectedYear || evaluation.period_year == selectedYear;

        return matchesSearch && matchesDept && matchesSupplier && matchesYear;
    });

    if (filtered.length === 0) {
        alert("No evaluations to calculate.");
        return;
    }

    let totalScore = 0;
    let count = 0;
    const scoresPerPO = [];

    filtered.forEach(evaluation => {
        if (evaluation.total_score != null) {
            totalScore += parseFloat(evaluation.total_score);
            count++;
            scoresPerPO.push({
                poNumber: evaluation.po_no,
                score: parseFloat(evaluation.total_score)
            });
        }
    });

    if (count === 0) {
        alert("No valid scores found in the filtered results.");
        return;
    }

    const averageScore = (totalScore / count).toFixed(2);

    // ================= MODAL UI =================
    modalContent.innerHTML = `
        <div class="p-4 bg-gray-50 rounded-xl shadow-inner">
            <p class="text-lg"><strong>Total POs:</strong> ${count}</p>
            <p class="text-lg"><strong>Average Score:</strong> ${averageScore}%</p>
        </div>

        <div class="mt-6 max-h-96 overflow-y-auto space-y-3">
            ${scoresPerPO.map(s => `
                <div class="p-3 bg-gray-100 rounded-xl shadow-sm">
                    <div class="flex justify-between mb-1">
                        <span class="font-medium text-gray-700">${s.poNumber}</span>
                        <span class="font-medium text-gray-700">${s.score}%</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-4">
                        <div class="bg-green-500 h-4 rounded-full transition-all duration-1000" style="width: 0%"></div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    openModal();

    // animate bars
    const bars = modalContent.querySelectorAll('.bg-green-500');
    bars.forEach((bar, index) => {
        setTimeout(() => {
            bar.style.width = `${scoresPerPO[index].score}%`;
        }, 100);
    });
}
</script>






          <!-- Chart.js library -->
          <script src="{{asset('script/chart.js')}}"></script>
    <script src="{{asset('script/chartDataLabel.js')}}"></script>

<!-- Chart rendering script -->
<script src="{{ asset('script/chartReport.js') }}"></script>
<script>
const flipInner = document.getElementById('flipInner');
const toggleAnalytics = document.getElementById('toggleAnalytics');
const toggleBack = document.getElementById('toggleBack');
const backSide = flipInner.querySelector('.back');

toggleAnalytics.addEventListener('click', () => {
  backSide.classList.remove('hidden');
  flipInner.style.transform = 'rotateY(180deg)';

  // Delay ensures DOM is ready before charts render
  setTimeout(() => {
    initBarChart();   // previously renderCharts()
    initLineChart();  // line chart
    initSemesterChart(); // semester chart & breakdown table
  }, 300);
});

toggleBack.addEventListener('click', () => {
  flipInner.style.transform = 'rotateY(0deg)';
  setTimeout(() => backSide.classList.add('hidden'), 700);
});
</script>

          <style>
            /* Ensure back matches front size */
            #flipInner .back {
              background-color: #ffffff;
              /* optional, matches card */
              backface-visibility: hidden;
            }
          </style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('downloadBtn')) {

            const evaluationId = e.target.dataset.id;

            if (evaluationId) {
                window.open(`/evaluations/${evaluationId}/download`, '_blank');
            } else {
                console.error('Evaluation ID not found!');
            }
        }
    });
});
</script>
    @endif
@endauth
