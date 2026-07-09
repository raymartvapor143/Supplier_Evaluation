<div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
  <!-- Search -->
  <div class="relative flex-1">
    <input id="eva-search" type="text" placeholder="Search Evaluation..."
           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
      <i class="ri-search-line"></i>
    </div>
  </div>

  <!-- Start Date -->
  <div class="relative hidden">
    <input id="start-date" type="month"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
    <label for="start-date" class="absolute -top-2 left-2 bg-gray-50 px-1 text-xs text-gray-600">Start Date</label>
  </div>

  <!-- End Date -->
  <div class="relative hidden">
    <input id="end-date" type="month"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
    <label for="end-date" class="absolute -top-2 left-2 bg-gray-50 px-1 text-xs text-gray-600">End Date</label>
  </div>

  <!-- Clear Filter Button -->
  <div>
    <button id="clearFilters"
            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg shadow-sm text-sm transition-all duration-200">
      Clear Filter
    </button>
  </div>
</div>
