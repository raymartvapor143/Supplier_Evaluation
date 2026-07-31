
// SAFE FETCH HELPER
async function safeFetch(url, options = {}) {
  try {
    const res = await fetch(url, options);
    if (!res.ok) throw new Error('Network response was not ok');
    return res;
  } catch (err) {
    console.error('Fetch error:', err);
    return { json: async () => [] };
  }
}


// LOAD DEPARTMENTS
async function loadDepartments(selectElement, includeAll = false) {
  if (!selectElement || selectElement.dataset.loaded) return;

  const res = await safeFetch('/analytics/departments');
  const departments = await res.json();

  let html = includeAll
    ? '<option value="all">All Departments</option>'
    : '<option disabled selected>Select Department</option>';

  departments.forEach(dep => {
    html += `<option value="${dep}">${dep}</option>`;
  });

  selectElement.innerHTML = html;
  selectElement.dataset.loaded = "true";
}


// BAR CHART
let barChartInitialized = false;

async function initBarChart() {
  if (barChartInitialized) return; // prevent double fetch
  barChartInitialized = true;

  const departmentSelect = document.getElementById('departmentSelectBarChart');
  const yearSelect = document.getElementById('yearSelectBarChart');
  const title = document.getElementById('barChartTitle');
  const canvas = document.getElementById('barChartCanvas');

  if (!departmentSelect || !yearSelect || !canvas) return;

  // Load departments once
  await loadDepartments(departmentSelect, true);

  // Populate years
  const currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '<option value="all">All Years</option>';
  for (let i = currentYear; i >= 2020; i--) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    yearSelect.appendChild(opt);
  }

  // Defaults
  departmentSelect.value = "all";
  yearSelect.value = "all";

  // Chart update function
  async function updateBarChart() {
    const department = departmentSelect.value || "all";
    const year = yearSelect.value || "all";

    const deptLabel = department === "all" ? "All Departments" : department;
    const yearLabel = year === "all" ? "All Years" : year;
    if (title) title.textContent = `Supplier Scores - ${deptLabel} (${yearLabel})`;

    let url = `/analytics/department/${department}/suppliers`;
    if (year !== "all") url += `?year=${year}`;

    const res = await safeFetch(url);
    const data = await res.json();

    const labels = data.map(item => item.supplier);
    const values = data.map(item => item.average);
    const poCounts = data.map(item => item.evaluations_count);

    const ctx = canvas.getContext('2d');

    if (window.barChartInstance) window.barChartInstance.destroy();

    window.barChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Average Score per Supplier',
          data: values,
          backgroundColor: values.map((v, i) => v < 60 ? '#EF4444' : `hsl(${i * 360 / Math.max(values.length, 1)}, 70%, 50%)`)
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true },
          datalabels: {
            color: '#fff',
            anchor: 'center',
            align: 'center',
            font: { weight: 'bold', size: 11 },
            formatter: (value, context) => `${value}%\n(${poCounts[context.dataIndex]} POs)`
          },
          tooltip: {
            callbacks: {
              label: ctx => `Score: ${ctx.raw}% (${poCounts[ctx.dataIndex]} POs)`
            }
          }
        },
        scales: {
          y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
        }
      },
      plugins: [ChartDataLabels]
    });
  }

  // Event listeners
  departmentSelect.addEventListener('change', updateBarChart);
  yearSelect.addEventListener('change', updateBarChart);

  // Initial render (only once!)
  updateBarChart();
}

// LINE CHART INIT
let lineChartInitialized = false;

async function initLineChart() {
  if (lineChartInitialized) return;
  lineChartInitialized = true;

  const departmentSelect = document.getElementById('departmentSelectLine');
  const yearSelect = document.getElementById('yearSelectLine');
  const title = document.getElementById('lineChartTitle');

  if (!departmentSelect || !yearSelect) return;

  await loadDepartments(departmentSelect, true);

  const currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '<option value="all">All Years</option>';

  for (let i = currentYear; i >= 2020; i--) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    yearSelect.appendChild(opt);
  }

  departmentSelect.value = "all";
  yearSelect.value = "all";

  function updateChart() {
    const department = departmentSelect.value || "all";
    const year = yearSelect.value || "all";

    const deptLabel = department === "all" ? "All Departments" : department;
    const yearLabel = year === "all" ? "All Years" : year;

    if (title) {
      title.textContent = `Monthly Evaluation Count - ${deptLabel} (${yearLabel})`;
    }

    renderLineChart(department, year);
  }

  departmentSelect.addEventListener('change', updateChart);
  yearSelect.addEventListener('change', updateChart);

  updateChart();
}


