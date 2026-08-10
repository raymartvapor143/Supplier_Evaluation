<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Login</title>


  <script src="{{asset('script/tailwind.js')}}"></script>
 
<script src="{{asset('script/sweetalert.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


  <style>
    @keyframes fadeSlide {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fade-slide {
      animation: fadeSlide 0.8s ease-out forwards;
    }
  </style>
  <script>
    // Force reload/re-check when navigating back/forward in browser history
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
  </script>
</head>

@include('layouts.loading')

<script src="{{asset('script/block.js')}}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))

        Swal.fire({
            icon: 'success',
            title: 'Password Reset Successful',
            text: "{{ session('success') }}",
            confirmButtonText: 'Continue',
            confirmButtonColor: '#2563eb'
        });

    @endif


    @if(session('error'))

        Swal.fire({
            icon: 'error',
            title: 'Reset Failed',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });

    @endif

});
</script>

<body class="min-h-screen bg-slate-950 font-sans antialiased text-slate-800 selection:bg-orange-500 selection:text-white flex flex-col md:flex-row relative overflow-x-hidden">

  <!-- LEFT PANEL: BACKGROUND IMAGE WITH S-CURVE DIVIDER -->
  <div class="relative w-full md:w-1/2 lg:w-7/12 min-h-[40vh] md:min-h-screen overflow-hidden flex items-center justify-center bg-slate-950">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full bg-center bg-no-repeat"
         style="background-image: url('{{ asset('finalbg.png') }}'); background-size: 100% 100%;">
    </div>

    <!-- S-CURVE DIVIDER (Desktop / Medium Screens) -->
    <svg class="absolute -right-1 top-0 bottom-0 h-full w-28 md:w-40 lg:w-56 text-slate-950 z-10 pointer-events-none hidden md:block"
         viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
      <path d="M 0,0 C 70,25 30,75 100,100 L 105,100 L 105,0 Z"></path>
    </svg>

    <!-- S-CURVE DIVIDER (Mobile Screens) -->
    <svg class="absolute -bottom-1 left-0 right-0 w-full h-16 md:h-24 text-slate-950 z-10 pointer-events-none block md:hidden"
         viewBox="0 0 100 100" preserveAspectRatio="none" fill="currentColor">
      <path d="M 0,0 C 25,75 75,25 100,100 L 100,105 L 0,105 Z"></path>
    </svg>
  </div>

  <!-- RIGHT PANEL: MAIN LOGIN FORM CONTAINER -->
  <div class="relative z-20 w-full md:w-1/2 lg:w-5/12 min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 bg-slate-950">
    
    <div class="w-full max-w-md bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 sm:p-10 animate-fade-slide transform transition-all duration-300">
      
      <!-- LOGO & BRAND HEADER -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-400 text-white shadow-lg shadow-orange-500/30 mb-4 transform hover:scale-105 transition-transform duration-300">
          <img src="{{ asset('logo.png') }}" alt="Logo" class="w-10 h-10 object-contain" onerror="this.remove()">
        </div>
        <h2 class="text-xs font-bold tracking-widest uppercase text-orange-600 mb-1">
          Welcome to
        </h2>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-snug">
          SUPPLIER'S EVALUATION SYSTEM
        </h1>
        <p class="text-xs text-slate-500 mt-1 font-medium">
          Sign in to access your portal & dashboard
        </p>
      </div>

      <!-- FORM -->
      <form class="space-y-5">
        
        <!-- EMAIL -->
        <div>
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
            Email Address
          </label>
          <div class="relative flex items-center">
            <input
              type="email"
              placeholder="you@example.com"
              class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none transition-all shadow-sm"
              required
            />
          </div>
        </div>

        <!-- PASSWORD -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
              Password
            </label>
            <a href="#"
               onclick="showForgotPasswordModal(event)"
               class="text-xs font-semibold text-orange-600 hover:text-orange-700 hover:underline transition">
              Forgot password?
            </a>
          </div>

          <div class="relative flex items-center">
            <input
                id="loginPassword"
                type="password"
                placeholder="••••••••"
                class="w-full px-4 py-3.5 pr-12 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none transition-all shadow-sm"
                required
            />

            <button
                type="button"
                onclick="togglePassword('loginPassword', this)"
                class="absolute right-3 text-slate-400 hover:text-orange-600 transition p-2 rounded-lg hover:bg-orange-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                             c4.477 0 8.268 2.943 9.542 7
                             -1.274 4.057-5.065 7-9.542 7
                             -4.477 0-8.268-2.943-9.542-7z"/>
                </svg>

            </button>
          </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <button
            id="loginBtn"
            type="submit"
            class="w-full mt-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-3.5 rounded-xl font-bold text-sm tracking-wide transition-all duration-300 shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 active:scale-[0.98]"
        >
            Sign In to Portal
        </button>

      </form>

      <!-- REGISTRATION FOOTER LINK -->
      <div class="text-center text-xs text-slate-600 mt-6 pt-4 border-t border-slate-100">
        <p>
          Don't have an account?
          <button onclick="showPrivacyNotice()" class="text-orange-600 hover:text-orange-700 hover:underline font-bold ml-1">
            Register here
          </button>
        </p>
      </div>

      <!-- LEGAL FOOTER -->
      <div class="mt-6 text-center text-[11px] text-slate-400 leading-relaxed">
        <p>
          By using this system, you agree to our
          <a href="{{ route('privacy.privacy') }}"
              class="text-orange-600 hover:underline font-medium">
              Privacy Center
          </a>.
        </p>
        <p class="mt-1 font-mono text-[10px] text-slate-400">
          © 2026 OPPMO - Supplier's Evaluation System
        </p>
      </div>

    </div>

  </div>


<script>
let lockTimer;

