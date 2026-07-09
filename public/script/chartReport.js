
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
async function loadDepartments(selectElement) {
  if (!selectElement || selectElement.dataset.loaded) return;

  const res = await safeFetch('/analytics/departments');
  const departments = await res.json();

  selectElement.innerHTML = '<option disabled selected>Select Department</option>';
  departments.forEach(dep => {
    const opt = document.createElement('option');
    opt.value = dep;
    opt.textContent = dep;
    selectElement.appendChild(opt);
  });

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
  await loadDepartments(departmentSelect);

  // Populate years
  const currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '<option value="all">All Years</option>';
  for (let i = currentYear; i >= 2023; i--) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    yearSelect.appendChild(opt);
  }

  // Defaults
  const defaultDepartment = departmentSelect.options[1]?.value;
  if (defaultDepartment) departmentSelect.value = defaultDepartment;
  yearSelect.value = "all";

  // Chart update function
  async function updateBarChart() {
    const department = departmentSelect.value;
    const year = yearSelect.value;

    if (!department || !year) return;

    const yearLabel = year === "all" ? "All Years" : year;
    if (title) title.textContent = `Supplier Scores - ${department} (${yearLabel})`;

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
          backgroundColor: values.map((v, i) => v < 60 ? '#EF4444' : `hsl(${i * 360 / values.length}, 70%, 50%)`)
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

  await loadDepartments(departmentSelect);

  const currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '<option value="all">All Years</option>';

  for (let i = currentYear; i >= 2023; i--) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    yearSelect.appendChild(opt);
  }

  const defaultDepartment = departmentSelect.options[1]?.value;
  if (defaultDepartment) departmentSelect.value = defaultDepartment;

  yearSelect.value = "all";

  function updateChart() {
    const department = departmentSelect.value;
    const year = yearSelect.value;

    if (!department || !year) return;

    const yearLabel = year === "all" ? "All Years" : year;

    if (title) {
      title.textContent = `Monthly Evaluation Count - ${department} (${yearLabel})`;
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
    //Fix: only add year param if not "all"
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

    // Fix: label display
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

// INIT ON PAGE LOAD OR ON FLIP
document.addEventListener('DOMContentLoaded', () => {
  initBarChart();
  initLineChart();
});
