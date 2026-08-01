<!DOCTYPE html>
<html lang="en">

  @include('layouts.header')











  <body class="bg-gradient-to-br from-orange-400 via-orange-300 to-sky-300 min-h-screen">

    @include('layouts.profile')
    @include('layouts.navbar')
    <div class="flex">
      @include('layouts.sidebar')
      <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>
      <main id="mainContent" class="flex-1 perspective-1000 p-4 sm:p-6 lg:p-8">
        <div id="flipInner" class="relative bg-white rounded-xl shadow-lg p-6 w-full h-full transition-transform duration-700 ease-in-out transform-style-preserve-3d">

          <!-- Front content -->
          <div class="front">
            <div class="mb-6">
              <h1 class="text-2xl font-bold text-gray-900 mb-2">Evaluation Management</h1>
              <p class="text-gray-600 mb-3">Manage and review all evaluation submissions</p>

              <div class="flex flex-wrap justify-start sm:justify-end gap-2" style="margin-top: -60px;"> <!-- Add Evaluation Button --> <button id="openNewEvaluationModalBtn" class="bg-secondary text-white px-4 py-2 rounded-md hover:bg-orange-600 transition-colors text-sm font-medium whitespace-nowrap flex items-center space-x-2">
                  <div class="w-4 h-4 flex items-center justify-center"> <i class="ri-add-line"></i> </div> <span>Evaluation</span>
                </button> </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
              @include('layouts.filter')
            </div>

            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 mb-6">
              <nav class="flex space-x-8">
                <button class="tab-btn active py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="pending">
                  Pending
                  <span id="pending-count" class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
                </button>

                <button class="tab-btn py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="review">
                  Head Review
                  <span id="head-count" class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
                </button>

                <button class="tab-btn py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="approved">
                  Approved/Submitted
                  <span id="approve-count" class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">0</span>
                </button>
              </nav>
            </div>

            <div class="w-full overflow-x-auto bg-orange-200/60 rounded-lg p-3 sm:p-4 lg:p-6">
              @include('layouts.pendingTable')
              @include('layouts.reviewTable')
              @include('layouts.approveTable')
            </div>
          </div>
        </div>
      </main>
    </div>
    <div id="chatWindows" class="fixed bottom-0 right-4 z-50 flex space-x-2">
    </div>







    <script id="tabSwitcher">
      document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn[data-tab]');
        const tabContents = document.querySelectorAll('.tab-content');

        function animateTableRows(targetContent) {
          const rows = targetContent.querySelectorAll('.table-row');
          rows.forEach((row, index) => {
            row.classList.add('opacity-0', 'transform', 'translate-y-4');
            setTimeout(() => {
              row.classList.remove('opacity-0', 'translate-y-4');
              row.classList.add('opacity-100', 'translate-y-0');
            }, index * 100);
          });
        }

        function resetTableRows() {
          const allRows = document.querySelectorAll('.table-row');
          allRows.forEach(row => {
            row.classList.remove('opacity-100', 'translate-y-0');
            row.classList.add('opacity-0', 'translate-y-4');
          });
        }
        tabBtns.forEach(btn => {
          btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            resetTableRows();
            tabBtns.forEach(b => {
              b.classList.remove('active', 'border-primary', 'text-primary');
              b.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            this.classList.add('active', 'border-primary', 'text-primary');
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            tabContents.forEach(content => {
              content.classList.add('hidden');
            });
            const targetContent = document.getElementById(targetTab + 'Table');
            if (targetContent) {
              targetContent.classList.remove('hidden');
            //   setTimeout(() => {
            //     animateTableRows(targetContent);
            //   }, 50);
            }
          });
        });
        tabBtns[0].classList.add('active', 'border-primary', 'text-primary');
        tabBtns[0].classList.remove('border-transparent', 'text-gray-500');
        // setTimeout(() => {
        //   const initialContent = document.getElementById('pendingTable');
        //   if (initialContent) {
        //     animateTableRows(initialContent);
        //   }
        // }, 100);
      });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const pendingCountEl = document.getElementById('pending-count');
    const headCountEl = document.getElementById('head-count');
    const approveCountEl = document.getElementById('approve-count');

    async function updateCounts() {
        try {
            const [pendingRes, headRes, approveRes] = await Promise.all([
                safeFetch('/evaluations/count-pending'),
                safeFetch('/evaluations/count-head'),
                safeFetch('/evaluations/count-approve'),
            ]);

            if (!pendingRes.ok || !headRes.ok || !approveRes.ok) {
                throw new Error('Network response not ok');
            }

            const pendingData = await pendingRes.json();
            const headData = await headRes.json();
            const approveData = await approveRes.json();

            pendingCountEl.textContent = pendingData.pending;
            headCountEl.textContent = headData.head;
            approveCountEl.textContent = approveData.approve; // ✅ correct

        } catch (err) {
            console.error('Error fetching counts:', err);
        }
    }

    // Initial load
    updateCounts();

    // Update every 2 seconds
    // setInterval(updateCounts, 2000);

});
</script>
      @include('layouts.request')
@include('layouts.update')
    @include('layouts.add')

    @include('layouts.viewModal')








  </body>

</html>