function startLock(seconds) {

    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[type="password"]');
    const loginBtn = document.getElementById('loginBtn');

    emailInput.disabled = true;
    passwordInput.disabled = true;
    loginBtn.disabled = true;

    clearInterval(lockTimer);

    lockTimer = setInterval(() => {

        loginBtn.innerText = `Try again in ${seconds}s`;

        seconds--;

        if (seconds < 0) {

            clearInterval(lockTimer);

            emailInput.disabled = false;
            passwordInput.disabled = false;
            loginBtn.disabled = false;

            loginBtn.innerText = 'Login';
        }

    }, 1000);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const loginForm = document.querySelector('form');

    if (!loginForm) return;

const savedEmail = localStorage.getItem('login_email');

if (savedEmail) {

    loginForm.querySelector('input[type="email"]').value = savedEmail;

    axios.post('/login-status', {
        email: savedEmail
    })
    .then(response => {

        if (response.data.locked) {
            startLock(response.data.seconds);
        }

    })
    .catch(error => {
        console.log(error);
    });
}

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = loginForm.querySelector('input[type="email"]').value.trim();
        const password = loginForm.querySelector('input[type="password"]').value.trim();

        localStorage.setItem('login_email', email);

        if (!email || !password) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Please enter your email and password.'
            });
            return;
        }

        showGlobalLoading('Signing In to Portal...', 'Authenticating credentials, please wait');

        try {
            const response = await axios.post('/logincontrol', {
                email: email,
                password: password
            }, {
                loadingText: 'Signing In to Portal...'
            });

            hideGlobalLoading(true);

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.data.message,
                confirmButtonText: 'Continue'
            }).then(() => {

                const role = response.data.user.role;

                if (role === 'administrator') {
                    window.location.href = '/admin-dashboard';
                } else if (role === 'end_user') {
                    window.location.href = '/enduser-dashboard';
                } else if (role === 'pgso') {
                    window.location.href = '/pgso-dashboard';
                } else if (role === 'presentative_staff') {
                    window.location.href = '/enduser-dashboard';
                } else {
                    window.location.href = '/';
                }
            });

        } catch (error) {

            hideGlobalLoading(true);

            let message = 'Something went wrong. Please try again.';

if (error.response) {

    const data = error.response.data;

    if (data.locked) {

        startLock(data.seconds);
    }

    if (data.message) {
        message = data.message;
    }
}

            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: message
            });
        }

    });

});
</script>









  </div>





<div id="registerModal"
    class="fixed inset-0 hidden items-center justify-center z-50 bg-black/60 backdrop-blur-md overflow-y-auto p-4 sm:p-6 transition-all duration-300">

   
    <div id="modalContent"
        class="relative w-full max-w-5xl bg-white rounded-[28px] shadow-[0_20px_80px_rgba(0,0,0,0.25)] border border-gray-200 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out max-h-[95vh] overflow-y-auto">

    
        <div
            class="relative bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 px-6 sm:px-10 py-8 text-white overflow-hidden">

       
            <div
                class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl -translate-y-24 translate-x-24">
            </div>


   <button onclick="closeModal()"
    aria-label="Close Modal"
    class="absolute top-6 right-6 z-30 group
           w-11 h-11
           rounded-2xl
           bg-white/15 backdrop-blur-md
           border border-white/30
           hover:bg-rose-600
           hover:border-rose-400
           hover:scale-105
           active:scale-95
           shadow-xl shadow-black/20
           flex items-center justify-center
           transition-all duration-200 ease-out">

    <svg xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5 text-white group-hover:rotate-90 transition-transform duration-300 relative z-10"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor">

        <path stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.5"
            d="M6 18L18 6M6 6l12 12" />

    </svg>

</button>

            <!-- HEADER CONTENT -->
            <div class="relative z-10 flex items-center gap-4">

                <!-- ICON -->
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-white/10 border border-white/20 flex items-center justify-center shadow-xl backdrop-blur-md">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 sm:w-10 sm:h-10 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                    </svg>

                </div>

                <!-- TEXT -->
                <div>

                    <h2 class="text-2xl sm:text-4xl font-bold tracking-tight">
                        Register Account
                    </h2>

                    <p class="text-blue-100 mt-2 text-sm sm:text-base max-w-2xl">
                        Create your secure account and complete identity verification to access the system.
                    </p>

                </div>

            </div>

        </div>

        <!-- BODY -->
        <div class="grid grid-cols-1 lg:grid-cols-5">

<!-- LEFT SIDE -->
<div
    class="hidden lg:flex lg:col-span-2 bg-slate-50 border-r border-slate-200 p-10 flex-col justify-between">

    <div>

        <div class="mb-8">

            <h3 class="text-2xl font-bold text-slate-800 mb-3">
                Security & Registration
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed">
                Please complete all required information accurately to ensure successful account verification and approval.
            </p>

        </div>

        <div class="space-y-6">

            <!-- ITEM -->
            {{-- <div
                class="flex gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200">

                <div
                    class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-700 text-xl shrink-0">
                    🔒
                </div>

                <div>

                    <h4 class="font-semibold text-slate-800">
                        Protected Information
                    </h4>

                    <p class="text-sm text-slate-500 leading-relaxed mt-1">
                        Your personal information is encrypted and securely protected within the system.
                    </p>

                </div>

            </div> --}}


<!-- ITEM -->
<div
    class="flex gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200">

    <div
        class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-700 text-xl shrink-0">
        💡
    </div>

    <div>

        <h4 class="font-semibold text-slate-800">
            Registration Tips
        </h4>

        <ul class="text-sm text-slate-500 leading-relaxed mt-2 space-y-1 list-disc pl-4">
            <li>Use a valid and active email address.</li>
            <li>Draw a clean and accurate digital signature.</li>
            <li>Select the correct department before submitting.</li>
            <li>Wait for administrator approval before logging in.</li>
        </ul>

    </div>

</div>

<!-- ITEM -->
<div
    class="flex gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200">

    <div
        class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-700 text-xl shrink-0">
        ✍️
    </div>

    <div>

        <h4 class="font-semibold text-slate-800">
            Draw Your Signature
        </h4>

        <p class="text-sm text-slate-500 leading-relaxed mt-1">
            Draw a clean and accurate digital signature to enable secure document verification and evaluator authentication.
        </p>

    </div>

</div>

            <!-- ITEM -->
            <div
                class="flex gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200">

                <div
                    class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center text-violet-700 text-xl shrink-0">
                    ✅
                </div>

                <div>

                    <h4 class="font-semibold text-slate-800">
                        Administrator Approval
                    </h4>

                    <p class="text-sm text-slate-500 leading-relaxed mt-1">
                        After successful registration, your account will remain pending until reviewed and approved by the system administrator.
                    </p>

                </div>

            </div>


        </div>

    </div>

    <!-- FOOTER CARD -->
    <div
        class="mt-10 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white shadow-xl">

        <div class="flex items-center gap-3 mb-3">

            <div
                class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center text-2xl">
                🛡️
            </div>

            <div>

                <h4 class="font-semibold text-lg">
                    Data Privacy Act
                </h4>

                <p class="text-xs text-blue-100">
                    Republic Act No. 10173
                </p>

            </div>

        </div>

        <p class="text-sm text-blue-100 leading-relaxed">
            All personal data collected during registration is securely managed and protected in compliance with the Philippine Data Privacy Act of 2012.
        </p>

    </div>

