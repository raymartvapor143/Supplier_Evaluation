<!-- PROFILE MODAL -->
<div id="profileModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center
            opacity-0 pointer-events-none transition-opacity duration-200 ease-out z-[9999]">

    <!-- Modal Card -->
    <div id="profileCard"
         class="relative w-[95%] max-w-md bg-white/95 backdrop-blur-xl
                rounded-3xl shadow-2xl border border-white/40
                transform scale-90 opacity-0
                transition-all duration-200 ease-out
                max-h-[90vh] flex flex-col">

        <!-- Decorative Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-purple-500/10 pointer-events-none rounded-3xl"></div>

        <!-- Header -->
        <div class="p-6 md:p-7 pb-4 shrink-0 relative z-10">

            <!-- Signature Preview Container -->
            <div class="flex justify-center mb-3">
                <div class="relative group">

                    <!-- SIGNATURE CONTAINER -->
                    <div id="signatureContainer"
                         class="bg-white border border-gray-200 rounded-2xl p-3 shadow-sm min-w-[14rem] flex items-center justify-center">

                        @if(auth()->user()->signature)
                            <img id="signaturePreview"
                                 src="{{ route('signature', auth()->user()->id) }}"
                                 alt="Signature"
                                 class="w-56 h-28 object-contain transition duration-300 group-hover:scale-105">
                        @else
                            <div id="noSignaturePlaceholder"
                                 class="w-56 h-28 rounded-xl border-2 border-dashed border-gray-300
                                        flex flex-col items-center justify-center
                                        bg-gray-50 text-gray-400">
                                <i class="ri-quill-pen-line text-3xl mb-2"></i>
                                <span class="text-sm font-medium">
                                    No Signature
                                </span>
                            </div>
                        @endif

                    </div>

                    <!-- DRAW / REDRAW BUTTON -->
                    <button id="redrawBtn"
                            type="button"
                            class="hidden absolute -bottom-3 left-1/2 -translate-x-1/2
                                   px-4 py-2 rounded-full
                                   bg-indigo-600 text-white text-sm font-medium
                                   shadow-lg hover:bg-indigo-700 active:scale-95
                                   transition-all duration-200 whitespace-nowrap z-20">
                        <i class="ri-quill-pen-line mr-1"></i>
                        {{ auth()->user()->signature ? 'Redraw Signature' : 'Draw Signature' }}
                    </button>

                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800">
                Profile Information
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Manage your account details and digital signature
            </p>
        </div>

        <!-- Fields Scroll Area -->
        <div class="px-6 md:px-7 overflow-y-auto flex-1 relative z-10">
            <!-- FORM -->
            <div id="profileFields" class="space-y-4 relative">

                <!-- Name -->
                <div>
                    <label for="name" class="text-sm font-medium text-gray-600 mb-1 block">
                        Full Name
                    </label>
                    <input type="text"
                           id="name"
                           value="{{ auth()->user()->name }}"
                           readonly
                           class="profile-input">
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="text-sm font-medium text-gray-600 mb-1 block">
                        Department
                    </label>
                    <input type="text"
                           id="department"
                           value="{{ auth()->user()->office->name ?? 'N/A' }}"
                           disabled
                           class="profile-input bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>

                <!-- Designation -->
                <div>
                    <label for="designation" class="text-sm font-medium text-gray-600 mb-1 block">
                        Designation
                    </label>
                    <input type="text"
                           id="designation"
                           value="{{ auth()->user()->designation ?? 'N/A' }}"
                           readonly
                           class="profile-input">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="text-sm font-medium text-gray-600 mb-1 block">
                        Email Address
                    </label>
                    <input type="email"
                           id="email"
                           value="{{ auth()->user()->email }}"
                           readonly
                           class="profile-input">
                </div>

                <!-- Status & Role -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="status" class="text-sm font-medium text-gray-600 mb-1 block">
                            Status
                        </label>
                        <input type="text"
                               id="status"
                               value="{{ auth()->user()->status }}"
                               disabled
                               class="profile-input capitalize bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="role" class="text-sm font-medium text-gray-600 mb-1 block">
                            Role
                        </label>
                        <input type="text"
                               id="role"
                               value="{{ auth()->user()->role }}"
                               disabled
                               class="profile-input capitalize bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                @if(auth()->user()->role === 'presentative_staff')
                <!-- Authorization Letter PDF (Presentative Staff Only) -->
                <div id="authorizationLetterContainer" class="p-3.5 rounded-2xl bg-indigo-50/50 border border-indigo-100/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                            <i class="ri-file-pdf-2-line text-red-500 text-base"></i>
                            Authorization Letter
                        </label>
                        
                        <div id="authPdfViewLinkContainer">
                            @if(auth()->user()->authorization_letter)
                                <a id="authPdfViewLink"
                                   href="{{ route('authorization.letter', auth()->user()->authorization_letter_token) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-white px-2.5 py-1 rounded-lg border border-indigo-200 shadow-sm transition hover:shadow">
                                    <i class="ri-external-link-line"></i>
                                    <span>View PDF in new tab</span>
                                </a>
                            @else
                                <span id="authPdfNoneText" class="text-xs text-gray-400 font-medium">No PDF attached</span>
                            @endif
                        </div>
                    </div>

                    <!-- PDF Upload / Change in Edit Mode -->
                    <div id="authPdfUploadSection" class="hidden pt-1">
                        <label for="authorization_letter" class="text-xs text-gray-500 block mb-1">
                            {{ auth()->user()->authorization_letter ? 'Change Authorization PDF (replacing will delete existing file):' : 'Upload Authorization PDF:' }}
                        </label>
                        <div class="relative">
                            <input type="file"
                                   id="authorization_letter"
                                   name="authorization_letter"
                                   accept="application/pdf,.pdf"
                                   class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer bg-white rounded-xl border border-gray-200 p-1">
                        </div>
                        <p id="selectedPdfName" class="text-xs text-indigo-600 mt-1 hidden flex items-center gap-1">
                            <i class="ri-check-line"></i>
                            <span id="selectedPdfText"></span>
                        </p>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="p-6 md:p-7 pt-4 shrink-0 border-t border-gray-200/80 relative z-10">
            <div class="flex flex-wrap items-center justify-between gap-3">

                <button type="button"
                        onclick="openChangePasswordModal()"
                        class="px-4 py-2.5 rounded-xl
                               bg-amber-500 hover:bg-amber-600
                               text-white text-sm font-medium shadow-md hover:shadow-amber-300/40 active:scale-95
                               transition-all duration-200 flex items-center gap-1.5">
                    <i class="ri-key-2-line text-base"></i>
                    <span>Change Password</span>
                </button>

                <div class="flex items-center gap-2">
                    <button id="cancelBtn"
                            type="button"
                            class="hidden px-4 py-2.5 rounded-xl
                                   bg-gray-100 hover:bg-gray-200
                                   text-gray-700 text-sm font-medium active:scale-95
                                   transition-all duration-200">
                        Cancel
                    </button>

                    <button id="editBtn"
                            type="button"
                            class="px-5 py-2.5 rounded-xl
                                   bg-indigo-600 hover:bg-indigo-700
                                   text-white text-sm font-medium shadow-md hover:shadow-indigo-300/40 active:scale-95
                                   transition-all duration-200">
                        <i class="ri-edit-line mr-1"></i>
                        Edit
                    </button>

                    <button type="button"
                            onclick="closeProfileModal()"
                            class="px-4 py-2.5 rounded-xl
                                   bg-gray-500 hover:bg-gray-600
                                   text-white text-sm font-medium shadow-sm active:scale-95
                                   transition-all duration-200">
                        Close
                    </button>

                    <button id="saveBtn"
                            type="button"
                            class="hidden px-5 py-2.5 rounded-xl
                                   bg-emerald-600 hover:bg-emerald-700
                                   text-white text-sm font-medium shadow-md hover:shadow-emerald-300/40 active:scale-95
                                   transition-all duration-200 items-center justify-center">
                        <i class="ri-save-line mr-1"></i>
                        Save
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- STYLES -->
<style>
    .profile-input {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 12px 14px;
        background: #f9fafb;
        transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        color: #374151;
    }

    .profile-input:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }

    .profile-input.editing {
        background: #ffffff;
        border-color: #cbd5e1;
    }
