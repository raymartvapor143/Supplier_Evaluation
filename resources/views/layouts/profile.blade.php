<!-- PROFILE MODAL -->
<div id="profileModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center
            opacity-0 pointer-events-none transition-all duration-300 z-[9999]">

    <!-- Modal Card -->
    <div id="profileCard"
         class="relative w-[95%] max-w-md bg-white/90 backdrop-blur-xl
                rounded-3xl shadow-2xl border border-white/30
                transform scale-90 opacity-0
                transition-all duration-300
                max-h-[90vh] flex flex-col">

        <!-- Decorative Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-transparent to-purple-500/10 pointer-events-none"></div>



        <!-- Header -->
        <div class="p-6 md:p-7 pb-4 shrink-0">

            <!-- Signature Preview -->
            <!-- Signature Preview -->
            <div class="flex justify-center mb-3">
                <div class="relative group">

                    <!-- SIGNATURE CONTAINER -->
                    <div id="signatureContainer"
                         class="bg-white border border-gray-200 rounded-2xl p-3 shadow-lg">

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
                    <button id="redrawBtn" disabled hidden
                            type="button"
                            class="hidden absolute -bottom-3 left-1/2 -translate-x-1/2
                                   px-4 py-2 rounded-full
                                   bg-indigo-600 text-white text-sm font-medium
                                   shadow-lg hover:bg-indigo-700
                                   transition-all duration-300">

                        <i class="ri-quill-pen-line mr-1"></i>

                        {{ auth()->user()->signature ? 'Redraw' : 'Draw Signature' }}
                    </button>

                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800">
                Profile Information
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Manage your account details and signature
            </p>
        </div>
      <div class="px-6 md:px-7 overflow-y-auto flex-1">
        <!-- FORM -->
        <div id="profileFields" class="space-y-4 relative">

            <!-- Name -->
            <div>
                <label class="text-sm font-medium text-gray-500 mb-1 block">
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
                <label class="text-sm font-medium text-gray-500 mb-1 block">
                    Department
                </label>

                <input type="text"
                       id="department"
                       value="{{ auth()->user()->office->name ?? 'N/A' }}"
                       disabled
                       class="profile-input">
            </div>

            <!-- designation -->
            <div>
                <label class="text-sm font-medium text-gray-500 mb-1 block">
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
                <label class="text-sm font-medium text-gray-500 mb-1 block">
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
                    <label class="text-sm font-medium text-gray-500 mb-1 block">
                        Status
                    </label>

                    <input type="text"
                           id="status"
                           value="{{ auth()->user()->status }}"
                           disabled
                           class="profile-input capitalize">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500 mb-1 block">
                        Role
                    </label>

                    <input type="text"
                           id="role"
                           value="{{ auth()->user()->role }}"
                           disabled
                           class="profile-input capitalize">
                </div>

            </div>

        </div>
       </div>

        <!-- ACTION BUTTONS -->
      <div class="p-6 md:p-7 pt-4 shrink-0 border-t border-gray-200">
        <div class="flex justify-end gap-3">

            <button id="cancelBtn"
                    class="hidden px-5 py-2.5 rounded-xl
                           bg-gray-100 hover:bg-gray-200
                           text-gray-700 font-medium
                           transition-all duration-200">
                Cancel
            </button>

            <button id="editBtn"
                    class="px-5 py-2.5 rounded-xl
                           bg-indigo-600 hover:bg-indigo-700
                           text-white font-medium shadow-lg
                           hover:shadow-indigo-300/40
                           transition-all duration-300">
                <i class="ri-edit-line mr-1"></i>
                Edit
            </button>




            <button type="button"
                    onclick="closeProfileModal()"
                    class="px-5 py-2.5 rounded-xl
                           bg-gray-500 hover:bg-gray-600
                           text-white font-medium shadow
                           transition-all duration-200">
                Close
            </button>

            <button id="saveBtn"
                    class="hidden px-5 py-2.5 rounded-xl
                           bg-emerald-600 hover:bg-emerald-700
                           text-white font-medium shadow-lg
                           hover:shadow-emerald-300/40
                           transition-all duration-300">
                <i class="ri-save-line mr-1"></i>
                Save
            </button>

        </div>
       </div>

    </div>
</div>

<!-- STYLE -->
<style>
    .profile-input{
        width:100%;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:12px 14px;
        background:#f9fafb;
        transition:all .25s ease;
        color:#374151;
    }

    .profile-input:focus{
        outline:none;
        border-color:#6366f1;
        background:white;
        box-shadow:0 0 0 4px rgba(99,102,241,.12);
    }

    .profile-input.editing{
        background:white;
    }
</style>