</div>

            <!-- FORM -->
            <div class="lg:col-span-3 p-5 sm:p-8 md:p-10">

                <!-- AJAX FORM -->
                <form id="registerForm" class="space-y-7">
                    <!-- HONEYPOT BOT FIELD (HIDDEN) -->
                    <input type="text" name="b_website" id="b_website" class="hidden" tabindex="-1" autocomplete="off" style="display:none !important;" aria-hidden="true">

                    <!-- STEP HEADER INDICATOR -->
                    <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 flex items-center justify-between text-xs sm:text-sm font-medium text-blue-900">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">1</span>
                            <span>Account & Role</span>
                        </div>
                        <div class="w-8 h-0.5 bg-blue-200"></div>
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">2</span>
                            <span>Security & Signature</span>
                        </div>
                        <div class="w-8 h-0.5 bg-blue-200"></div>
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">3</span>
                            <span>CAPTCHA</span>
                        </div>
                    </div>

                    <!-- GRID -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- FULL NAME -->
                        <div class="md:col-span-2">

                            <label class="form-label flex items-center justify-between">
                                <span>Full Name <span class="text-red-500">*</span></span>
                                <span class="text-xs text-slate-400 font-normal">First M. Last</span>
                            </label>

                            <input type="text"
                                name="name"
                                class="modern-input"
                                placeholder="e.g. Juan Dela Cruz"
                                required>

                        </div>

                        <!-- DEPARTMENT -->
                        <div class="relative md:col-span-2">

                            <label class="form-label flex items-center justify-between">
                                <span>Department / Office <span class="text-red-500">*</span></span>
                                <span class="text-xs text-slate-400 font-normal">Select official office</span>
                            </label>

                            <!-- KEEPING ORIGINAL IDS -->
                            <input type="text"
                                id="officeInput"
                                placeholder="Type to search department..."
                                class="modern-input"
                                autocomplete="off">

                            <!-- KEEPING ORIGINAL NAME + ID -->
                            <input type="hidden"
                                name="office_id"
                                id="office_id">

                            <!-- KEEPING ORIGINAL ID -->
                            <div id="officeDropdown"
                                class="absolute z-50 w-full bg-white border border-slate-200 rounded-2xl shadow-2xl mt-2 hidden max-h-60 overflow-y-auto p-2">

                                @foreach($offices as $office)
                                    @if($office->name !== 'PGSO-Warehouse')

                                        <!-- KEEPING ORIGINAL CLASS -->
                                        <div class="office-option px-4 py-3 rounded-xl hover:bg-blue-50 cursor-pointer transition-all duration-150 flex items-center justify-between"
                                            data-id="{{ $office->id }}"
                                            data-name="{{ $office->name }}"
                                            data-abbreviation="{{ $office->abbreviation ?? '' }}">

                                            <span class="font-medium text-slate-800">{{ $office->name }}</span>
                                            @if(!empty($office->abbreviation))
                                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
                                                    {{ $office->abbreviation }}
                                                </span>
                                            @endif

                                        </div>

                                    @endif
                                @endforeach

                            </div>

                        </div>

                        <!-- designation -->
                        <div class="md:col-span-2">

                            <label class="form-label flex items-center justify-between">
                                <span>Designation / Position <span class="text-red-500">*</span></span>
                            </label>

                            <input type="text"
                                name="designation"
                                class="modern-input"
                                placeholder="e.g. Administrative Officer IV"
                                required>

                        </div>

                        <!-- ROLE -->
                        <div>

                            <label class="form-label">
                                Role Account <span class="text-red-500">*</span>
                            </label>

                            <select name="role"
                                class="modern-input bg-white"
                                required>

                                <option value="" disabled selected>
                                    Select System Role
                                </option>

                                <option value="end_user">
                                    End-User
                                </option>

                                <option value="administrator">
                                    Administrator
                                </option>

                                <option value="head">
                                    Department Head
                                </option>

                                <option value="presentative_staff">
                                    Authorized Staff
                                </option>

                            </select>

                        </div>

<!-- AUTHORIZATION LETTER -->
<div id="authorizationLetterContainer" class="md:col-span-2 hidden">

    <label class="form-label flex items-center justify-between">
        <span>Authorization Letter <span class="text-red-500">*</span></span>
        <span class="text-xs text-slate-400 font-normal">PDF format (Max 5MB)</span>
    </label>

    <input type="file"
        id="authorization_letter"
        name="authorization_letter"
        accept=".pdf"
        class="modern-input">

    <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Upload signed authorization letter from department head.
    </p>

</div>

                        <!-- EMAIL -->
                        <div>

                            <label class="form-label">
                                Email Address <span class="text-red-500">*</span>
                            </label>

                            <input type="email"
                                name="email"
                                class="modern-input"
                                placeholder="you@domain.gov.ph"
                                required>

                        </div>

                        <!-- PASSWORD -->
                        <div class="relative md:col-span-2 sm:col-span-1">

                            <label class="form-label">
                                Password <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="modern-input pr-14"
                                    placeholder="Minimum 8 characters"
                                    required>

                                <!-- KEEPING ORIGINAL FUNCTION -->
                                <button type="button"
                                    onclick="togglePassword('password', this)"
                                    class="eye-btn">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                    </svg>

                                </button>
                            </div>

                            <!-- PASSWORD STRENGTH METER -->
                            <div id="passwordStrengthContainer" class="mt-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-1">
                                    <span>Password Complexity:</span>
                                    <span id="passwordStrengthLabel" class="text-slate-400 font-bold">Too Short</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden flex">
                                    <div id="passwordStrengthBar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                                </div>
                            </div>

                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="relative md:col-span-2 sm:col-span-1">

                            <label class="form-label">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="modern-input pr-14"
                                    placeholder="Re-enter password"
                                    required>

                                <!-- KEEPING ORIGINAL FUNCTION -->
                                <button type="button"
                                    onclick="togglePassword('password_confirmation', this)"
                                    class="eye-btn">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                    </svg>

                                </button>
                            </div>

                            <p id="passwordMatchHint" class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                Must match password above.
                            </p>

                        </div>

                    </div>

<!-- SIGNATURE SECTION -->
<div class="rounded-3xl border border-slate-200 bg-slate-50/80 p-5 sm:p-6 shadow-sm">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">

        <div>
            <div class="flex items-center gap-2">
                <h3 class="text-lg font-bold text-slate-800">
                    Digital Signature
                </h3>
                <span id="signatureStatusBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                    ⚠️ Required
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                Draw and click Save to lock your digital signature for identity verification.
            </p>
        </div>

        <div class="flex gap-2">

            <button type="button"
                id="clearSignatureBtn"
                class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition">
                Clear
            </button>

            <button type="button"
                id="saveSignatureBtn"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-md transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save
            </button>

            <button type="button"
                id="editSignatureBtn"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-md hidden transition">
                Edit
            </button>

        </div>

    </div>

    <!-- SIGNATURE CANVAS -->
    <div class="relative w-full rounded-2xl overflow-hidden border border-slate-300 bg-white shadow-inner">

        <canvas id="signaturePad"
            class="w-full h-[220px] sm:h-[280px] touch-none cursor-crosshair"></canvas>

    </div>

    <!-- HIDDEN INPUT -->
    <input type="hidden" name="signature" id="signatureInput">

