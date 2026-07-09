<nav class="bg-gradient-to-r from-orange-400 to-sky-400 shadow-lg sticky top-0 z-50">
  <div class="w-full px-3 sm:px-6 lg:px-8">

    <div class="flex justify-between items-center h-16">

      <!-- LEFT SIDE -->
      <div class="flex items-center space-x-2 sm:space-x-4">

        <!-- Mobile Sidebar Button -->
        <button id="sidebarToggle"
          class="lg:hidden text-white hover:bg-white/20 p-2 rounded-md transition-colors">
          <i class="ri-menu-line text-xl"></i>
        </button>

        <!-- Logo / Title -->
        <div class="flex items-center space-x-2">
         <div class="text-white text-lg sm:text-xl font-['Poppins']">
            SUPPLIER EVALUATION
          </div>

          <!-- Hide on small phones -->
          <div class="text-white text-lg hidden md:block">
            Portal
          </div>
        </div>

      </div>

      <!-- RIGHT SIDE -->
      <div class="flex items-center space-x-2 sm:space-x-4">

<!-- MESSAGE BUTTON -->
<div class="relative">

    <button id="messageBtn"
        class="text-white hover:bg-white/20 p-2 rounded-md transition-colors relative">

        <i class="ri-notification-2-fill text-xl"></i>

        <!-- Notification Count -->
        <span
            class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">

            {{ $messageCount ?? 0 }}

        </span>

    </button>

    <!-- MESSAGE DROPDOWN -->
    <div id="messageDropdown"
        class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-lg shadow-xl border hidden z-50">

        <!-- HEADER -->
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-900">
                Notifications
            </h3>
        </div>

        <!-- MESSAGES -->
        <div class="max-h-80 overflow-y-auto">

            @forelse($messages as $message)

                <div class="p-4 border-b hover:bg-gray-50">

                    <div class="flex items-start gap-3">

                        <!-- ICON -->
                        <div class="
                            @if($message->status == 'approved')
                                bg-green-100 text-green-600
                            @elseif($message->status == 'rejected')
                                bg-red-100 text-red-600
                            @else
                                bg-yellow-100 text-yellow-600
                            @endif
                            p-2 rounded-full
                        ">

                            @if($message->type == 'pdf')
                                <i class="ri-file-pdf-line"></i>
                            @else
                                <i class="ri-notification-3-line"></i>
                            @endif

                        </div>

                        <!-- CONTENT -->
                        <div class="flex-1">

                            <p class="text-sm text-gray-800">

                                <span class="font-semibold">
                                    {{ $message->po_no }}
                                </span>

                                @if($message->type == 'pdf')
                                    PDF has been approved.
                                @else
                                    request status is
                                    <span class="font-semibold capitalize">
                                        {{ $message->status }}
                                    </span>
                                @endif

                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-4 text-center text-gray-500 text-sm">
                    No notifications found.
                </div>

            @endforelse

        </div>

        <!-- FOOTER -->
        <div class="p-3 border-t">

            <a href="#"
                class="w-full block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">

                View all notifications

            </a>

        </div>

    </div>

</div>
        <!-- USER MENU -->
        <div class="relative">

          <button id="userMenuBtn"
            class="flex items-center space-x-2 text-white hover:bg-white/20 px-2 sm:px-3 py-2 rounded-md transition-colors">

            <!-- Avatar -->
<div class="w-8 h-8 bg-white text-orange-500 rounded-full flex items-center justify-center font-semibold">
    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
</div>

            <!-- Hide username on small devices -->
            <span class="hidden md:block text-sm font-medium">
              {{ auth()->user()->role }}
            </span>

            <i class="ri-arrow-down-s-line text-lg"></i>

          </button>

          <!-- USER DROPDOWN -->
          <div id="userDropdown"
            class="absolute right-0 mt-2 w-44 sm:w-48 bg-white rounded-lg shadow-xl border hidden z-50">

            <div class="px-4 py-3 border-b">
              <p class="text-sm font-semibold text-gray-900">
                {{ auth()->user()->name }}
              </p>
              <p class="text-xs text-gray-500">
                {{ auth()->user()->email }}
              </p>
            </div>

<a href="#" onclick="openProfileModal()"
   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
   <i class="ri-user-line mr-2"></i> Profile
</a>

            {{-- <a href="#"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
              <i class="ri-settings-3-line mr-2"></i> Settings
            </a> --}}

            <div class="border-t"></div>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"
        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
        <i class="ri-logout-box-line mr-2"></i> Logout
    </button>
</form>

          </div>

        </div>

      </div>

    </div>

  </div>
</nav>


<script>
const userBtn = document.getElementById("userMenuBtn");
const userDropdown = document.getElementById("userDropdown");

userBtn.addEventListener("click", () => {
  userDropdown.classList.toggle("hidden");
});

document.addEventListener("click", function(e){
  if(!userBtn.contains(e.target) && !userDropdown.contains(e.target)){
    userDropdown.classList.add("hidden");
  }
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const messageBtn = document.getElementById("messageBtn");
    const messageDropdown = document.getElementById("messageDropdown");

    // Toggle dropdown
    messageBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        messageDropdown.classList.toggle("hidden");
    });

    // Close when clicking outside
    document.addEventListener("click", function (e) {
        if (!messageDropdown.contains(e.target) &&
            !messageBtn.contains(e.target)) {
            messageDropdown.classList.add("hidden");
        }
    });

});
</script>