<!-- SIGNATURE DRAW MODAL -->
<div id="signatureModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm
            flex items-center justify-center
            opacity-0 pointer-events-none
            transition-all duration-300 z-[10000]">

    <div id="signatureCard"
         class="w-[95%] max-w-2xl
                bg-white rounded-3xl shadow-2xl
                p-6 relative
                transform scale-90 opacity-0
                transition-all duration-300">

        <!-- Header -->
        <div class="flex items-center justify-between mb-5">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Draw Signature
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Use your mouse, stylus, or finger to sign
                </p>
            </div>

            <button onclick="closeSignatureModal()"
                    class="w-10 h-10 rounded-full
                           bg-gray-100 hover:bg-red-100
                           text-gray-500 hover:text-red-500
                           flex items-center justify-center
                           transition-all duration-200">

                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Signature Area -->
        <div class="border-2 border-dashed border-gray-300
                    rounded-2xl overflow-hidden bg-white shadow-inner">

            <canvas id="signatureCanvas"
                    class="w-full h-[260px] cursor-crosshair"></canvas>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center mt-5">

            <button id="clearSignature"
                    class="px-5 py-2.5 rounded-xl
                           bg-red-100 text-red-600
                           hover:bg-red-200
                           transition-all duration-200">

                <i class="ri-delete-bin-line mr-1"></i>
                Clear
            </button>

            <div class="flex gap-3">

                <button onclick="closeSignatureModal()"
                        class="px-5 py-2.5 rounded-xl
                               bg-gray-100 hover:bg-gray-200
                               text-gray-700 transition-all duration-200">

                    Cancel
                </button>

                <button id="saveSignatureBtn"
                        class="px-5 py-2.5 rounded-xl
                               bg-indigo-600 hover:bg-indigo-700
                               text-white shadow-lg
                               transition-all duration-300">

                    <i class="ri-check-line mr-1"></i>
                    Save Signature
                </button>

            </div>

        </div>

    </div>
</div>

<!-- SCRIPT -->
<script>
/* =========================================================
   PROFILE MODAL
========================================================= */

const modalProfile = document.getElementById('profileModal');
const profileCard = document.getElementById('profileCard');

const editBtn = document.getElementById('editBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');

const redrawBtn = document.getElementById('redrawBtn');

let signaturePreview = document.getElementById('signaturePreview');

const fields = modalProfile.querySelectorAll(
    'input:not([disabled])'
);

let signatureBlob = null;

/* OPEN PROFILE MODAL */
function openProfileModal() {

    modalProfile.classList.remove(
        'opacity-0',
        'pointer-events-none'
    );

    setTimeout(() => {

        modalProfile.classList.add('opacity-100');

        profileCard.classList.remove(
            'scale-90',
            'opacity-0'
        );

        profileCard.classList.add(
            'scale-100',
            'opacity-100'
        );

    }, 10);
}

/* CLOSE PROFILE MODAL */
function closeProfileModal() {

    profileCard.classList.remove(
        'scale-100',
        'opacity-100'
    );

    profileCard.classList.add(
        'scale-90',
        'opacity-0'
    );

    modalProfile.classList.remove('opacity-100');

    setTimeout(() => {

        modalProfile.classList.add(
            'opacity-0',
            'pointer-events-none'
        );

    }, 250);

    resetFields();
}

/* OUTSIDE CLICK */
modalProfile.addEventListener('click', (e) => {

    if (e.target === modalProfile) {

        closeProfileModal();
    }
});

/* ESC CLOSE */
document.addEventListener('keydown', (e) => {

    if (e.key === 'Escape') {

        closeProfileModal();
        closeSignatureModal();
    }
});

/* EDIT MODE */
editBtn.addEventListener('click', () => {

    fields.forEach(field => {

        if (!field.disabled) {

            field.removeAttribute('readonly');

            field.classList.add('editing');
        }
    });

    redrawBtn.classList.remove('hidden');

    editBtn.classList.add('hidden');

    saveBtn.classList.remove('hidden');

    cancelBtn.classList.remove('hidden');
});

/* CANCEL */
cancelBtn.addEventListener('click', () => {

    resetFields();
});

/* RESET */
function resetFields() {

    fields.forEach(field => {

        field.setAttribute('readonly', true);

        field.classList.remove('editing');
    });

    redrawBtn.classList.add('hidden');

    editBtn.classList.remove('hidden');

    saveBtn.classList.add('hidden');

    cancelBtn.classList.add('hidden');
}

/* SAVE PROFILE */
saveBtn.addEventListener('click', async () => {

    const formData = new FormData();

    formData.append(
        'name',
        document.getElementById('name').value
    );

    formData.append(
        'designation',
        document.getElementById('designation').value
    );

    formData.append(
        'email',
        document.getElementById('email').value
    );

    formData.append(
        '_token',
        '{{ csrf_token() }}'
    );

    formData.append(
        '_method',
        'PUT'
    );

    /* APPEND DRAWN SIGNATURE */
    if (signatureBlob) {

        formData.append(
            'signature',
            signatureBlob,
            'signature.png'
        );
    }

    try {

        const response = await fetch(
            '{{ route("user.update", auth()->user()->id) }}',
            {
                method: 'POST',
                body: formData
            }
        );

        const result = await response.json();

        Swal.fire({
            icon: 'success',
            title: 'Profile Updated',
            text: 'Your profile has been updated successfully.',
            confirmButtonColor: '#4f46e5'
        });

        closeProfileModal();

    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: 'Something went wrong.'
        });
    }
});