</div>

<!-- REGISTRATION CAPTCHA SECTION -->
<div class="rounded-3xl border border-slate-200 bg-slate-50/80 p-5 sm:p-6 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h4 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Security Puzzle Verification
            </h4>
            <p class="text-xs text-slate-500 mt-0.5">
                Slide the puzzle piece into position to verify human registration.
            </p>
        </div>
        <button type="button" id="refreshRegisterPuzzleBtn" onclick="loadRegisterPuzzleCaptcha()" class="text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-sm transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </button>
    </div>

    <div class="relative w-[300px] h-[140px] mx-auto rounded-2xl overflow-hidden shadow-inner border border-slate-300 bg-slate-900 select-none">
        <canvas id="registerPuzzleCanvas" width="300" height="140" class="block w-full h-full"></canvas>
        <canvas id="registerPieceCanvas" width="45" height="45" class="absolute top-0 left-0 pointer-events-none transition-transform duration-75" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.6));"></canvas>
    </div>

    <div class="mt-3 relative w-[300px] mx-auto">
        <input type="range" id="registerPuzzleSlider" min="0" max="255" value="0"
            class="w-full h-9 accent-blue-600 bg-slate-200 rounded-xl cursor-pointer appearance-none outline-none focus:ring-2 focus:ring-blue-400">
        <div id="registerPuzzleStatus" class="text-center text-xs font-semibold mt-2 text-slate-500">
            Slide the puzzle piece to fit the target slot
        </div>
    </div>
</div>


<script>
let currentPuzzleData = null;
let isPuzzleVerified = false;

async function loadPuzzleCaptcha() {
    isPuzzleVerified = false;
    currentPuzzleData = null;

    const statusEl = document.getElementById('puzzleStatus');
    const slider = document.getElementById('puzzleSlider');
    const canvas = document.getElementById('puzzleCanvas');
    const pieceCanvas = document.getElementById('pieceCanvas');

    if (!canvas || !pieceCanvas || !slider) return;

    statusEl.innerHTML = '<span class="text-gray-400">Loading security puzzle...</span>';
    statusEl.className = "text-center text-xs font-medium mt-2 text-gray-500";
    slider.value = 0;
    slider.disabled = true;
    pieceCanvas.style.transform = 'translateX(0px)';

    try {
        const res = await axios.get("{{ url('/forgot-password/puzzle') }}");
        currentPuzzleData = res.data;

        const ctx = canvas.getContext('2d');
        const pCtx = pieceCanvas.getContext('2d');
        const w = 300, h = 150;
        const pw = 45, ph = 45;
        const targetX = currentPuzzleData.target_x;
        const targetY = currentPuzzleData.target_y;

        // Render colorful procedural background
        const grad = ctx.createLinearGradient(0, 0, w, h);
        grad.addColorStop(0, '#1e293b');
        grad.addColorStop(0.5, '#0f172a');
        grad.addColorStop(1, '#1e1b4b');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, w, h);

        // Add geometric pattern details
        const seed = currentPuzzleData.seed || 1234;
        for (let i = 0; i < 6; i++) {
            ctx.beginPath();
            ctx.arc((seed * (i + 1) * 37) % w, (seed * (i + 1) * 73) % h, 25 + (i * 8), 0, Math.PI * 2);
            ctx.fillStyle = `hsla(${(seed + i * 60) % 360}, 75%, 55%, 0.45)`;
            ctx.fill();
        }

        // Draw grid overlay lines
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.12)';
        ctx.lineWidth = 1;
        for (let x = 0; x < w; x += 20) {
            ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, h); ctx.stroke();
        }
        for (let y = 0; y < h; y += 20) {
            ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(w, y); ctx.stroke();
        }

        // Draw header text on canvas
        ctx.font = 'bold 11px sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.fillText('SUPPLIER EVALUATION SECURITY PUZZLE', 12, 22);

        // Copy original piece graphic to pieceCanvas
        pCtx.clearRect(0, 0, pw, ph);
        pCtx.drawImage(canvas, targetX, targetY, pw, ph, 0, 0, pw, ph);

        // Highlight border of floating piece
        pCtx.lineWidth = 2;
        pCtx.strokeStyle = '#3b82f6';
        pCtx.strokeRect(0, 0, pw, ph);

        // Draw dark cutout target box on main canvas
        ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
        ctx.fillRect(targetX, targetY, pw, ph);
        ctx.lineWidth = 2;
        ctx.strokeStyle = '#60a5fa';
        ctx.setLineDash([4, 4]);
        ctx.strokeRect(targetX, targetY, pw, ph);
        ctx.setLineDash([]);

        // Position pieceCanvas vertically
        pieceCanvas.style.top = targetY + 'px';
        pieceCanvas.style.left = '0px';

        slider.disabled = false;
        slider.max = w - pw;
        statusEl.innerHTML = 'Slide the puzzle piece to fit the target slot';
        statusEl.className = "text-center text-xs font-semibold mt-2 text-blue-600";

        slider.oninput = () => {
            const val = parseInt(slider.value, 10);
            pieceCanvas.style.transform = `translateX(${val}px)`;

            if (Math.abs(val - targetX) <= 6) {
                isPuzzleVerified = true;
                statusEl.innerHTML = '✓ Puzzle Aligned! You can submit now.';
                statusEl.className = "text-center text-xs font-bold mt-2 text-emerald-600";
            } else {
                isPuzzleVerified = false;
                statusEl.innerHTML = 'Slide to align the puzzle piece';
                statusEl.className = "text-center text-xs font-semibold mt-2 text-blue-600";
            }
        };

    } catch (e) {
        statusEl.innerHTML = '<span class="text-red-500">Failed to load puzzle. Please click Refresh.</span>';
    }
}

