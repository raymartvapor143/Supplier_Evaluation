<!DOCTYPE html>
<html lang="en">

@include('layouts.header')

<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 min-h-screen text-slate-100 font-sans antialiased">

  @include('layouts.profile')
  @include('layouts.navbar')

  <div class="flex">
    @include('layouts.sidebar')
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    <main id="mainContent" class="flex-1 p-4 sm:p-6 lg:p-8">
      <div class="max-w-7xl mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-800/80 backdrop-blur-md p-6 rounded-2xl border border-slate-700 shadow-xl">
          <div>
            <div class="flex items-center gap-3 mb-1">
              <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center border border-red-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
              </div>
              <h1 class="text-2xl font-extrabold text-white tracking-tight">Threat & File Security Scanner</h1>
            </div>
            <p class="text-slate-400 text-xs sm:text-sm">Scan storage directories for web shells, double extension tricks, and payload injections.</p>
          </div>

          <div class="flex items-center gap-3">
            <button onclick="deleteAllThreats()" id="deleteAllBtn" class="hidden px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-red-600/30 transition items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              <span>Delete All Threats</span>
            </button>

            <button onclick="runThreatScan()" id="scanBtn" class="px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center gap-2">
              <svg id="scanIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <span id="scanBtnText">Run Deep Security Scan</span>
            </button>
          </div>
        </div>

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          
          <div class="bg-slate-800/80 backdrop-blur-md p-5 rounded-2xl border border-slate-700 shadow-md">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Files Scanned</div>
            <div class="text-2xl font-black text-white" id="statTotal">--</div>
            <div class="text-xs text-slate-400 mt-1">Storage & Public Directory</div>
          </div>

          <div class="bg-slate-800/80 backdrop-blur-md p-5 rounded-2xl border border-emerald-500/30 shadow-md">
            <div class="text-xs font-semibold text-emerald-400 uppercase tracking-wider mb-1">Clean & Verified</div>
            <div class="text-2xl font-black text-emerald-400" id="statClean">--</div>
            <div class="text-xs text-emerald-300/70 mt-1">Passed magic byte validation</div>
          </div>

          <div class="bg-slate-800/80 backdrop-blur-md p-5 rounded-2xl border border-red-500/30 shadow-md">
            <div class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1">Threats Detected</div>
            <div class="text-2xl font-black text-red-400" id="statThreats">--</div>
            <div class="text-xs text-red-300/70 mt-1">Suspicious / Web Shell files</div>
          </div>

          <div class="bg-slate-800/80 backdrop-blur-md p-5 rounded-2xl border border-slate-700 shadow-md">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">System Health</div>
            <div class="text-sm font-bold mt-2" id="systemHealthBadge">
              <span class="px-3 py-1 bg-slate-700 text-slate-300 rounded-full text-xs">Scanning...</span>
            </div>
          </div>

        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-slate-800/80 backdrop-blur-md p-4 rounded-2xl border border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="relative w-full sm:w-80">
            <input type="text" id="fileSearch" onkeyup="filterFilesTable()" placeholder="Search scanned filename or path..." 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-900 text-white placeholder-slate-500 border border-slate-700 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4 absolute left-3 top-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto">
            <button onclick="filterStatus('ALL')" class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white transition">All Files</button>
            <button onclick="filterStatus('THREAT')" class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-700 hover:bg-slate-600 text-red-400 transition">Threats Only</button>
            <button onclick="filterStatus('CLEAN')" class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-700 hover:bg-slate-600 text-emerald-400 transition">Clean Files</button>
          </div>
        </div>

        <!-- Scanned Files Table -->
        <div class="bg-slate-800/80 backdrop-blur-md rounded-2xl border border-slate-700 overflow-hidden shadow-xl">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
              <thead class="bg-slate-900/90 text-slate-400 uppercase tracking-wider text-[11px] border-b border-slate-700">
                <tr>
                  <th class="py-3.5 px-4 font-semibold">File Name</th>
                  <th class="py-3.5 px-4 font-semibold">Location Path</th>
                  <th class="py-3.5 px-4 font-semibold">Size</th>
                  <th class="py-3.5 px-4 font-semibold">MIME Type</th>
                  <th class="py-3.5 px-4 font-semibold">Status</th>
                  <th class="py-3.5 px-4 font-semibold">Security Details</th>
                  <th class="py-3.5 px-4 font-semibold text-right">Action</th>
                </tr>
              </thead>
              <tbody id="scanResultsTable" class="divide-y divide-slate-700/60">
                <tr>
                  <td colspan="7" class="py-12 text-center text-slate-400">
                    <div class="inline-block animate-spin w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full mb-2"></div>
                    <div>Scanning system storage... Please wait.</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    let allScannedFiles = [];
    let currentStatusFilter = 'ALL';

    document.addEventListener('DOMContentLoaded', () => {
      runThreatScan();
    });

    async function runThreatScan() {
      const btn = document.getElementById('scanBtn');
      const icon = document.getElementById('scanIcon');
      const text = document.getElementById('scanBtnText');
      const tableBody = document.getElementById('scanResultsTable');

      btn.disabled = true;
      icon.classList.add('animate-spin');
      text.innerText = 'Scanning Storage...';

      try {
        const scanUrl = window.location.origin + window.location.pathname.replace(/\/admin\/threat-scanner.*$/, '') + '/admin/threat-scanner/scan';
        const res = await axios.get(scanUrl);
        const data = res.data;

        if (data.status === 'success') {
          allScannedFiles = data.files || [];

          document.getElementById('statTotal').innerText = data.summary.total_files;
          document.getElementById('statClean').innerText = data.summary.clean_count;
          document.getElementById('statThreats').innerText = data.summary.threat_count;

          const deleteAllBtn = document.getElementById('deleteAllBtn');
          const healthBadge = document.getElementById('systemHealthBadge');
          if (data.summary.threat_count > 0) {
            healthBadge.innerHTML = `<span class="px-3 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded-full text-xs font-bold animate-pulse">⚠️ ${data.summary.threat_count} Threat(s) Found</span>`;
            if (deleteAllBtn) {
              deleteAllBtn.classList.remove('hidden');
              deleteAllBtn.classList.add('flex');
            }
          } else {
            healthBadge.innerHTML = `<span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full text-xs font-bold">✓ System Protected</span>`;
            if (deleteAllBtn) {
              deleteAllBtn.classList.add('hidden');
              deleteAllBtn.classList.remove('flex');
            }
          }

          renderFilesTable();
        }
      } catch (err) {
        console.error('Threat scan error:', err);
        if (err.response && (err.response.status === 401 || err.response.status === 419)) {
          window.location.href = "{{ route('auth.login') }}";
          return;
        }
        const errorMsg = err.response?.data?.message || err.message || 'Failed to run threat scan.';
        tableBody.innerHTML = `
          <tr>
            <td colspan="7" class="py-8 text-center text-red-400 font-semibold">
              Scan Failed: ${escapeHtml(errorMsg)}
            </td>
          </tr>
        `;
      } finally {
        btn.disabled = false;
        icon.classList.remove('animate-spin');
        text.innerText = 'Run Deep Security Scan';
      }
    }

    function renderFilesTable() {
      const tableBody = document.getElementById('scanResultsTable');
      const searchQuery = document.getElementById('fileSearch').value.toLowerCase();

      let filtered = allScannedFiles.filter(file => {
        const matchesStatus = currentStatusFilter === 'ALL' || file.status === currentStatusFilter;
        const matchesSearch = file.filename.toLowerCase().includes(searchQuery) || file.path.toLowerCase().includes(searchQuery);
        return matchesStatus && matchesSearch;
      });

      if (filtered.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="7" class="py-8 text-center text-slate-400">
              No files found matching criteria.
            </td>
          </tr>
        `;
        return;
      }

      tableBody.innerHTML = filtered.map(file => {
        const isThreat = file.status === 'THREAT';
        const badgeClass = isThreat 
          ? 'bg-red-500/20 text-red-400 border-red-500/30 font-bold' 
          : 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
        
        return `
          <tr class="hover:bg-slate-700/40 transition">
            <td class="py-3 px-4 font-mono font-medium text-white flex items-center gap-2">
              <svg class="w-4 h-4 ${isThreat ? 'text-red-400' : 'text-slate-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
              ${escapeHtml(file.filename)}
            </td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-400 max-w-xs truncate">${escapeHtml(file.path)}</td>
            <td class="py-3 px-4">${file.size_formatted}</td>
            <td class="py-3 px-4 font-mono text-[11px] text-slate-400">${escapeHtml(file.mime)}</td>
            <td class="py-3 px-4">
              <span class="px-2.5 py-1 rounded-full border text-[11px] ${badgeClass}">
                ${file.status}
              </span>
            </td>
            <td class="py-3 px-4 ${isThreat ? 'text-red-300 font-semibold' : 'text-slate-400'}">${escapeHtml(file.details)}</td>
            <td class="py-3 px-4 text-right">
              ${isThreat ? `
                <button onclick="deleteFile('${escapeJs(file.path)}')" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs transition flex items-center gap-1 ml-auto">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  Remove Threat
                </button>
              ` : `
                <span class="text-xs text-slate-500 font-medium">Safe</span>
              `}
            </td>
          </tr>
        `;
      }).join('');
    }

    function filterStatus(status) {
      currentStatusFilter = status;
      renderFilesTable();
    }

    function filterFilesTable() {
      renderFilesTable();
    }

    async function deleteFile(filePath) {
      const confirm = await Swal.fire({
        title: 'Delete Threat File?',
        text: `Are you sure you want to permanently remove "${filePath}" from the server?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#475569',
        confirmButtonText: 'Yes, Delete Immediately'
      });

      if (!confirm.isConfirmed) return;

      try {
        const deleteUrl = window.location.origin + window.location.pathname.replace(/\/admin\/threat-scanner.*$/, '') + '/admin/threat-scanner/delete';
        const res = await axios.post(deleteUrl, { file_path: filePath });
        Swal.fire('Deleted!', res.data.message, 'success');
        runThreatScan();
      } catch (err) {
        Swal.fire('Error', err.response?.data?.message || 'Failed to delete threat file.', 'error');
      }
    }

    async function deleteAllThreats() {
      const threatCount = document.getElementById('statThreats').innerText;
      const confirm = await Swal.fire({
        title: 'Delete All Threat Files?',
        text: `Are you sure you want to permanently remove ALL ${threatCount} detected threat file(s) from the server? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#475569',
        confirmButtonText: 'Yes, Delete All Threats'
      });

      if (!confirm.isConfirmed) return;

      try {
        const deleteAllUrl = window.location.origin + window.location.pathname.replace(/\/admin\/threat-scanner.*$/, '') + '/admin/threat-scanner/delete-all';
        const res = await axios.post(deleteAllUrl);
        Swal.fire('Bulk Removal Complete!', res.data.message, 'success');
        runThreatScan();
      } catch (err) {
        Swal.fire('Error', err.response?.data?.message || 'Failed to bulk delete threat files.', 'error');
      }
    }

    function escapeHtml(str) {
      if (!str) return '';
      return str.replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m]);
    }

    function escapeJs(str) {
      if (!str) return '';
      return str.replace(/'/g, "\\'");
    }
  </script>
</body>
</html>