</style>

<!-- SIGNATURE DRAW MODAL -->
<div id="signatureModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm
            flex items-center justify-center
            opacity-0 pointer-events-none
            transition-opacity duration-200 ease-out z-[10000]">

    <div id="signatureCard"
         class="w-[95%] max-w-2xl
                bg-white rounded-3xl shadow-2xl
                p-6 relative
                transform scale-90 opacity-0
                transition-all duration-200 ease-out">

        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Draw Signature
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Use your mouse, stylus, or touch screen to draw your signature
                </p>
            </div>

            <button type="button"
                    onclick="closeSignatureModal()"
                    class="w-10 h-10 rounded-full
                           bg-gray-100 hover:bg-red-100
                           text-gray-500 hover:text-red-500
                           flex items-center justify-center active:scale-95
                           transition-all duration-200">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Signature Canvas Box -->
        <div class="border-2 border-dashed border-gray-300
                    rounded-2xl overflow-hidden bg-white shadow-inner relative">
            <canvas id="signatureCanvas"
                    class="w-full h-[260px] cursor-crosshair block touch-none"
                    style="touch-action: none;"></canvas>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center mt-5">
            <button id="clearSignature"
                    type="button"
                    class="px-5 py-2.5 rounded-xl
                           bg-red-100 text-red-600
                           hover:bg-red-200 active:scale-95
                           transition-all duration-200">
                <i class="ri-delete-bin-line mr-1"></i>
                Clear
            </button>

            <div class="flex gap-3">
                <button type="button"
                        onclick="closeSignatureModal()"
                        class="px-5 py-2.5 rounded-xl
                               bg-gray-100 hover:bg-gray-200
                               text-gray-700 transition-all duration-200 active:scale-95">
                    Cancel
                </button>

                <button id="saveSignatureBtn"
                        type="button"
                        class="px-5 py-2.5 rounded-xl
                               bg-indigo-600 hover:bg-indigo-700
                               text-white shadow-md active:scale-95
                               transition-all duration-200">
                    <i class="ri-check-line mr-1"></i>
                    Save Signature
                </button>
            </div>
        </div>

    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div id="changePasswordModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm
            flex items-center justify-center
            opacity-0 pointer-events-none
            transition-opacity duration-200 ease-out z-[10001]">

    <div id="changePasswordCard"
         class="w-[95%] max-w-md
                bg-white rounded-3xl shadow-2xl
                p-6 relative
                transform scale-90 opacity-0
                transition-all duration-200 ease-out">

        <!-- Decorative Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 via-transparent to-indigo-500/10 pointer-events-none rounded-3xl"></div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-5 relative z-10">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-shield-keyhole-line text-amber-500"></i>
                    Change Password
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Update your password to keep your account secure
                </p>
            </div>

            <button type="button"
                    onclick="closeChangePasswordModal()"
                    class="w-10 h-10 rounded-full
                           bg-gray-100 hover:bg-red-100
                           text-gray-500 hover:text-red-500
                           flex items-center justify-center active:scale-95
                           transition-all duration-200">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Form -->
        <form id="changePasswordForm" class="space-y-4 relative z-10" onsubmit="return false;">
            <!-- Current Password -->
            <div>
                <label for="current_password" class="text-sm font-medium text-gray-600 mb-1 block">
                    Current Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="current_password"
                           placeholder="Enter current password"
                           class="profile-input pr-10">
                    <button type="button"
                            onclick="togglePasswordVisibility('current_password', 'toggleCurrentEye')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none p-1">
                        <i id="toggleCurrentEye" class="ri-eye-off-line text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label for="new_password" class="text-sm font-medium text-gray-600 mb-1 block">
                    New Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="new_password"
                           placeholder="At least 8 characters"
                           class="profile-input pr-10">
                    <button type="button"
                            onclick="togglePasswordVisibility('new_password', 'toggleNewEye')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none p-1">
                        <i id="toggleNewEye" class="ri-eye-off-line text-lg"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Minimum of 8 characters required</p>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="new_password_confirmation" class="text-sm font-medium text-gray-600 mb-1 block">
                    Confirm New Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="new_password_confirmation"
                           placeholder="Re-enter new password"
                           class="profile-input pr-10">
                    <button type="button"
                            onclick="togglePasswordVisibility('new_password_confirmation', 'toggleConfirmEye')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none p-1">
                        <i id="toggleConfirmEye" class="ri-eye-off-line text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button"
                        onclick="closeChangePasswordModal()"
                        class="px-5 py-2.5 rounded-xl
                               bg-gray-100 hover:bg-gray-200
                               text-gray-700 text-sm font-medium active:scale-95
                               transition-all duration-200">
                    Cancel
                </button>

                <button id="savePasswordBtn"
                        type="button"
                        class="px-5 py-2.5 rounded-xl
                               bg-amber-600 hover:bg-amber-700
                               text-white text-sm font-medium shadow-md active:scale-95
                               transition-all duration-200 flex items-center justify-center">
                    <i class="ri-check-line mr-1"></i>
                    Update Password
                </button>
            </div>
        </form>

    </div>