async function showForgotPasswordModal(event) {

    event.preventDefault();

    let widgetId = null;

    const { value: formData } = await Swal.fire({

        width: 520,

        background: "#ffffff",

        showCancelButton: true,

        reverseButtons: true,

        focusConfirm: false,

        confirmButtonText: `
            <span class="flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>

                Send Reset Link
            </span>
        `,

        cancelButtonText: `
            <span class="flex items-center gap-2">
                Cancel
            </span>
        `,


        customClass: {

            popup:
                'rounded-3xl shadow-2xl border border-gray-100',

            confirmButton:
                'bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-6 py-3 font-semibold transition',

            cancelButton:
                'bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl px-6 py-3 font-semibold transition',

            validationMessage:
                'rounded-xl'

        },


        title: '',


        html: `

        <div class="flex flex-col items-center mb-6">


            <div class="
                w-20 h-20
                rounded-full
                bg-gradient-to-br
                from-blue-500
                to-indigo-600
                flex
                items-center
                justify-center
                shadow-xl">


                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-10 h-10 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">


                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 0c-4.418 0-8 2.239-8 5v2h16v-2c0-2.761-3.582-5-8-5z"/>


                </svg>


            </div>


            <h2 class="
                text-2xl
                font-bold
                text-gray-800
                mt-5">

                Forgot Password?

            </h2>


            <p class="
                text-sm
                text-gray-500
                mt-2">

                Recover your account securely

            </p>


        </div>



        <div class="text-left">


            <div class="
                bg-blue-50
                border
                border-blue-100
                rounded-2xl
                p-4
                mb-5">


                <p class="
                    text-sm
                    text-blue-700
                    leading-relaxed">


                    Enter your registered email address.
                    We will send a secure password reset link.


                </p>


            </div>



            <label class="
                block
                text-sm
                font-semibold
                text-gray-700
                mb-2">

                Email Address

            </label>



            <input
                id="forgotEmail"
                type="email"
                class="
                    w-full
                    px-5
                    py-3
                    rounded-xl
                    border
                    border-gray-300
                    focus:ring-4
                    focus:ring-blue-100
                    focus:border-blue-500
                    outline-none
                    transition"
                placeholder="you@example.com"
                autocomplete="email"
            >

            <!-- Custom Interactive Puzzle CAPTCHA Container -->
            <div class="mt-5 border border-gray-200 rounded-2xl p-4 bg-slate-50/70 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Drag Puzzle Verification
                    </span>
                    <button type="button" id="refreshPuzzleBtn" onclick="loadPuzzleCaptcha()" class="text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Refresh Puzzle
                    </button>
                </div>

                <div class="relative w-[300px] h-[150px] mx-auto rounded-xl overflow-hidden shadow-md border border-slate-300 bg-slate-900 select-none">
                    <canvas id="puzzleCanvas" width="300" height="150" class="block w-full h-full"></canvas>
                    <canvas id="pieceCanvas" width="45" height="45" class="absolute top-0 left-0 pointer-events-none transition-transform duration-75" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.6));"></canvas>
                </div>

                <div class="mt-3 relative w-[300px] mx-auto">
                    <input type="range" id="puzzleSlider" min="0" max="255" value="0"
                        class="w-full h-9 accent-blue-600 bg-gray-200 rounded-lg cursor-pointer appearance-none outline-none focus:ring-2 focus:ring-blue-400">
                    <div id="puzzleStatus" class="text-center text-xs font-semibold mt-2 text-gray-500">
                        Slide the puzzle piece to fit the target slot
                    </div>
                </div>
            </div>

        </div>
        `,

        didOpen: () => {
            loadPuzzleCaptcha();
        },

        preConfirm: () => {
            const email = document.getElementById("forgotEmail").value.trim();

            if(!email){
                Swal.showValidationMessage("Please enter your email address.");
                return false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailPattern.test(email)){
                Swal.showValidationMessage("Please enter a valid email address.");
                return false;
            }

            const slider = document.getElementById('puzzleSlider');
            const sliderVal = slider ? parseInt(slider.value, 10) : 0;

            if(!currentPuzzleData || !currentPuzzleData.token){
                Swal.showValidationMessage("Security puzzle loading failed. Please refresh puzzle.");
                return false;
            }

            if (!isPuzzleVerified && Math.abs(sliderVal - currentPuzzleData.target_x) > 8) {
                Swal.showValidationMessage("Please solve the puzzle by sliding the piece into position.");
                return false;
            }

            return {
                email,
                captcha_token: currentPuzzleData.token,
                captcha_x: sliderVal
            };
        }
    });

    if(!formData) return;

    try {
        Swal.fire({
            title:"Sending Reset Link",
            html:`
                <p class="text-gray-500">
                    Please wait while we process your request...
                </p>
            `,
            allowOutsideClick:false,
            didOpen:()=>{
                Swal.showLoading();
            }
        });

        const response = await axios.post(
            "{{ url('/forgot-password') }}",
            {
                email: formData.email,
                captcha_token: formData.captcha_token,
                captcha_x: formData.captcha_x
            }
        );



        Swal.fire({

            icon:"success",

            title:"Email Sent",

            text:

            response.data.message ||

            "Password reset instructions have been sent.",


            confirmButtonColor:"#2563eb"

        });



    } catch(error){



        Swal.fire({

            icon:"error",

            title:"Request Failed",

            text:

            error.response?.data?.message ||

            "Unable to process your request.",


            confirmButtonColor:"#dc2626"

        });


    }


}

</script>




