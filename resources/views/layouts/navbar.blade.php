<nav class="bg-gradient-to-r from-orange-400 to-sky-400 shadow-lg sticky top-0 z-50">
  <div class="w-full px-3 sm:px-6 lg:px-8">

    <div class="flex justify-between items-center h-16">

      <!-- LEFT SIDE -->
      <div class="flex items-center space-x-2 sm:space-x-4">

        <!-- Mobile Sidebar Button -->
        <button id="sidebarToggle"
          type="button"
          class="lg:hidden text-white hover:bg-white/20 p-2 rounded-md transition-colors active:scale-95">
          <i class="ri-menu-line text-xl"></i>
        </button>

        <!-- Logo / Title -->
        <div class="flex items-center space-x-2">
          <div class="text-white text-lg sm:text-xl font-['Poppins'] tracking-wide font-semibold">
            SUPPLIER EVALUATION
          </div>

          <!-- Hide on small phones -->
          <div class="text-white text-lg hidden md:block font-light">
            Portal
          </div>
        </div>

      </div>

      <!-- RIGHT SIDE -->
      <div class="flex items-center space-x-2 sm:space-x-4">

        <!-- NOTIFICATION / MESSAGE BUTTON -->
        <div class="relative">

          <button id="messageBtn"
              type="button"
              class="text-white hover:bg-white/20 p-2 rounded-md transition-colors relative active:scale-95 focus:outline-none"
              title="Notifications">

              <i class="ri-notification-2-fill text-xl"></i>

              <!-- Notification Badge -->
              @if(!empty($messageCount) && $messageCount > 0)
                <span id="notifBadge"
                      class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse shadow">
                    {{ $messageCount }}
                </span>
              @endif

          </button>

          <!-- NOTIFICATION DROPDOWN -->
          <div id="messageDropdown"
              class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 pointer-events-none transform scale-95 transition-all duration-200 ease-out z-50 overflow-hidden">

              <!-- HEADER -->
              <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                  <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                      <i class="ri-notification-3-line text-indigo-500"></i>
                      Notifications
                  </h3>
                  @if(!empty($messageCount) && $messageCount > 0)
                      <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-medium">
                          {{ $messageCount }} New
                      </span>
                  @endif
              </div>

              <!-- MESSAGES CONTAINER -->
              <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">

                  @forelse($messages ?? [] as $message)
                       @php
                           $msgStatus = strtolower($message->status ?? '');
                           $isDisabledStatus = in_array($msgStatus, ['done', 'cancelled', 'canceled', 'rejected']);
                           $isClickable = !empty($message->evaluation_id) && !$isDisabledStatus;
                       @endphp

                       <div class="p-3.5 transition-colors @if($isClickable) cursor-pointer hover:bg-indigo-50/80 group @else bg-gray-50/40 opacity-75 @endif"
                            @if($isClickable) onclick="handleNotificationClick('{{ $message->type ?? 'request' }}', '{{ $message->status ?? '' }}', {{ $message->evaluation_id }})" @endif>
                           <div class="flex items-start gap-3">

                               <!-- STATUS ICON -->
                               <div class="p-2 rounded-xl shrink-0
                                   @if($message->status == 'approved')
                                       bg-emerald-100 text-emerald-600
                                   @elseif($message->status == 'rejected' || $message->status == 'cancelled' || $message->status == 'canceled')
                                       bg-rose-100 text-rose-600
                                   @else
                                       bg-amber-100 text-amber-600
                                   @endif
                               ">
                                   @if(($message->type ?? '') == 'pdf')
                                       <i class="ri-file-pdf-line text-lg"></i>
                                   @else
                                       <i class="ri-notification-3-line text-lg"></i>
                                   @endif
                               </div>

                               <!-- CONTENT -->
                               <div class="flex-1 min-w-0">
                                   <p class="text-sm text-gray-800 leading-snug">
                                       <span class="font-semibold text-gray-900">
                                           {{ $message->po_no ?? 'PO' }}
                                       </span>
                                       @if(($message->type ?? '') == 'pdf')
                                           PDF has been approved.
                                       @else
                                           request status is
                                           <span class="font-semibold capitalize
                                               @if($message->status == 'approved') text-emerald-600
                                               @elseif($message->status == 'rejected' || $message->status == 'cancelled' || $message->status == 'canceled') text-rose-600
                                               @else text-amber-600
                                               @endif
                                           ">
                                               {{ $message->status }}
                                           </span>
                                       @endif
                                   </p>

                                   <div class="flex items-center justify-between mt-1">
                                       <p class="text-[11px] text-gray-400 flex items-center gap-1">
                                           <i class="ri-time-line text-xs"></i>
                                           {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                                       </p>

                                       @if($isClickable)
                                           <span class="text-xs text-indigo-600 font-medium opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-0.5">
                                               Open <i class="ri-arrow-right-line"></i>
                                           </span>
                                       @elseif($isDisabledStatus)
                                           <span class="text-[10px] font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded capitalize">
                                               {{ $message->status }}
                                           </span>
                                       @endif
                                   </div>
                               </div>

                           </div>
                       </div>

                  @empty

                      <div class="p-6 text-center text-gray-400 text-sm flex flex-col items-center justify-center gap-2">
                          <i class="ri-notification-off-line text-3xl text-gray-300"></i>
                          <span>No notifications found</span>
                      </div>

                  @endforelse

              </div>

              <!-- FOOTER -->
              <div class="p-3 border-t border-gray-100 bg-gray-50/50 text-center">
                  <span class="text-xs text-gray-400 font-medium">
                      Click approved notifications to open evaluation
                  </span>
              </div>

          </div>

        </div>

        <!-- USER MENU -->
        <div class="relative">

          <button id="userMenuBtn"
            type="button"
            class="flex items-center space-x-2 text-white hover:bg-white/20 px-2 sm:px-3 py-2 rounded-md transition-colors active:scale-95 focus:outline-none">

            <!-- Avatar -->
            <div class="w-8 h-8 bg-white text-orange-500 rounded-full flex items-center justify-center font-bold shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <!-- Hide username on small devices -->
            <span class="hidden md:block text-sm font-medium capitalize">
              {{ auth()->user()->role_display_name }}
            </span>

            <i class="ri-arrow-down-s-line text-lg"></i>

          </button>

          <!-- USER DROPDOWN -->
          <div id="userDropdown"
            class="absolute right-0 mt-2 w-48 sm:w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 pointer-events-none transform scale-95 transition-all duration-200 ease-out z-50 overflow-hidden">

            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
              <p class="text-sm font-semibold text-gray-900 truncate">
                {{ auth()->user()->name }}
              </p>
              <p class="text-xs text-gray-500 truncate">
                {{ auth()->user()->email }}
              </p>
            </div>

            <div class="py-1">
              <a href="#" onclick="openProfileModal(); return false;"
                 class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                 <i class="ri-user-line mr-2.5 text-indigo-500"></i> Profile
              </a>

              <a href="#" onclick="openChangePasswordModal(); return false;"
                 class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                 <i class="ri-key-2-line mr-2.5 text-amber-500"></i> Change Password
              </a>
            </div>

            <div class="border-t border-gray-100"></div>

            <div class="py-1">
              <form action="{{ route('logout') }}" method="POST" onsubmit="if(typeof showGlobalLoading === 'function') showGlobalLoading('Logging Out...', 'Ending session, please wait');">
                  @csrf
                  <button type="submit"
                      class="flex items-center w-full px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition-colors">
                      <i class="ri-logout-box-line mr-2.5"></i> Logout
                  </button>
              </form>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>
</nav>

<!-- NAVBAR DROPDOWN & TOAST SCRIPTS -->
<script>
(function() {
    'use strict';

    let isProcessingNotification = false;

    window.handleNotificationClick = async function(type, status, evaluationId) {
        if (isProcessingNotification) return;

        const normalizedStatus = (status || '').toLowerCase();
        if (['done', 'cancelled', 'canceled', 'rejected'].includes(normalizedStatus)) {
            return;
        }

        const messageDropdown = document.getElementById("messageDropdown");
        if (messageDropdown) {
            messageDropdown.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
            messageDropdown.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        }

        if (!evaluationId) return;

        isProcessingNotification = true;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Processing Evaluation',
                text: 'Fetching evaluation details, please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        } else if (typeof window.showToast === 'function') {
            window.showToast('Processing evaluation...', 'info', 2000);
        }

        try {
            const role = (window.authUser && window.authUser.role) ? window.authUser.role : (window.userRole || '');
            const isAdmin = ['administrator', 'admin', 'pgso'].includes(role);

            if (typeof window.updateEvaluation === 'function' && (isAdmin || status === 'approved' || type === 'request')) {
                await window.updateEvaluation(evaluationId);
            } else if (typeof window.viewEvaluation === 'function') {
                await window.viewEvaluation(evaluationId);
            } else {
                window.location.href = `/?open_evaluation=${evaluationId}`;
            }
        } catch (error) {
            console.error('Error opening evaluation:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not load evaluation data.'
                });
            }
        } finally {
            isProcessingNotification = false;
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        const messageBtn = document.getElementById("messageBtn");
        const messageDropdown = document.getElementById("messageDropdown");
        const userBtn = document.getElementById("userMenuBtn");
        const userDropdown = document.getElementById("userDropdown");

        // Check query param for auto-launching evaluation update
        const urlParams = new URLSearchParams(window.location.search);
        const evalId = urlParams.get('open_evaluation');
        if (evalId && typeof window.updateEvaluation === 'function') {
            const cleanUrl = window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
            setTimeout(() => {
                window.updateEvaluation(evalId);
            }, 300);
        }

        function openMenu(menu) {
            if (!menu) return;
            menu.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            menu.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
        }

        function closeMenu(menu) {
            if (!menu) return;
            menu.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
            menu.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        }

        function isMenuOpen(menu) {
            return menu && menu.classList.contains('opacity-100');
        }

        // Toggle Notification Dropdown
        if (messageBtn && messageDropdown) {
            messageBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                if (isMenuOpen(messageDropdown)) {
                    closeMenu(messageDropdown);
                } else {
                    closeMenu(userDropdown); // Close user menu
                    openMenu(messageDropdown);
                }
            });
        }

        // Toggle User Menu Dropdown
        if (userBtn && userDropdown) {
            userBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                if (isMenuOpen(userDropdown)) {
                    closeMenu(userDropdown);
                } else {
                    closeMenu(messageDropdown); // Close notification menu
                    openMenu(userDropdown);
                }
            });
        }

        // Unified Outside Click Listener
        document.addEventListener("click", function (e) {
            if (messageDropdown && messageBtn &&
                !messageDropdown.contains(e.target) &&
                !messageBtn.contains(e.target)) {
                closeMenu(messageDropdown);
            }

            if (userDropdown && userBtn &&
                !userDropdown.contains(e.target) &&
                !userBtn.contains(e.target)) {
                closeMenu(userDropdown);
            }
        });
    });

    /* =========================================================
       GLOBAL APP TOAST NOTIFICATION SYSTEM
    ========================================================= */
    window.showToast = function(message, type = 'info', duration = 3500) {
        let container = document.getElementById('globalToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'globalToastContainer';
            container.className = 'fixed bottom-5 right-5 z-[10005] flex flex-col gap-2.5 pointer-events-none max-w-sm w-full px-4';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        let bgClass = 'bg-gray-900 text-white';
        let iconClass = 'ri-information-line';

        if (type === 'success') {
            bgClass = 'bg-emerald-600 text-white';
            iconClass = 'ri-checkbox-circle-line';
        } else if (type === 'error' || type === 'danger') {
            bgClass = 'bg-rose-600 text-white';
            iconClass = 'ri-error-warning-line';
        } else if (type === 'warning') {
            bgClass = 'bg-amber-500 text-white';
            iconClass = 'ri-alert-line';
        }

        toast.className = `${bgClass} p-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-medium transform translate-y-4 opacity-0 transition-all duration-300 pointer-events-auto border border-white/20`;
        toast.innerHTML = `<i class="${iconClass} text-xl shrink-0"></i><span class="flex-1 leading-snug">${message}</span>`;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    };

})();
</script>
