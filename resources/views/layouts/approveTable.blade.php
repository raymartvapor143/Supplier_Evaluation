            {{-- <div id="approvedTable" class="tab-content hidden">

    <div class="overflow-x-auto rounded-lg">

        <table class="min-w-[900px] w-full divide-y divide-orange-200">
                <thead class="bg-orange-100/70 sticky top-0 z-10">
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
                <tbody class="bg-white/80 divide-y divide-orange-100">
                  <tr
class="table-row hover:bg-orange-50/50 transition-colors duration-200">

                  </tr>
                </tbody>
              </table>
              <div
id="approvedPagination"
class="flex flex-wrap justify-center items-center gap-2 mt-4 px-2">
</div>
            </div>
            </div>

 --}}


 <div id="approvedTable" class="tab-content hidden">

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
                id="approvedTableBody"
                class="bg-white/80 divide-y divide-orange-100">
            </tbody>

        </table>

    </div>



    <div
        id="approvedPagination"
        class="flex flex-wrap justify-center items-center gap-2 mt-4 px-2">
    </div>

</div>

<style>
    .table-head {
    padding: 12px 16px;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #6b7280;
    white-space: nowrap;
}

#approvedTable table {
    width: 100%;
    table-layout: auto;
}

#approvedTable tbody tr {
    transition: all 0.2s ease;
    will-change: transform, opacity;
    contain: layout style paint;
}

#approvedTable tbody tr:hover {
    transform: translateY(-1px);
}
</style>