<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    const input = document.getElementById('signatureInput');

    const clearBtn = document.getElementById('clearSignatureBtn');
    const saveBtn = document.getElementById('saveSignatureBtn');
    const editBtn = document.getElementById('editSignatureBtn');

    let drawing = false;
    let locked = false;

    // =========================
    // SAFE RESIZE (NO BUGS)
    // =========================
    function resizeCanvas() {
        const ratio = window.devicePixelRatio || 1;
        const rect = canvas.getBoundingClientRect();

        if (rect.width === 0 || rect.height === 0) {
            requestAnimationFrame(resizeCanvas);
            return;
        }

        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "#111827";
    }

    window.addEventListener('load', () => requestAnimationFrame(resizeCanvas));
    window.addEventListener('resize', resizeCanvas);

    // =========================
    // POSITION
    // =========================
    function getPos(e) {
        const rect = canvas.getBoundingClientRect();

        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;

        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    // =========================
    // DRAW
    // =========================
    function startDraw(e) {
        if (locked) return;

        drawing = true;
        const pos = getPos(e);

        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!drawing || locked) return;

        const pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function stopDraw() {
        if (locked) return;

        drawing = false;
    }

    // =========================
    // SAVE
    // =========================
    saveBtn.addEventListener('click', () => {

        const data = canvas.toDataURL("image/png");

        if (!data || data === "data:,") {
            alert("Please draw a signature first.");
            return;
        }

        input.value = data;

        locked = true;

        clearBtn.classList.add('hidden');
        saveBtn.classList.add('hidden');
        editBtn.classList.remove('hidden');

        const statusBadge = document.getElementById('signatureStatusBadge');
        if (statusBadge) {
            statusBadge.innerHTML = '✓ Signature Saved';
            statusBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200';
        }

        Swal.fire({
            icon: 'success',
            title: 'Signature Saved',
            text: 'Signature locked successfully.'
        });
    });

    // =========================
    // EDIT
    // =========================
    editBtn.addEventListener('click', () => {

        locked = false;

        clearBtn.classList.remove('hidden');
        saveBtn.classList.remove('hidden');
        editBtn.classList.add('hidden');

        input.value = "";

        const statusBadge = document.getElementById('signatureStatusBadge');
        if (statusBadge) {
            statusBadge.innerHTML = '⚠️ Required';
            statusBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200';
        }

        Swal.fire({
            icon: 'info',
            title: 'Edit Mode Enabled',
            text: 'You can now update your signature.'
        });
    });

    // =========================
    // CLEAR
    // =========================
    clearBtn.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        input.value = "";
        const statusBadge = document.getElementById('signatureStatusBadge');
        if (statusBadge) {
            statusBadge.innerHTML = '⚠️ Required';
            statusBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200';
        }
    });

    // =========================
    // EVENTS
    // =========================
    canvas.addEventListener("mousedown", startDraw);
    canvas.addEventListener("mousemove", draw);
    canvas.addEventListener("mouseup", stopDraw);
    canvas.addEventListener("mouseleave", stopDraw);

    canvas.addEventListener("touchstart", (e) => {
        e.preventDefault();
        startDraw(e);
    }, { passive: false });

    canvas.addEventListener("touchmove", (e) => {
        e.preventDefault();
        draw(e);
    }, { passive: false });

    canvas.addEventListener("touchend", stopDraw);

});
</script>

                    <!-- SUBMIT -->
                    <button type="submit"
                        id="registerBtn"
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-4 rounded-2xl font-semibold text-lg shadow-[0_15px_40px_rgba(34,197,94,0.35)] transition-all duration-300 hover:scale-[1.01]">

                        Register Account

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- MODERN STYLES -->
<style>

    /* MODAL OPEN */
    #registerModal.flex {
        opacity: 1;
    }

    #registerModal.flex #modalContent {
        opacity: 1;
        transform: scale(1);
    }

    /* LABELS */
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 10px;
    }

    /* INPUTS */
    .modern-input {
        width: 100%;
        padding: 16px 20px;
        border-radius: 18px;
        border: 1px solid #dbe2ea;
        background: #fff;
        font-size: 15px;
        color: #0f172a;
        transition: all .25s ease;
        box-shadow: 0 4px 20px rgba(15, 23, 42, .03);
    }

    .modern-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow:
            0 0 0 4px rgba(37, 99, 235, .10),
            0 10px 25px rgba(37, 99, 235, .08);
        transform: translateY(-1px);
    }

    /* EYE BUTTON */
    .eye-btn {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        transition: all .2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        border-radius: 10px;
        z-index: 10;
    }

    .eye-btn:hover {
        color: #0f172a;
        background-color: #f1f5f9;
    }

    /* SCROLLBAR */
    #modalContent::-webkit-scrollbar {
        width: 10px;
    }

    #modalContent::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
    }

    /* MOBILE */
    @media (max-width: 640px) {

        #modalContent {
            border-radius: 24px;
        }

        .modern-input {
            padding: 14px 16px;
            font-size: 14px;
        }

    }

</style>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('officeInput');
    const dropdown = document.getElementById('officeDropdown');
    const hiddenInput = document.getElementById('office_id');
    const options = document.querySelectorAll('.office-option');

    function showDropdown() {
        dropdown.classList.remove('hidden');
    }

    function hideDropdown() {
        dropdown.classList.add('hidden');
    }

    function showAllOptions() {
        options.forEach(option => {
            option.style.display = 'block';
        });
    }

    input.addEventListener('input', () => {

        const value = input.value.trim().toLowerCase();

        // ✅ EMPTY INPUT → SHOW ALL OPTIONS
        if (value === '') {
            showAllOptions();
            showDropdown();
            return;
        }

        let hasMatch = false;

        options.forEach(option => {
            const name = (option.dataset.name || '').toLowerCase();
            const abbr = (option.dataset.abbreviation || '').toLowerCase();

            if (name.includes(value) || abbr.includes(value)) {
                option.style.display = 'block';
                hasMatch = true;
            } else {
                option.style.display = 'none';
            }
        });

        if (hasMatch) {
            showDropdown();
        } else {
            hideDropdown();
        }
    });

    input.addEventListener('focus', () => {
        // ✅ SHOW ALL WHEN CLICKING EMPTY INPUT
        if (input.value.trim() === '') {
            showAllOptions();
        }
        showDropdown();
    });

    options.forEach(option => {
        option.addEventListener('click', () => {
            input.value = option.dataset.name;
            hiddenInput.value = option.dataset.id;
            hideDropdown();
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#officeInput') && !e.target.closest('#officeDropdown')) {
            hideDropdown();
        }
    });

});
</script>




<script>
let currentRegisterPuzzleData = null;
let isRegisterPuzzleVerified = false;

