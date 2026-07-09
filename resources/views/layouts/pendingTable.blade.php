{{-- <div id="pendingTable" class="tab-content">
 <div class="w-full overflow-x-auto">
  <table class="min-w-full divide-y divide-orange-200">

    <thead class="bg-orange-100/70">
      <tr>



        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purchase Order</th>
        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company Name</th>
        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluator</th>
        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average Score</th>
        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Covered Period</th>
        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluation Date</th>
        <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>

      </tr>
    </thead>

    <tbody class="bg-white/80 divide-y divide-orange-100"></tbody>

  </table>
  <div id="pendingPagination" class="flex justify-center items-center mt-2 space-x-2"></div>
 </div>
</div>

 --}}

 <div id="pendingTable" class="tab-content">

    <div class="overflow-x-auto rounded-lg">

        <table class="min-w-[900px] w-full divide-y divide-orange-200">

            <thead class="bg-orange-100/70 sticky top-0 z-10">
                <tr>
                    <th class="table-head">Purchase Order</th>
                    <th class="table-head">Company Name</th>
                    <th class="table-head">Evaluator</th>
                    <th class="table-head">Average Score</th>
                    <th class="table-head">Covered Period</th>
                    <th class="table-head">Evaluation Date</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>

            <tbody
                id="pendingTableBody"
                class="bg-white/80 divide-y divide-orange-100">
            </tbody>

        </table>

    </div>



<div id="pendingPagination"
     class="flex items-center justify-center gap-1 mt-5 px-2 select-none">
</div>

</div>
<style>

.pagination-btn {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination-btn:hover:not(.active):not(.disabled) {
    background: #fed7aa; /* orange-200 */
    transform: translateY(-1px);
}

.pagination-btn.active {
    background: #f97316; /* orange-500 */
    color: white;
    box-shadow: 0 4px 10px rgba(249,115,22,0.25);
}

.pagination-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}


.table-head {
    padding: 12px 16px;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #6b7280;
    white-space: nowrap;
}

#pendingTable table {
    width: 100%;
    table-layout: auto;
}

#pendingTable tbody tr {
    transition: all 0.2s ease;
    will-change: transform, opacity;
    contain: layout style paint;
}

#pendingTable tbody tr:hover {
    transform: translateY(-1px);
}
</style>