/* =========================================================
   SIGNATURE DRAW MODAL
========================================================= */

const signatureModal = document.getElementById(
    'signatureModal'
);

const signatureCard = document.getElementById(
    'signatureCard'
);

const canvas = document.getElementById(
    'signatureCanvas'
);

const ctx = canvas.getContext('2d');

const clearBtn = document.getElementById(
    'clearSignature'
);

const saveSignatureBtn = document.getElementById(
    'saveSignatureBtn'
);

let drawing = false;

/* OPEN SIGNATURE MODAL */
redrawBtn.addEventListener('click', () => {

    signatureModal.classList.remove(
        'opacity-0',
        'pointer-events-none'
    );

    setTimeout(() => {

        signatureModal.classList.add('opacity-100');

        signatureCard.classList.remove(
            'scale-90',
            'opacity-0'
        );

        signatureCard.classList.add(
            'scale-100',
            'opacity-100'
        );

    }, 10);

    resizeCanvas();
});

/* CLOSE SIGNATURE MODAL */
function closeSignatureModal() {

    signatureCard.classList.remove(
        'scale-100',
        'opacity-100'
    );

    signatureCard.classList.add(
        'scale-90',
        'opacity-0'
    );

    signatureModal.classList.remove('opacity-100');

    setTimeout(() => {

        signatureModal.classList.add(
            'opacity-0',
            'pointer-events-none'
        );

    }, 250);
}

/* RESIZE CANVAS */
function resizeCanvas() {

    const ratio = Math.max(
        window.devicePixelRatio || 1,
        1
    );

    canvas.width = canvas.offsetWidth * ratio;

    canvas.height = canvas.offsetHeight * ratio;

    ctx.scale(ratio, ratio);

    ctx.lineWidth = 2.5;

    ctx.lineCap = 'round';

    ctx.strokeStyle = '#111827';
}

window.addEventListener('resize', resizeCanvas);

/* START DRAW */
function startPosition(e) {

    drawing = true;

    draw(e);
}

/* END DRAW */
function endPosition() {

    drawing = false;

    ctx.beginPath();
}

/* DRAW */
function draw(e) {

    if (!drawing) return;

    e.preventDefault();

    const rect = canvas.getBoundingClientRect();

    let x;
    let y;

    if (e.touches) {

        x = e.touches[0].clientX - rect.left;

        y = e.touches[0].clientY - rect.top;

    } else {

        x = e.clientX - rect.left;

        y = e.clientY - rect.top;
    }

    ctx.lineTo(x, y);

    ctx.stroke();

    ctx.beginPath();

    ctx.moveTo(x, y);
}

/* MOUSE EVENTS */
canvas.addEventListener(
    'mousedown',
    startPosition
);

canvas.addEventListener(
    'mouseup',
    endPosition
);

canvas.addEventListener(
    'mousemove',
    draw
);

/* TOUCH EVENTS */
canvas.addEventListener(
    'touchstart',
    startPosition
);

canvas.addEventListener(
    'touchend',
    endPosition
);

canvas.addEventListener(
    'touchmove',
    draw
);

/* CLEAR SIGNATURE */
clearBtn.addEventListener('click', () => {

    ctx.clearRect(
        0,
        0,
        canvas.width,
        canvas.height
    );
});

/* SAVE SIGNATURE */
/* SAVE SIGNATURE */
saveSignatureBtn.addEventListener('click', () => {

    canvas.toBlob(blob => {

        signatureBlob = blob;

        const imageUrl = URL.createObjectURL(blob);

        /*
        |--------------------------------------------------------------------------
        | IF THERE IS NO EXISTING IMAGE
        |--------------------------------------------------------------------------
        */
        if (!signaturePreview) {

            const placeholder = document.getElementById(
                'noSignaturePlaceholder'
            );

            if (placeholder) {

                placeholder.remove();
            }

            const img = document.createElement('img');

            img.id = 'signaturePreview';

            img.src = imageUrl;

            img.alt = 'Signature';

            img.className =
                'w-56 h-28 object-contain transition duration-300 group-hover:scale-105';

            document.getElementById(
                'signatureContainer'
            ).appendChild(img);

            signaturePreview = img;

        } else {

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING IMAGE
            |--------------------------------------------------------------------------
            */
            signaturePreview.src = imageUrl;
        }

        closeSignatureModal();

    }, 'image/png');
});

</script>