async function loadRegisterPuzzleCaptcha() {
    isRegisterPuzzleVerified = false;
    currentRegisterPuzzleData = null;

    const statusEl = document.getElementById('registerPuzzleStatus');
    const slider = document.getElementById('registerPuzzleSlider');
    const canvas = document.getElementById('registerPuzzleCanvas');
    const pieceCanvas = document.getElementById('registerPieceCanvas');

    if (!canvas || !pieceCanvas || !slider) return;

    statusEl.innerHTML = '<span class="text-slate-400">Loading security puzzle...</span>';
    statusEl.className = "text-center text-xs font-medium mt-2 text-slate-500";
    slider.value = 0;
    slider.disabled = true;
    pieceCanvas.style.transform = 'translateX(0px)';

    try {
        const res = await axios.get("{{ url('/register/puzzle') }}");
        currentRegisterPuzzleData = res.data;

        const ctx = canvas.getContext('2d');
        const pCtx = pieceCanvas.getContext('2d');
        const w = 300, h = 140;
        const pw = 45, ph = 45;
        const targetX = currentRegisterPuzzleData.target_x;
        const targetY = currentRegisterPuzzleData.target_y;

        // Render colorful procedural background
        const grad = ctx.createLinearGradient(0, 0, w, h);
        grad.addColorStop(0, '#0f172a');
        grad.addColorStop(0.5, '#1e293b');
        grad.addColorStop(1, '#0284c7');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, w, h);

        // Add geometric pattern details
        const seed = currentRegisterPuzzleData.seed || 5678;
        for (let i = 0; i < 7; i++) {
            ctx.beginPath();
            ctx.arc((seed * (i + 1) * 41) % w, (seed * (i + 1) * 67) % h, 20 + (i * 7), 0, Math.PI * 2);
            ctx.fillStyle = `hsla(${(seed + i * 50) % 360}, 80%, 60%, 0.4)`;
            ctx.fill();
        }

        // Draw grid overlay lines
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
        ctx.lineWidth = 1;
        for (let x = 0; x < w; x += 20) {
            ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, h); ctx.stroke();
        }
        for (let y = 0; y < h; y += 20) {
            ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(w, y); ctx.stroke();
        }

        // Header watermark
        ctx.font = 'bold 10px sans-serif';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.45)';
        ctx.fillText('ACCOUNT REGISTRATION CAPTCHA', 10, 18);

        // Copy original piece graphic to pieceCanvas
        pCtx.clearRect(0, 0, pw, ph);
        pCtx.drawImage(canvas, targetX, targetY, pw, ph, 0, 0, pw, ph);

        // Highlight border of floating piece
        pCtx.lineWidth = 2;
        pCtx.strokeStyle = '#38bdf8';
        pCtx.strokeRect(0, 0, pw, ph);

        // Draw dark cutout target box on main canvas
        ctx.fillStyle = 'rgba(0, 0, 0, 0.85)';
        ctx.fillRect(targetX, targetY, pw, ph);
        ctx.lineWidth = 2;
        ctx.strokeStyle = '#38bdf8';
        ctx.setLineDash([4, 4]);
        ctx.strokeRect(targetX, targetY, pw, ph);
        ctx.setLineDash([]);

        // Position pieceCanvas vertically
        pieceCanvas.style.top = targetY + 'px';
        pieceCanvas.style.left = '0px';

        slider.disabled = false;
        slider.max = w - pw;
        statusEl.innerHTML = 'Slide the puzzle piece to fit the target slot';
        statusEl.className = "text-center text-xs font-semibold mt-2 text-blue-600";

        slider.oninput = () => {
            const val = parseInt(slider.value, 10);
            pieceCanvas.style.transform = `translateX(${val}px)`;

            if (Math.abs(val - targetX) <= 6) {
                isRegisterPuzzleVerified = true;
                statusEl.innerHTML = '✓ CAPTCHA Aligned! Security Verification Passed.';
                statusEl.className = "text-center text-xs font-bold mt-2 text-emerald-600";
            } else {
                isRegisterPuzzleVerified = false;
                statusEl.innerHTML = 'Slide to align the puzzle piece into position';
                statusEl.className = "text-center text-xs font-semibold mt-2 text-blue-600";
            }
        };

    } catch (e) {
        statusEl.innerHTML = '<span class="text-red-500">Failed to load CAPTCHA. Click Refresh.</span>';
    }
}

// =====================
// MODAL OPEN / CLOSE
// =====================
function openModal() {
  const modal = document.getElementById('registerModal');
  const content = document.getElementById('modalContent');

  modal.classList.remove('hidden');
  modal.classList.add('flex');

  setTimeout(() => {
    content.classList.add('opacity-100', 'scale-100');
    loadRegisterPuzzleCaptcha();
  }, 50);
}

function closeModal() {
  const modal = document.getElementById('registerModal');
  const content = document.getElementById('modalContent');

  content.classList.remove('opacity-100', 'scale-100');

  setTimeout(() => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }, 200);
}

// =====================
// PASSWORD TOGGLE
// =====================
function togglePassword(id, btn) {

    const input = document.getElementById(id);

    const showIcon = `
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                     c4.477 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.065 7-9.542 7
                     -4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
    `;

    const hideIcon = `
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 3l18 18"/>

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10.477 10.489A3 3 0 0013.5 13.5
                     M9.88 5.09A9.953 9.953 0 0112 5
                     c4.478 0 8.268 2.943 9.543 7
                     a9.97 9.97 0 01-4.132 5.411
                     M6.228 6.228A9.956 9.956 0 002.458 12
                     c1.274 4.057 5.065 7 9.542 7
                     a9.95 9.95 0 005.772-1.772"/>
        </svg>
    `;

    if (input.type === "password") {
        input.type = "text";
        btn.innerHTML = hideIcon;
    } else {
        input.type = "password";
        btn.innerHTML = showIcon;
    }
}

</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // PASSWORD STRENGTH EVALUATOR
    const passInput = document.getElementById('password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthLabel = document.getElementById('passwordStrengthLabel');

    if (passInput && strengthBar && strengthLabel) {
        passInput.addEventListener('input', () => {
            const val = passInput.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            if (val.length === 0) {
                strengthBar.style.width = '0%';
                strengthLabel.textContent = 'Too Short';
                strengthLabel.className = 'text-slate-400 font-bold';
            } else if (score <= 1) {
                strengthBar.style.width = '25%';
                strengthBar.className = 'h-full bg-red-500 transition-all duration-300';
                strengthLabel.textContent = 'Weak';
                strengthLabel.className = 'text-red-500 font-bold';
            } else if (score === 2 || score === 3) {
                strengthBar.style.width = '65%';
                strengthBar.className = 'h-full bg-amber-500 transition-all duration-300';
                strengthLabel.textContent = 'Medium';
                strengthLabel.className = 'text-amber-500 font-bold';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.className = 'h-full bg-emerald-500 transition-all duration-300';
                strengthLabel.textContent = 'Strong ✓';
                strengthLabel.className = 'text-emerald-600 font-bold';
            }
        });
    }

    const registerForm = document.querySelector('#registerForm');
    if (!registerForm) return;

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const registerBtn = document.getElementById('registerBtn');
        const honeypotVal = document.getElementById('b_website')?.value?.trim();

        // 1. HONEYPOT BOT CHECK
        if (honeypotVal) {
            Swal.fire({
                icon: 'error',
                title: 'Submission Blocked',
                text: 'Automated bot activity detected.'
            });
            return;
        }

        const designation = registerForm.querySelector('input[name="designation"]').value.trim();
        const name = registerForm.querySelector('input[name="name"]').value.trim();
        const office_id = document.getElementById('office_id').value;
        const role = registerForm.querySelector('select[name="role"]').value;
        const email = registerForm.querySelector('input[name="email"]').value.trim();
        const password = registerForm.querySelector('input[name="password"]').value.trim();
        const password_confirmation = registerForm.querySelector('input[name="password_confirmation"]').value.trim();
        const signature = document.getElementById('signatureInput')?.value?.trim();

        // =====================
        // VALIDATION
        // =====================
        if (!name || !designation || !office_id || !role || !email || !password || !password_confirmation || !signature) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Required Fields',
                text: 'Please complete all required fields including saving your digital signature.'
            });
            return;
        }

        if (password !== password_confirmation) {
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and confirmation do not match.'
            });
            return;
        }

        if (role === 'presentative_staff' && !document.getElementById('authorization_letter').files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Authorization Letter Required',
                text: 'Please upload the required authorization letter.'
            });
            return;
        }

        // =====================
        // CAPTCHA PUZZLE CHECK
        // =====================
        const slider = document.getElementById('registerPuzzleSlider');
        const sliderVal = slider ? parseInt(slider.value, 10) : 0;

        if (!currentRegisterPuzzleData || !currentRegisterPuzzleData.token) {
            Swal.fire({
                icon: 'warning',
                title: 'Security CAPTCHA Loading',
                text: 'Please refresh the puzzle CAPTCHA and try again.'
            });
            loadRegisterPuzzleCaptcha();
            return;
        }

        if (!isRegisterPuzzleVerified && Math.abs(sliderVal - currentRegisterPuzzleData.target_x) > 8) {
            Swal.fire({
                icon: 'warning',
                title: 'CAPTCHA Verification Required',
                text: 'Please solve the puzzle by sliding the piece into position.'
            });
            return;
        }

        // LOCK SUBMIT BUTTON (ANTI DUPLICATE SUBMISSION)
        if (registerBtn) {
            registerBtn.disabled = true;
            registerBtn.innerHTML = `
                <span class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Encrypting & Processing Account...
                </span>
            `;
        }

        showGlobalLoading('Registering Account...', 'Encrypting credentials & validating security token');

        try {
            const formData = new FormData();

            formData.append('designation', designation);
            formData.append('name', name);
            formData.append('office_id', office_id);
            formData.append('role', role);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('password_confirmation', password_confirmation);
            formData.append('signature', signature);

            // SECURITY CAPTCHA & HONEYPOT DATA
            formData.append('captcha_token', currentRegisterPuzzleData.token);
            formData.append('captcha_x', sliderVal);
            formData.append('b_website', honeypotVal || '');

            const authorizationLetter = document.getElementById('authorization_letter')?.files[0];
            if (authorizationLetter) {
                formData.append('authorization_letter', authorizationLetter);
            }

            const response = await axios.post('/register', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideGlobalLoading(true);

            Swal.fire({
                icon: 'success',
                title: 'Registration Successful!',
                text: response.data.message,
                confirmButtonText: 'Continue to Login',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.href = '/';
            });

        } catch (error) {
            hideGlobalLoading(true);

            // RE-ENABLE BUTTON & RELOAD CAPTCHA ON ERROR
            if (registerBtn) {
                registerBtn.disabled = false;
                registerBtn.innerHTML = 'Register Account';
            }
            loadRegisterPuzzleCaptcha();

            let message = 'Something went wrong. Please try again.';
            if (error.response?.data?.message) {
                message = error.response.data.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: message,
                confirmButtonColor: '#dc2626'
            });
        }
    });

});
</script>


