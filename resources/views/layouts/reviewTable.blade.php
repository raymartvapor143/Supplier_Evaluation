{{-- <div id="reviewTable" class="tab-content hidden">
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
  <div id="headPagination" class="flex justify-center items-center mt-2 space-x-2"></div>
 </div>
</div> --}}


<div id="reviewTable" class="tab-content hidden">

    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-white">

        <table class="min-w-[900px] w-full divide-y divide-gray-200">

            <thead class="bg-slate-50 sticky top-0 z-10 border-b border-gray-200">
                <tr>
                    <th class="table-head">Purchase Order</th>
                    <th class="table-head text-center">PO PDF</th>
                    <th class="table-head">Company Name</th>
                    <th class="table-head">Evaluator / Office</th>
                    <th class="table-head text-center">Average Score</th>
                    <th class="table-head text-center">Covered Period</th>
                    <th class="table-head text-center">Evaluation Date</th>
                    <th class="table-head text-center">Actions</th>
                </tr>
            </thead>

            <tbody
                id="reviewTableBody"
                class="bg-white divide-y divide-gray-100">
            </tbody>

        </table>

    </div>

    <div
        id="headPagination"
        class="flex flex-wrap justify-center items-center gap-1 mt-4 px-2 select-none">
    </div>

</div>

<style>
.table-head {
    padding: 12px 16px;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #475569;
    white-space: nowrap;
}

#reviewTable tbody tr {
    transition: all 0.15s ease;
}

#reviewTable tbody tr:hover {
    background-color: #f8fafc;
}
</style>

@include('layouts.tablescript')