// RENDER LINE CHART
async function renderLineChart(department, year) {
  try {
    let url = `/analytics/department/${department}/monthly-evaluations`;

    if (year && year !== "all") {
      url += `?year=${year}`;
    }

    const res = await safeFetch(url);
    const data = await res.json();

    const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const counts = Array(12).fill(0);

    data.forEach(item => {
      counts[item.month - 1] = item.count;
    });

    const canvas = document.getElementById('lineChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    if (window.lineChartInstance) {
      window.lineChartInstance.destroy();
    }

    const yearLabel = year === "all" ? "All Years" : year;

    window.lineChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: monthNames,
        datasets: [{
          label: `Evaluations (${yearLabel})`,
          data: counts,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59,130,246,0.2)',
          tension: 0.3,
          fill: true,
          pointRadius: 4,
          pointBackgroundColor: '#3b82f6'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true },
          tooltip: {
            callbacks: {
              label: ctx => ` ${ctx.raw} evaluations`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });

  } catch (err) {
    console.error('Line chart error:', err);
  }
}

// SEMESTER CHART & TABLE INIT
let semesterChartInitialized = false;

async function initSemesterChart() {
  if (semesterChartInitialized) return;
  semesterChartInitialized = true;

  const departmentSelect = document.getElementById('departmentSelectSemesterChart');
  const yearSelect = document.getElementById('yearSelectSemesterChart');
  const title = document.getElementById('semesterChartTitle');
  const canvas = document.getElementById('semesterChartCanvas');
  const tableBody = document.getElementById('semesterTableBody');
  const searchInput = document.getElementById('semester-table-search');
  const paginationContainer = document.getElementById('semesterTablePagination');

  if (!departmentSelect || !yearSelect || !canvas) return;

  await loadDepartments(departmentSelect, true);

  const currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '<option value="all">All Years</option>';
  for (let i = currentYear; i >= 2020; i--) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    yearSelect.appendChild(opt);
  }

  departmentSelect.value = "all";
  yearSelect.value = "all";

  let allSemesterData = [];
  let semesterCurrentPage = 1;
  const semesterRowsPerPage = 5;
  let semesterSearchQuery = '';

  if (searchInput) {
    searchInput.value = '';
    searchInput.addEventListener('input', (e) => {
      semesterSearchQuery = e.target.value.toLowerCase().trim();
      semesterCurrentPage = 1;
      renderSemesterTable();
    });
  }

  async function updateSemesterChart() {
    const department = departmentSelect.value || "all";
    const year = yearSelect.value || "all";

    const deptLabel = department === "all" ? "All Departments" : department;
    const yearLabel = year === "all" ? "All Years" : year;
    if (title) title.textContent = `Supplier Evaluation Score by Semester - ${deptLabel} (${yearLabel})`;

    let url = `/analytics/department/${department}/semester-evaluations`;
    if (year !== "all") url += `?year=${year}`;

    const res = await safeFetch(url);
    const data = await res.json();

    allSemesterData = data || [];
    semesterCurrentPage = 1;

    const labels = allSemesterData.map(item => item.supplier);
    const sem1Values = allSemesterData.map(item => item.sem1_avg !== null ? item.sem1_avg : 0);
    const sem2Values = allSemesterData.map(item => item.sem2_avg !== null ? item.sem2_avg : 0);

    const ctx = canvas.getContext('2d');

    if (window.semesterChartInstance) window.semesterChartInstance.destroy();

    window.semesterChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: '1st Semester (Jan - Jun)',
            data: sem1Values,
            backgroundColor: 'rgba(59, 130, 246, 0.85)',
            borderColor: '#2563eb',
            borderWidth: 1,
          },
          {
            label: '2nd Semester (Jul - Dec)',
            data: sem2Values,
            backgroundColor: 'rgba(245, 158, 11, 0.85)',
            borderColor: '#d97706',
            borderWidth: 1,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true, position: 'top' },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const item = allSemesterData[ctx.dataIndex];
                const isSem1 = ctx.datasetIndex === 0;
                const score = isSem1 ? item.sem1_avg : item.sem2_avg;
                const count = isSem1 ? item.sem1_count : item.sem2_count;
                return score !== null ? `${ctx.dataset.label}: ${score}% (${count} POs)` : `${ctx.dataset.label}: No Evaluations`;
              }
            }
          }
        },
        scales: {
          y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
        }
      }
    });

    renderSemesterTable();
  }

  function renderSemesterTable() {
    if (!tableBody) return;

    const filtered = allSemesterData.filter(item =>
      (item.supplier || '').toLowerCase().includes(semesterSearchQuery)
    );

    if (!filtered || filtered.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500 text-xs">No matching supplier found</td></tr>`;
      if (paginationContainer) paginationContainer.innerHTML = '';
      return;
    }

    const totalPages = Math.ceil(filtered.length / semesterRowsPerPage);
    if (semesterCurrentPage > totalPages) semesterCurrentPage = totalPages || 1;
    if (semesterCurrentPage < 1) semesterCurrentPage = 1;

    const startIndex = (semesterCurrentPage - 1) * semesterRowsPerPage;
    const pageData = filtered.slice(startIndex, startIndex + semesterRowsPerPage);

    let rows = '';
    pageData.forEach(item => {
      const sem1Display = item.sem1_avg !== null 
        ? `<span class="font-medium text-blue-700">${item.sem1_avg}%</span> <span class="text-xs text-gray-500">(${item.sem1_count} POs)</span>`
        : `<span class="text-gray-400 font-normal">N/A</span>`;

      const sem2Display = item.sem2_avg !== null 
        ? `<span class="font-medium text-amber-700">${item.sem2_avg}%</span> <span class="text-xs text-gray-500">(${item.sem2_count} POs)</span>`
        : `<span class="text-gray-400 font-normal">N/A</span>`;

      const overall = item.overall_avg !== null ? `${item.overall_avg}%` : 'N/A';

      let badge = '';
      if (item.overall_avg !== null) {
        if (item.overall_avg >= 90) {
          badge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Outstanding</span>`;
        } else if (item.overall_avg >= 80) {
          badge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Satisfactory</span>`;
        } else if (item.overall_avg >= 75) {
          badge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Fair</span>`;
        } else {
          badge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Needs Improvement</span>`;
        }
      } else {
        badge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">No Rating</span>`;
      }

      let trend = '';
      if (item.sem1_avg !== null && item.sem2_avg !== null) {
        const diff = (item.sem2_avg - item.sem1_avg).toFixed(1);
        if (diff > 0) {
          trend = `<span class="text-xs font-medium text-green-600 ml-2" title="Improved in 2nd Sem">▲ +${diff}%</span>`;
        } else if (diff < 0) {
          trend = `<span class="text-xs font-medium text-red-600 ml-2" title="Declined in 2nd Sem">▼ ${diff}%</span>`;
        } else {
          trend = `<span class="text-xs font-medium text-gray-500 ml-2" title="No change">● 0%</span>`;
        }
      }

      rows += `
        <tr class="hover:bg-gray-50 transition-colors">
          <td class="px-4 py-3 text-sm font-semibold text-gray-800">${item.supplier}</td>
          <td class="px-4 py-3 text-center text-sm">${sem1Display}</td>
          <td class="px-4 py-3 text-center text-sm">${sem2Display}</td>
          <td class="px-4 py-3 text-center text-sm font-bold text-gray-900">${overall}${trend}</td>
          <td class="px-4 py-3 text-center text-sm">${badge}</td>
        </tr>
      `;
    });

    tableBody.innerHTML = rows;
    renderSemesterPagination(filtered.length);
  }

  function renderSemesterPagination(totalItems) {
    if (!paginationContainer) return;
    paginationContainer.innerHTML = '';

    const totalPages = Math.ceil(totalItems / semesterRowsPerPage);
    if (totalPages <= 1) return;

    const createBtn = (label, page, extraClass = '', disabled = false) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.innerHTML = label;
      btn.className = `pagination-btn ${extraClass}`;

      if (disabled) {
        btn.classList.add('disabled');
        btn.disabled = true;
      }

      if (page === semesterCurrentPage) {
        btn.classList.add('active');
      }

      btn.onclick = () => {
        if (page < 1 || page > totalPages || page === semesterCurrentPage) return;
        semesterCurrentPage = page;
        renderSemesterTable();
      };

      return btn;
    };

    // Previous button
    paginationContainer.appendChild(createBtn('‹', semesterCurrentPage - 1, '', semesterCurrentPage === 1));

    // Page numbers
    const maxVisible = 5;
    let start = Math.max(1, semesterCurrentPage - 2);
    let end = Math.min(totalPages, start + maxVisible - 1);
    if (end - start < maxVisible - 1) {
      start = Math.max(1, end - (maxVisible - 1));
    }

    if (start > 1) {
      paginationContainer.appendChild(createBtn(1, 1));
      if (start > 2) {
        const dots = document.createElement('span');
        dots.textContent = '...';
        dots.className = 'px-1 text-gray-400 text-xs';
        paginationContainer.appendChild(dots);
      }
    }

    for (let i = start; i <= end; i++) {
      paginationContainer.appendChild(createBtn(i, i));
    }

    if (end < totalPages) {
      if (end < totalPages - 1) {
        const dots = document.createElement('span');
        dots.textContent = '...';
        dots.className = 'px-1 text-gray-400 text-xs';
        paginationContainer.appendChild(dots);
      }
      paginationContainer.appendChild(createBtn(totalPages, totalPages));
    }

    // Next button
    paginationContainer.appendChild(createBtn('›', semesterCurrentPage + 1, '', semesterCurrentPage === totalPages));
  }

  departmentSelect.addEventListener('change', updateSemesterChart);
  yearSelect.addEventListener('change', updateSemesterChart);

  updateSemesterChart();
}

// DOWNLOAD SEMESTER PDF
function downloadSemesterPdf() {
  const departmentSelect = document.getElementById('departmentSelectSemesterChart');
  const yearSelect = document.getElementById('yearSelectSemesterChart');

  const department = departmentSelect?.value || 'all';
  const year = yearSelect?.value || 'all';

  const params = new URLSearchParams({
    department,
    year
  });

  window.open(`/analytics/semester-evaluations/download?${params.toString()}`, '_blank');
}

// INIT ON PAGE LOAD OR ON FLIP
document.addEventListener('DOMContentLoaded', () => {
  initBarChart();
  initLineChart();
  initSemesterChart();
});