<script>
document.addEventListener('DOMContentLoaded', async () => {

    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[type="password"]');
    const loginBtn = document.getElementById('loginBtn');

    let timerInterval = null;

    function startLock(seconds) {

        emailInput.disabled = true;
        passwordInput.disabled = true;
        loginBtn.disabled = true;

        clearInterval(timerInterval);

        timerInterval = setInterval(() => {

            loginBtn.innerText = `Try again in ${seconds}s`;

            seconds--;

            if (seconds < 0) {

                clearInterval(timerInterval);

                emailInput.disabled = false;
                passwordInput.disabled = false;
                loginBtn.disabled = false;

                loginBtn.innerText = 'Login';
            }

        }, 1000);
    }

    async function checkLock() {

        const email = emailInput.value.trim();

        if (!email) return;

        try {

            const response = await axios.post('/login-status', {
                email: email
            });

            if (response.data.locked) {
                startLock(response.data.seconds);
            }

        } catch (e) {
            console.log(e);
        }
    }

    emailInput.addEventListener('blur', checkLock);

});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const roleSelect = document.querySelector('select[name="role"]');
    const authContainer = document.getElementById('authorizationLetterContainer');
    const authInput = document.getElementById('authorization_letter');

    function toggleAuthorizationField() {

        if (roleSelect.value === 'presentative_staff') {

            authContainer.classList.remove('hidden');
            authInput.required = true;

        } else {

            authContainer.classList.add('hidden');
            authInput.required = false;
            authInput.value = '';

        }
    }

    roleSelect.addEventListener('change', toggleAuthorizationField);

    toggleAuthorizationField();
});
</script>



<script>
function showPrivacyNotice() {

    Swal.fire({
        width: 750,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check"></i> I Agree',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false,
        customClass: {
            popup: 'privacy-popup',
            confirmButton: 'btn-agree',
            cancelButton: 'btn-cancel'
        },
        html: `
            <div style="text-align:left;">

                <div style="
                    text-align:center;
                    margin-bottom:20px;
                    padding-bottom:15px;
                    border-bottom:1px solid #e5e7eb;">

                    <h2 style="
                        margin:0;
                        color:#1f2937;
                        font-weight:700;">
                        Data Privacy Notice
                    </h2>

                    <small style="color:#6b7280;">
                        Republic Act No. 10173 (Data Privacy Act of 2012)
                    </small>
                </div>

                <div style="
                    max-height:320px;
                    overflow-y:auto;
                    padding-right:10px;">

                    <div style="
                        background:#f8fafc;
                        padding:15px;
                        border-left:4px solid #0d6efd;
                        border-radius:6px;
                        margin-bottom:15px;">

                        <p style="margin:0; line-height:1.7;">
                            The information you provide during registration
                            will be collected, processed, stored, and used
                            solely for the Supplier Evaluation System.
                        </p>
                    </div>

                    <div style="
                        background:#f8fafc;
                        padding:15px;
                        border-left:4px solid #198754;
                        border-radius:6px;
                        margin-bottom:15px;">

                        <p style="margin:0; line-height:1.7;">
                            Your personal information, uploaded documents,
                            authorization letters, digital signatures, and
                            account details will be handled with strict
                            confidentiality and protected through appropriate
                            security measures.
                        </p>
                    </div>

                    <div style="
                        background:#fff8e6;
                        padding:15px;
                        border-left:4px solid #ffc107;
                        border-radius:6px;">

                        <p style="margin:0; line-height:1.7;">
                            By clicking
                            <strong>"I Agree"</strong>,
                            you voluntarily consent to the collection and
                            processing of your personal data for registration,
                            verification, evaluation, and approval purposes.
                        </p>
                    </div>

                </div>
            </div>
        `
    }).then((result) => {

        if (result.isConfirmed) {
            localStorage.setItem('privacy_accepted', 'true');
            openModal();
        }

    });
}
</script>

<style>
.privacy-popup{
    border-radius:16px !important;
    padding:20px !important;
}

.btn-agree{
    background:#0d6efd !important;
    border:none !important;
    padding:10px 24px !important;
    border-radius:8px !important;
    font-weight:600 !important;
}

.btn-agree:hover{
    background:#0b5ed7 !important;
}

.btn-cancel{
    background:#6c757d !important;
    color:#fff !important;
    border:none !important;
    padding:10px 24px !important;
    border-radius:8px !important;
    font-weight:600 !important;
}
</style>
</body>
</html>