</div>

<!-- SCRIPTS -->
<script>
(function() {
    'use strict';

    let isProfileOpen = false;
    let isSignatureOpen = false;
    let isPasswordModalOpen = false;
    const originalFieldValues = {};
    let signatureBlob = null;
    let activeObjectUrl = null;
    let isSavingProfile = false;
    let isSavingPassword = false;

    // DOM References
    const modalProfile = document.getElementById('profileModal');
    const profileCard = document.getElementById('profileCard');
    const signatureModal = document.getElementById('signatureModal');
    const signatureCard = document.getElementById('signatureCard');
    const changePasswordModal = document.getElementById('changePasswordModal');
    const changePasswordCard = document.getElementById('changePasswordCard');
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas ? canvas.getContext('2d') : null;

    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const redrawBtn = document.getElementById('redrawBtn');
    const clearBtn = document.getElementById('clearSignature');
    const saveSignatureBtn = document.getElementById('saveSignatureBtn');
    const savePasswordBtn = document.getElementById('savePasswordBtn');

    // Helper function for SweetAlert or fallback alert
    function showAlert(icon, title, text) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: '#4f46e5'
            });
        } else {
            alert(`${title}: ${text}`);
        }
    }

    // Body scroll lock manager
    function toggleBodyLock(lock) {
        if (lock) {
            document.body.style.overflow = 'hidden';
        } else {
            if (!isProfileOpen && !isSignatureOpen && !isPasswordModalOpen) {
                document.body.style.overflow = '';
            }
        }
    }

    // Capture initial field values
    function storeOriginalValues() {
        ['name', 'designation', 'email'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                originalFieldValues[id] = el.value;
            }
        });
    }

    // Restore initial field values on cancel
    function restoreOriginalValues() {
        Object.keys(originalFieldValues).forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = originalFieldValues[id];
            }
        });
    }

    /* OPEN PROFILE MODAL */
    window.openProfileModal = function() {
        if (!modalProfile || !profileCard) return;

        storeOriginalValues();
        isProfileOpen = true;
        toggleBodyLock(true);

        modalProfile.classList.remove('opacity-0', 'pointer-events-none');
        modalProfile.classList.add('opacity-100');

        profileCard.classList.remove('scale-90', 'opacity-0');
        profileCard.classList.add('scale-100', 'opacity-100');
    };

    /* CLOSE PROFILE MODAL */
    window.closeProfileModal = function() {
        if (!modalProfile || !profileCard) return;

        if (isSignatureOpen) window.closeSignatureModal();
        if (isPasswordModalOpen) window.closeChangePasswordModal();

        profileCard.classList.remove('scale-100', 'opacity-100');
        profileCard.classList.add('scale-90', 'opacity-0');
        modalProfile.classList.remove('opacity-100');

        setTimeout(() => {
            modalProfile.classList.add('opacity-0', 'pointer-events-none');
            isProfileOpen = false;
            toggleBodyLock(false);
            resetEditState();
        }, 200);
    };

    /* ENTER EDIT MODE */
    function enableEditMode() {
        storeOriginalValues();

        ['name', 'designation', 'email'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.removeAttribute('readonly');
                el.classList.add('editing');
            }
        });

        const authUploadSection = document.getElementById('authPdfUploadSection');
        if (authUploadSection) {
            authUploadSection.classList.remove('hidden');
        }

        if (redrawBtn) redrawBtn.classList.remove('hidden');
        if (editBtn) editBtn.classList.add('hidden');
        if (saveBtn) {
            saveBtn.classList.remove('hidden');
            saveBtn.classList.add('flex');
        }
        if (cancelBtn) cancelBtn.classList.remove('hidden');
    }

    /* RESET EDIT STATE */
    function resetEditState() {
        restoreOriginalValues();

        ['name', 'designation', 'email'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.setAttribute('readonly', 'true');
                el.classList.remove('editing');
            }
        });

        const authUploadSection = document.getElementById('authPdfUploadSection');
        if (authUploadSection) {
            authUploadSection.classList.add('hidden');
        }
        const authFileInput = document.getElementById('authorization_letter');
        if (authFileInput) {
            authFileInput.value = '';
        }
        const selectedPdfName = document.getElementById('selectedPdfName');
        if (selectedPdfName) {
            selectedPdfName.classList.add('hidden');
        }

        if (redrawBtn) redrawBtn.classList.add('hidden');
        if (editBtn) editBtn.classList.remove('hidden');
        if (saveBtn) {
            saveBtn.classList.add('hidden');
            saveBtn.classList.remove('flex');
        }
        if (cancelBtn) cancelBtn.classList.add('hidden');

        signatureBlob = null;
    }

    if (editBtn) editBtn.addEventListener('click', enableEditMode);
    if (cancelBtn) cancelBtn.addEventListener('click', resetEditState);

    const authFileInputEl = document.getElementById('authorization_letter');
    const selectedPdfNameEl = document.getElementById('selectedPdfName');
    const selectedPdfTextEl = document.getElementById('selectedPdfText');

    if (authFileInputEl) {
        authFileInputEl.addEventListener('change', () => {
            if (authFileInputEl.files && authFileInputEl.files[0]) {
                const file = authFileInputEl.files[0];
                if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                    showAlert('warning', 'Invalid File', 'Please select a valid PDF file.');
                    authFileInputEl.value = '';
                    if (selectedPdfNameEl) selectedPdfNameEl.classList.add('hidden');
                    return;
                }
                if (selectedPdfTextEl) selectedPdfTextEl.textContent = `Selected: ${file.name} (${Math.round(file.size / 1024)} KB)`;
                if (selectedPdfNameEl) selectedPdfNameEl.classList.remove('hidden');
            } else {
                if (selectedPdfNameEl) selectedPdfNameEl.classList.add('hidden');
            }
        });
    }

    /* OUTSIDE CLICK TO CLOSE PROFILE MODAL */
    if (modalProfile) {
        modalProfile.addEventListener('click', (e) => {
            if (e.target === modalProfile) {
                window.closeProfileModal();
            }
        });
    }

    /* GLOBAL ESC KEY LISTENER */
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (isPasswordModalOpen) {
                window.closeChangePasswordModal();
            } else if (isSignatureOpen) {
                window.closeSignatureModal();
            } else if (isProfileOpen) {
                window.closeProfileModal();
            }
        }
    });

    /* =========================================================
       SIGNATURE DRAWING & MODAL LOGIC
    ========================================================= */

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    let hasDrawnInCanvas = false;

    window.openSignatureModal = function() {
        if (!signatureModal || !signatureCard) return;

        isSignatureOpen = true;
        toggleBodyLock(true);

        signatureModal.classList.remove('opacity-0', 'pointer-events-none');
        signatureModal.classList.add('opacity-100');

        signatureCard.classList.remove('scale-90', 'opacity-0');
        signatureCard.classList.add('scale-100', 'opacity-100');

        setTimeout(() => {
            resizeCanvas();
        }, 180);
    };

    window.closeSignatureModal = function() {
        if (!signatureModal || !signatureCard) return;

        signatureCard.classList.remove('scale-100', 'opacity-100');
        signatureCard.classList.add('scale-90', 'opacity-0');
        signatureModal.classList.remove('opacity-100');

        setTimeout(() => {
            signatureModal.classList.add('opacity-0', 'pointer-events-none');
            isSignatureOpen = false;
            toggleBodyLock(false);
        }, 200);
    };

    if (redrawBtn) {
        redrawBtn.addEventListener('click', window.openSignatureModal);
    }

    /* RESIZE CANVAS & PRESERVE CONTENT */
    function resizeCanvas() {
        if (!canvas || !ctx || !signatureModal || signatureModal.classList.contains('pointer-events-none')) return;

        let tempCanvas = null;
        if (hasDrawnInCanvas && canvas.width > 0 && canvas.height > 0) {
            tempCanvas = document.createElement('canvas');
            tempCanvas.width = canvas.width;
            tempCanvas.height = canvas.height;
            const tempCtx = tempCanvas.getContext('2d');
            tempCtx.drawImage(canvas, 0, 0);
        }

        const rect = canvas.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return;

        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#111827';

        if (tempCanvas) {
            ctx.drawImage(tempCanvas, 0, 0, tempCanvas.width / ratio, tempCanvas.height / ratio);
        }
    }

    let resizeDebounce;
    window.addEventListener('resize', () => {
        clearTimeout(resizeDebounce);
        resizeDebounce = setTimeout(resizeCanvas, 100);
    });

    function getCoords(e) {
        const rect = canvas.getBoundingClientRect();
        let clientX = e.clientX;
        let clientY = e.clientY;

        if (e.touches && e.touches.length > 0) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }

        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startDrawing(e) {
        if (e.cancelable) e.preventDefault();
        isDrawing = true;
        hasDrawnInCanvas = true;
        const coords = getCoords(e);
        lastX = coords.x;
        lastY = coords.y;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
    }

    function draw(e) {
        if (!isDrawing) return;
        if (e.cancelable) e.preventDefault();

        const coords = getCoords(e);

        const midX = (lastX + coords.x) / 2;
        const midY = (lastY + coords.y) / 2;

        ctx.quadraticCurveTo(lastX, lastY, midX, midY);
        ctx.stroke();

        lastX = coords.x;
        lastY = coords.y;
    }

    function stopDrawing(e) {
        if (!isDrawing) return;
        if (e.cancelable) e.preventDefault();
        isDrawing = false;
        ctx.beginPath();
    }

    if (canvas) {
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing, { passive: false });
        canvas.addEventListener('touchcancel', stopDrawing, { passive: false });
    }

    /* CLEAR CANVAS */
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            if (!canvas || !ctx) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            ctx.clearRect(0, 0, canvas.width / ratio, canvas.height / ratio);
            hasDrawnInCanvas = false;
        });
    }

    /* SAVE SIGNATURE TO PREVIEW */
    if (saveSignatureBtn) {
        saveSignatureBtn.addEventListener('click', () => {
            if (!canvas || !hasDrawnInCanvas) {
                showAlert('warning', 'Empty Signature', 'Please draw a signature before saving.');
                return;
            }

            canvas.toBlob((blob) => {
                if (!blob) return;

                signatureBlob = blob;

                if (activeObjectUrl) {
                    URL.revokeObjectURL(activeObjectUrl);
                }
                activeObjectUrl = URL.createObjectURL(blob);

                let preview = document.getElementById('signaturePreview');
                const placeholder = document.getElementById('noSignaturePlaceholder');

                if (placeholder) {
                    placeholder.remove();
                }

                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'signaturePreview';
                    preview.alt = 'Signature';
                    preview.className = 'w-56 h-28 object-contain transition duration-300 group-hover:scale-105';
                    const container = document.getElementById('signatureContainer');
                    if (container) container.appendChild(preview);
                }

                preview.src = activeObjectUrl;
                window.closeSignatureModal();
            }, 'image/png');
        });
    }

    /* SAVE PROFILE AJAX */
    if (saveBtn) {
        saveBtn.addEventListener('click', async () => {
            if (isSavingProfile) return;

            const nameEl = document.getElementById('name');
            const designationEl = document.getElementById('designation');
            const emailEl = document.getElementById('email');

            const nameVal = nameEl ? nameEl.value.trim() : '';
            const desigVal = designationEl ? designationEl.value.trim() : '';
            const emailVal = emailEl ? emailEl.value.trim() : '';

            if (!nameVal || !desigVal || !emailVal) {
                showAlert('warning', 'Required Fields', 'Name, Designation, and Email cannot be empty.');
                return;
            }

            isSavingProfile = true;
            const originalSaveHtml = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = `<i class="ri-loader-4-line animate-spin mr-1"></i> Saving...`;

            const formData = new FormData();
            formData.append('name', nameVal);
            formData.append('designation', desigVal);
            formData.append('email', emailVal);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            if (signatureBlob) {
                formData.append('signature', signatureBlob, 'signature.png');
            }

            const authFileInput = document.getElementById('authorization_letter');
            if (authFileInput && authFileInput.files && authFileInput.files[0]) {
                const pdfFile = authFileInput.files[0];
                if (pdfFile.type !== 'application/pdf' && !pdfFile.name.toLowerCase().endsWith('.pdf')) {
                    showAlert('warning', 'Invalid File', 'Authorization letter must be a PDF file.');
                    return;
                }
                formData.append('authorization_letter', pdfFile);
            }

            try {
                const response = await fetch('{{ route("user.update", auth()->user()->id) }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    originalFieldValues['name'] = nameVal;
                    originalFieldValues['designation'] = desigVal;
                    originalFieldValues['email'] = emailVal;

                    if (result.authorization_letter_url) {
                        const linkContainer = document.getElementById('authPdfViewLinkContainer');
                        if (linkContainer) {
                            linkContainer.innerHTML = `
                                <a id="authPdfViewLink"
                                   href="${result.authorization_letter_url}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-white px-2.5 py-1 rounded-lg border border-indigo-200 shadow-sm transition hover:shadow">
                                    <i class="ri-external-link-line"></i>
                                    <span>View PDF in new tab</span>
                                </a>
                            `;
                        }
                    }

                    showAlert('success', 'Profile Updated', 'Your profile has been updated successfully.');
                    window.closeProfileModal();
                } else {
                    let errMsg = result.message || 'Failed to update profile.';
                    if (result.errors) {
                        const firstKey = Object.keys(result.errors)[0];
                        if (firstKey && result.errors[firstKey].length > 0) {
                            errMsg = result.errors[firstKey][0];
                        }
                    }
                    showAlert('error', 'Update Failed', errMsg);
                }
            } catch (error) {
                console.error('Profile update error:', error);
                showAlert('error', 'Network Error', 'Unable to process request. Please check your connection.');
            } finally {
                isSavingProfile = false;
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalSaveHtml;
            }
        });
    }

    /* =========================================================
       CHANGE PASSWORD LOGIC
    ========================================================= */

    window.togglePasswordVisibility = function(inputId, eyeIconId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeIconId);
        if (!input || !eye) return;

        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('ri-eye-off-line');
            eye.classList.add('ri-eye-line');
        } else {
            input.type = 'password';
            eye.classList.remove('ri-eye-line');
            eye.classList.add('ri-eye-off-line');
        }
    };

    function resetChangePasswordFields() {
        ['current_password', 'new_password', 'new_password_confirmation'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = '';
                el.type = 'password';
            }
        });

        ['toggleCurrentEye', 'toggleNewEye', 'toggleConfirmEye'].forEach(id => {
            const eye = document.getElementById(id);
            if (eye) {
                eye.classList.remove('ri-eye-line');
                eye.classList.add('ri-eye-off-line');
            }
        });
    }

    window.openChangePasswordModal = function() {
        if (!changePasswordModal || !changePasswordCard) return;

        resetChangePasswordFields();
        isPasswordModalOpen = true;
        toggleBodyLock(true);

        changePasswordModal.classList.remove('opacity-0', 'pointer-events-none');
        changePasswordModal.classList.add('opacity-100');

        changePasswordCard.classList.remove('scale-90', 'opacity-0');
        changePasswordCard.classList.add('scale-100', 'opacity-100');
    };

    window.closeChangePasswordModal = function() {
        if (!changePasswordModal || !changePasswordCard) return;

        changePasswordCard.classList.remove('scale-100', 'opacity-100');
        changePasswordCard.classList.add('scale-90', 'opacity-0');
        changePasswordModal.classList.remove('opacity-100');

        setTimeout(() => {
            changePasswordModal.classList.add('opacity-0', 'pointer-events-none');
            isPasswordModalOpen = false;
            toggleBodyLock(false);
            resetChangePasswordFields();
        }, 200);
    };

    if (changePasswordModal) {
        changePasswordModal.addEventListener('click', (e) => {
            if (e.target === changePasswordModal) {
                window.closeChangePasswordModal();
            }
        });
    }

    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', async () => {
            if (isSavingPassword) return;

            const currentPass = document.getElementById('current_password')?.value || '';
            const newPass = document.getElementById('new_password')?.value || '';
            const confirmPass = document.getElementById('new_password_confirmation')?.value || '';

            if (!currentPass) {
                showAlert('warning', 'Current Password Required', 'Please enter your current password.');
                return;
            }

            if (!newPass) {
                showAlert('warning', 'New Password Required', 'Please enter a new password.');
                return;
            }

            if (newPass.length < 8) {
                showAlert('warning', 'Password Too Short', 'The new password must be at least 8 characters long.');
                return;
            }

            if (newPass !== confirmPass) {
                showAlert('warning', 'Password Mismatch', 'The new password and confirmation password do not match.');
                return;
            }

            if (currentPass === newPass) {
                showAlert('warning', 'Same Password', 'The new password must be different from your current password.');
                return;
            }

            isSavingPassword = true;
            const originalHtml = savePasswordBtn.innerHTML;
            savePasswordBtn.disabled = true;
            savePasswordBtn.innerHTML = `<i class="ri-loader-4-line animate-spin mr-1"></i> Updating...`;

            try {
                const response = await fetch('{{ route("user.change-password") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        current_password: currentPass,
                        new_password: newPass,
                        new_password_confirmation: confirmPass
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showAlert('success', 'Password Updated', 'Your password has been changed successfully.');
                    window.closeChangePasswordModal();
                } else {
                    showAlert('error', 'Update Failed', result.message || 'Could not update password.');
                }
            } catch (error) {
                console.error('Change password error:', error);
                showAlert('error', 'Network Error', 'Unable to process request. Please check your connection.');
            } finally {
                isSavingPassword = false;
                savePasswordBtn.disabled = false;
                savePasswordBtn.innerHTML = originalHtml;
            }
        });
    }

})();
</script>
