<div id="sidebarOverlay"
     class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden">
</div>

{{-- <aside id="sidebar"
       class="fixed lg:sticky top-16 left-0 z-40 w-64 bg-orange-50/95 backdrop-blur-sm shadow-xl border-r border-orange-100
              transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
              h-[calc(100vh-4rem)]">

  <div class="h-full overflow-y-auto flex flex-col">

    <!-- ================= HEADER ================= -->
    <div class="p-4 border-b border-orange-100 flex items-center justify-center">

      <div class="logo-container w-full flex justify-center">

        <h2 id="sidebarTitle" class="logo-zoom">
          <img src="{{asset('logo.png')}}"
               class="h-14 sm:h-16 lg:h-20 w-auto mx-auto object-contain">
        </h2>

        <h2 id="sidebarLogoCollapsed" class="hidden">
          <img src="{{asset('logo.png')}}"
               class="h-10 w-auto mx-auto object-contain">
        </h2>

      </div>
    </div>

    <!-- ================= NAV ================= -->
    <nav class="p-4 space-y-1 flex-1">

      <!-- ACTIVE LINK -->
<!-- Evaluations Dropdown -->
<div class="sidebar-dropdown">

    <button onclick="toggleEvaluationsDropdown()"
        class="sidebar-link w-full flex items-center justify-between px-4 py-3 text-primary
               rounded-xl bg-gradient-to-r from-orange-100 to-orange-200
               border-l-4 border-primary transition-all duration-200 group">

        <div class="flex items-center space-x-3">
            <div class="w-5 h-5 flex items-center justify-center text-primary">
                <i class="ri-file-list-3-line"></i>
            </div>

            <span class="sidebar-text font-medium">
                Evaluations
                <span id="totalEvaluations"
                    class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                    0
                </span>
            </span>
        </div>

        <i id="evaluationsDropdownIcon"
           class="ri-arrow-down-s-line transition-transform duration-200"></i>
    </button>

    <!-- Dropdown Menu -->
    <div id="evaluationsDropdownMenu" class="hidden ml-6 mt-1 space-y-1">

        <!-- Evaluate -->
        <a href="#" id="toggleBack"
           class="flex items-center space-x-3 px-4 py-2 text-gray-600 rounded-lg
                  hover:bg-orange-100 hover:text-primary transition">

            <i class="ri-file-list-3-line"></i>

            <span>
                Evaluate
            </span>
        </a>

        <!-- Bulk Evaluate -->
        <a href="{{ route('bulk.page') }}"
           class="hidden flex items-center space-x-3 px-4 py-2 text-gray-600 rounded-lg
                  hover:bg-orange-100 hover:text-primary transition">

            <i class="ri-stack-line"></i>

            <span>Bulk Evaluate</span>
        </a>

    </div>
</div>
<script>
function toggleEvaluationsDropdown() {
    const menu = document.getElementById('evaluationsDropdownMenu');
    const icon = document.getElementById('evaluationsDropdownIcon');

    menu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>

      @auth
      @if (auth()->user()->isAdmin() || auth()->user()->isEndUser() || auth()->user()->isPGSO())

      <a href="#" onclick="openRequestModal()"
         class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
                hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
                transition-all duration-200 group">

        <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
          <i class="ri-mail-line"></i>
        </div>

        <span class="sidebar-text font-medium">
          Requests
          <span id="requestCount"
                class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
        </span>
      </a>

      <a href="#" onclick="openPOModal_v2()"
         class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
                hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
                transition-all duration-200 group">

        <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
          <i class="ri-file-list-3-line"></i>
        </div>

        <span class="sidebar-text font-medium">
          P.O List
          <span id="POCount"
                class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
        </span>
      </a>

      @endif
      @endauth

      @auth
      @if (auth()->user()->isAdmin())

      <a href="#" onclick="openOffice()"
         class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
                hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
                transition-all duration-200 group">

        <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
          <i class="ri-building-2-line"></i>
        </div>

        <span class="sidebar-text font-medium">
          Offices
          <span id="officeCount"
                class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
        </span>
      </a>

      <a href="#" onclick="openImportOptions()"
         class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
                hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
                transition-all duration-200 group">

        <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
          <i class="ri-upload-2-line"></i>
        </div>

        <span class="sidebar-text font-medium">Import Files</span>
      </a>




      <a href="#" id="toggleAnalytics"
         class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
                hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
                transition-all duration-200 group">

        <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
          <i class="ri-bar-chart-line"></i>
        </div>

        <span class="sidebar-text font-medium">Analytics/Reports</span>
      </a>

      <div class="pt-4 border-t border-gray-100 mt-4">

        <a href="#" onclick="openRecycleBinModal()"
           class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
                  hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
                  transition-all duration-200 group">

          <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
            <i class="ri-delete-bin-line"></i>
          </div>

          <span class="sidebar-text font-medium">
            Recycle Bin
            <span id="deletedItemsCount"
                  class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
          </span>
        </a>

<a href="#"
   onclick="openActivityLogs()"
   class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 rounded-xl
          hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
          transition-all duration-200 group">

    <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
        <i class="ri-history-line"></i>
    </div>

    <span class="sidebar-text font-medium">Activity Logs</span>
</a>

      </div>
      <script>
function toggleUsersDropdown() {
    const menu = document.getElementById('usersDropdownMenu');
    const icon = document.getElementById('usersDropdownIcon');

    menu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
      @endif
      @endauth

      @auth
      @if (auth()->user()->isAdmin())

<!-- Users Dropdown -->
<div class="sidebar-dropdown">
    <button onclick="toggleUsersDropdown()"
        class="sidebar-link w-full flex items-center justify-between px-4 py-3 text-gray-600 rounded-xl
               hover:bg-gradient-to-r hover:from-orange-100 hover:to-orange-200 hover:text-primary
               transition-all duration-200 group">

        <div class="flex items-center space-x-3">
            <div class="w-5 h-5 flex items-center justify-center text-gray-400 group-hover:text-primary">
                <i class="ri-user-line"></i>
            </div>

            <span class="sidebar-text font-medium">
                Users
            </span>
        </div>

        <i id="usersDropdownIcon" class="ri-arrow-down-s-line transition-transform duration-200"></i>
    </button>

    <!-- Dropdown Menu -->
    <div id="usersDropdownMenu" class="hidden ml-6 mt-1 space-y-1">

        <!-- End-User -->
        <a href="#" onclick="openUsersModal()"
            class="flex items-center space-x-3 px-4 py-2 text-gray-600 rounded-lg
                   hover:bg-blue-100 hover:text-blue-600 transition">

            <i class="ri-user-3-line"></i>

            <span>
                End-User
                <span id="pendingUsersCount"
                    class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                    0
                </span>
            </span>
        </a>

        <!-- Authorization -->
        <a href="#" onclick="openAuthorizeUsersModal()"
            class="flex items-center space-x-3 px-4 py-2 text-gray-600 rounded-lg
                   hover:bg-green-100 hover:text-green-600 transition">

            <i class="ri-shield-check-line"></i>

            <span>
                Authorization
            </span>
        </a>

    </div>
</div>

      @endif
      @endauth

    </nav>

    <!-- ================= COLLAPSE BUTTON ================= -->
    <div class="flex justify-center pb-4">

      <button id="sidebarCollapseBtn"
              class="hidden lg:flex items-center justify-center w-10 h-10 rounded-full
                     bg-gradient-to-r from-orange-400 to-sky-400 shadow-lg
                     hover:scale-105 transition-all duration-200">

        <i class="ri-menu-fold-line text-white text-lg"></i>

      </button>

    </div>

  </div>
</aside> --}}

<aside id="sidebar"
  class="fixed lg:sticky top-16 left-0 z-40 w-64 bg-orange-50/95 backdrop-blur-sm
         shadow-xl border-r border-orange-100
         transform -translate-x-full lg:translate-x-0
         transition-all duration-300 ease-in-out
         h-[calc(100vh-4rem)]">

  <div class="h-full flex flex-col">

    <!-- ================= HEADER ================= -->
    <div class="p-4 border-b border-orange-100 flex justify-center">
      <img id="sidebarLogoFull" src="{{asset('logo.png')}}" class="h-16 lg:h-20 object-contain">
      <img id="sidebarLogoSmall" src="{{asset('logo.png')}}" class="h-10 hidden object-contain">
    </div>

    <!-- ================= NAV ================= -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 flex flex-col gap-1">

      <!-- ================= EVALUATIONS ================= -->
      <div>
        <button onclick="toggleDropdown('evaluations')"
          class="sidebar-link flex items-center justify-between w-full px-4 py-3 rounded-xl
                 bg-orange-100 text-primary">

          <div class="flex items-center gap-3">
            <i class="ri-file-list-3-line"></i>
            <span class="sidebar-text font-medium">
              Evaluations
              <span id="totalEvaluations" class="badge">0</span>
            </span>
          </div>

          <i id="evaluationsIcon" class="ri-arrow-down-s-line transition-transform"></i>
        </button>

        <div id="evaluationsMenu" class="hidden ml-6 mt-1 flex flex-col gap-1">
          <a href="javascript:void(0)" id="toggleBack" onclick="navigateOrRun('toggleBack', 'evaluate')" class="sub-link">Evaluate</a>
          <a href="{{ route('bulk.page') }}" class="sub-link">Bulk Evaluate</a>
        </div>
      </div>

      @if(auth()->user()->isAdmin() || auth()->user()->isEndUser())
      <!-- ================= REQUESTS ================= -->
      <a href="javascript:void(0)"
         onclick="navigateOrRun('openRequestModal')"
         class="sidebar-link">

        <i class="ri-mail-line"></i>
        <span class="sidebar-text">
          Requests <span id="requestCount" class="badge">0</span>
        </span>
      </a>

      <!-- ================= P.O ================= -->
      <a href="javascript:void(0)" onclick="navigateOrRun('openPOModal_v2')" class="sidebar-link">
        <i class="ri-file-list-3-line"></i>
        <span class="sidebar-text">
          P.O List <span id="POCount" class="badge">0</span>
        </span>
      </a>
      @endif

      @if(auth()->user()->isAdmin())

      <!-- ================= ADMIN SECTION ================= -->
      <div class="mt-3 pt-3 border-t border-orange-100 flex flex-col gap-1">

        <a href="javascript:void(0)" onclick="navigateOrRun('openOffice')" class="sidebar-link">
          <i class="ri-building-2-line"></i>
          <span class="sidebar-text">
            Offices <span id="officeCount" class="badge">0</span>
          </span>
        </a>

        <a href="javascript:void(0)" onclick="navigateOrRun('openImportOptions')" class="sidebar-link">
          <i class="ri-upload-2-line"></i>
          <span class="sidebar-text">Import Files</span>
        </a>

        <a href="javascript:void(0)" id="toggleAnalytics" onclick="navigateOrRun('toggleAnalytics', 'analytics')" class="sidebar-link">
          <i class="ri-bar-chart-line"></i>
          <span class="sidebar-text">Analytics</span>
        </a>

        <a href="javascript:void(0)" onclick="navigateOrRun('openRecycleBinModal')" class="sidebar-link">
          <i class="ri-delete-bin-line"></i>
          <span class="sidebar-text">
            Recycle Bin <span id="deletedItemsCount" class="badge badge-red">0</span>
          </span>
        </a>

        <a href="javascript:void(0)" onclick="navigateOrRun('openActivityLogs')" class="sidebar-link">
          <i class="ri-history-line"></i>
          <span class="sidebar-text">Activity Logs</span>
        </a>

        <a href="{{ route('admin.threat_scanner') }}" class="sidebar-link text-red-600 font-semibold hover:bg-red-50">
          <i class="ri-shield-keyhole-line text-red-600"></i>
          <span class="sidebar-text">Threat Scanner</span>
        </a>
      </div>

      <!-- ================= USERS ================= -->
      <div>
        <button onclick="toggleDropdown('users')" class="sidebar-link justify-between w-full">
          <div class="flex items-center gap-3">
            <i class="ri-user-line"></i>
            <span class="sidebar-text">Users</span>
          </div>
          <i id="usersIcon" class="ri-arrow-down-s-line"></i>
        </button>

        <div id="usersMenu" class="hidden ml-6 mt-1 flex flex-col gap-1">

          <a href="javascript:void(0)" onclick="navigateOrRun('openUsersModal')" class="sub-link">
            End Users <span id="pendingUsersCount" class="badge">0</span>
          </a>

          <a href="javascript:void(0)" onclick="navigateOrRun('openAuthorizeUsersModal')" class="sub-link">
            Authorization
          </a>
        </div>
      </div>

      @endif

    </nav>

    <!-- ================= COLLAPSE BUTTON ================= -->
    <div class="p-3 flex justify-center border-t border-orange-100">
      <button id="sidebarCollapseBtn"
        class="w-10 h-10 rounded-full bg-gradient-to-r from-orange-400 to-sky-400 text-white">
        <i class="ri-menu-fold-line"></i>
      </button>
    </div>

  </div>
</aside>

<style>
.sidebar-link {
  display:flex;
  align-items:center;
  gap:0.75rem;
  padding:0.75rem 1rem;
  border-radius:0.75rem;
  color:#4b5563;
  transition:all 0.2s;
}

.sidebar-link:hover {
  background:#ffedd5;
  color:#f97316;
}

.sub-link {
  display:flex;
  align-items:center;
  gap:0.75rem;
  padding:0.5rem 1rem;
  border-radius:0.5rem;
  color:#4b5563;
  transition:all 0.2s;
}

.sub-link:hover {
  background:#ffedd5;
  color:#f97316;
}

.badge {
  margin-left:0.5rem;
  font-size:0.75rem;
  padding:0.15rem 0.5rem;
  border-radius:9999px;
  background:#dbeafe;
  color:#1e40af;
}

.badge-red {
  background:#fee2e2;
  color:#991b1b;
}
</style>

<script>
window.navigateOrRun = function(actionFn, actionName) {
  const isDashboard = window.location.pathname.includes('admin-dashboard') || window.location.pathname === '/';

  if (actionFn === 'toggleBack' || actionName === 'evaluate') {
    if (isDashboard) {
      const flipInner = document.getElementById('flipInner');
      const backSide = flipInner ? flipInner.querySelector('.back') : null;
      if (flipInner) flipInner.style.transform = 'rotateY(0deg)';
      if (backSide) setTimeout(() => backSide.classList.add('hidden'), 700);
    } else {
      window.location.href = "{{ route('admin.dashboard') }}?action=evaluate";
    }
    return;
  }

  if (actionFn === 'toggleAnalytics' || actionName === 'analytics') {
    if (isDashboard) {
      const flipInner = document.getElementById('flipInner');
      const backSide = flipInner ? flipInner.querySelector('.back') : null;
      if (backSide) backSide.classList.remove('hidden');
      if (flipInner) flipInner.style.transform = 'rotateY(180deg)';
      setTimeout(() => {
        if (typeof initBarChart === 'function') initBarChart();
        if (typeof initLineChart === 'function') initLineChart();
        if (typeof initSemesterChart === 'function') initSemesterChart();
      }, 300);
    } else {
      window.location.href = "{{ route('admin.dashboard') }}?action=analytics";
    }
    return;
  }

  if (typeof window[actionFn] === 'function') {
    window[actionFn]();
  } else {
    const dashboardUrl = "{{ route('admin.dashboard') }}";
    window.location.href = dashboardUrl + '?action=' + (actionName || actionFn);
  }
};

document.addEventListener('DOMContentLoaded', () => {

  const urlParams = new URLSearchParams(window.location.search);
  const actionParam = urlParams.get('action');
  if (actionParam) {
    setTimeout(() => {
      if (actionParam === 'analytics') {
        window.navigateOrRun('toggleAnalytics', 'analytics');
      } else if (actionParam === 'evaluate') {
        window.navigateOrRun('toggleBack', 'evaluate');
      } else if (typeof window[actionParam] === 'function') {
        window[actionParam]();
      } else {
        const el = document.getElementById(actionParam);
        if (el) el.click();
      }
    }, 400);
  }

  const sidebar = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');

  const links = document.querySelectorAll('.sidebar-link');
  const sidebarTexts = document.querySelectorAll('.sidebar-text');

  let isCollapsed = false;

  const activeClasses = [
    "text-primary",
    "bg-gradient-to-r",
    "from-orange-100",
    "to-orange-200",
    "border-l-4",
    "border-primary"
  ];

  const inactiveClasses = [
    "text-gray-600",
    "hover:bg-gradient-to-r",
    "hover:from-orange-100",
    "hover:to-orange-200",
    "hover:text-primary"
  ];

  /* =========================
     ACTIVE LINK HANDLER
  ========================== */
const nav = document.querySelector('nav');

if (nav) {
  nav.addEventListener('click', (e) => {

    const link = e.target.closest('.sidebar-link');
    if (!link) return;

    if (link.getAttribute('href') === '#') e.preventDefault();

    document.querySelectorAll('.sidebar-link').forEach(l => {

      l.classList.remove(
        'bg-orange-100',
        'text-primary',
        'border-l-4',
        'border-primary'
      );

      l.classList.add('text-gray-600');

      const icon = l.querySelector('i');
      if (icon) icon.classList.remove('text-primary');
    });

    link.classList.add(
      'bg-orange-100',
      'text-primary',
      'border-l-4',
      'border-primary'
    );

    const icon = link.querySelector('i');
    if (icon) icon.classList.add('text-primary');
  });
}

  /* =========================
     MOBILE SIDEBAR TOGGLE
  ========================== */
window.toggleSidebar = function () {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  sidebar?.classList.toggle('-translate-x-full');
  overlay?.classList.toggle('hidden');
};

window.toggleDropdown = function (type) {

  const menus = ['evaluations', 'users'];

  menus.forEach(name => {
    if (name !== type) {
      document.getElementById(name + 'Menu')?.classList.add('hidden');
      document.getElementById(name + 'Icon')?.classList.remove('rotate-180');
    }
  });

  const menu = document.getElementById(type + 'Menu');
  const icon = document.getElementById(type + 'Icon');

  menu?.classList.toggle('hidden');
  icon?.classList.toggle('rotate-180');
};


  /* =========================
     COLLAPSE SIDEBAR (DESKTOP)
  ========================== */
function toggleSidebarCollapse() {

  isCollapsed = !isCollapsed;

  const sidebar = document.getElementById('sidebar');
  const texts = document.querySelectorAll('.sidebar-text');

  const links = document.querySelectorAll('.sidebar-link');

  if (isCollapsed) {

    sidebar.classList.replace('w-64', 'w-20');

    texts.forEach(t => t.classList.add('hidden'));

    links.forEach(l => {
      l.classList.add('justify-center');
      l.classList.remove('gap-3');
    });

  } else {

    sidebar.classList.replace('w-20', 'w-64');

    texts.forEach(t => t.classList.remove('hidden'));

    links.forEach(l => {
      l.classList.remove('justify-center');
      l.classList.add('gap-3');
    });
  }

  const btn = document.getElementById('sidebarCollapseBtn');
  if (btn) {
    btn.innerHTML = isCollapsed
      ? '<i class="ri-menu-unfold-line"></i>'
      : '<i class="ri-menu-fold-line"></i>';
  }
}

  /* =========================
     EVENT BINDINGS
  ========================== */
  sidebarToggle?.addEventListener('click', toggleSidebar);
  sidebarCollapseBtn?.addEventListener('click', toggleSidebarCollapse);
  sidebarOverlay?.addEventListener('click', toggleSidebar);

  /* =========================
     LOAD COUNTS
  ========================== */
  loadSidebarCounts();

});

/* =========================
   API COUNTS
========================== */
async function loadSidebarCounts() {

  try {

    const res = await fetch('/sidebar-counts', {
      headers: { 'Accept': 'application/json' }
    });

    if (!res.ok) throw new Error('Failed to fetch sidebar counts');

    const data = await res.json();

    const setText = (id, value) => {
      const el = document.getElementById(id);
      if (el) el.innerText = value ?? 0;
    };

    setText('totalEvaluations', data.evaluations);
    setText('requestCount', data.requests);
    setText('POCount', data.po);
    setText('pendingUsersCount', data.users);
    setText('officeCount', data.offices);
    setText('authorizeItemsCount', data.pdfs);

  } catch (err) {

    console.error('Sidebar count error:', err);

    [
      'totalEvaluations',
      'requestCount',
      'POCount',
      'pendingUsersCount',
      'officeCount',
      'authorizeItemsCount'
    ].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.innerText = 0;
    });
  }
}
</script>
