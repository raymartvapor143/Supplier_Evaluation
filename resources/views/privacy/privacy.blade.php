<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Center | Supplier Evaluation System</title>
    <script src="{{ asset('script/tailwind.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .nav-link.active {
            color: #2563eb;
            font-weight: 600;
            border-left-color: #2563eb;
            background-color: #eff6ff;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md text-white border-b border-slate-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight tracking-tight text-white">Supplier Evaluation System</h1>
                        <p class="text-xs text-blue-400 font-medium">Privacy Center & Data Protection Portal</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('auth.login') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 text-white py-14 px-4 sm:px-6 lg:px-8 relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-600/20 via-transparent to-transparent"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-400/30 mb-4 backdrop-blur-md">
                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                RA 10173 Data Privacy Act Compliant
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">Privacy Center & Data Transparency</h2>
            <p class="text-slate-300 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                We are committed to maintaining the highest standards of data security, operational integrity, and legal compliance in managing supplier evaluation records.
            </p>
            
            <!-- Quick Topic Search Filter -->
            <div class="max-w-xl mx-auto mt-6 relative">
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="privacySearch" onkeyup="filterPrivacySections()" placeholder="Search privacy topics (e.g. e-signatures, RA 10173, data retention...)" 
                        class="w-full pl-11 pr-4 py-3 bg-white/10 text-white placeholder-slate-400 border border-white/20 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-400 backdrop-blur-md text-sm transition">
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">
                    <div class="glass-card rounded-2xl p-4 shadow-sm border border-slate-200">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-3">On This Page</h3>
                        <nav class="space-y-1 text-xs">
                            <a href="#overview" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">1. Overview</a>
                            <a href="#data-collected" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">2. Data Collected (RA 10173)</a>
                            <a href="#login-data" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">3. Login & Registration</a>
                            <a href="#esign" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">4. Electronic Signatures</a>
                            <a href="#file-uploads" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">5. File Uploads & Documents</a>
                            <a href="#evaluation-data" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">6. Dashboard & Evaluation</a>
                            <a href="#data-sharing" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">7. Data Sharing & Access</a>
                            <a href="#security-measures" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">8. Security Measures</a>
                            <a href="#user-rights" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">9. User Rights</a>
                            <a href="#frameworks" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">10. Compliance Frameworks</a>
                            <a href="#contact" class="nav-link block px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition font-semibold text-blue-600">11. Contact & DPO</a>
                        </nav>
                    </div>

                    <!-- DPO Contact Quick Card -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-bold tracking-wide uppercase text-blue-200">Data Protection Officer</span>
                        </div>
                        <p class="text-xs text-blue-100 mb-3">Have questions about your data privacy?</p>
                        <a href="mailto:system.administrator16@gmail.com" class="block w-full py-2.5 px-3 bg-white text-blue-700 hover:bg-blue-50 font-bold text-center text-xs rounded-xl shadow transition truncate">
                            system.administrator16@gmail.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Column -->
            <div class="lg:col-span-3 space-y-6" id="privacyContent">
                
                <!-- Section 1 -->
                <section id="overview" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">1</div>
                        <h2 class="text-xl font-bold text-slate-900">Overview</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        This Privacy Center outlines how the <b>Supplier Evaluation System</b> collects, processes, stores, and protects personal data, supplier profiles, evaluation assessments, electronic signatures, and associated documentations. We maintain strict compliance with data privacy principles and transparency standardizations.
                    </p>
                </section>

                <!-- Section 2 -->
                <section id="data-collected" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">2</div>
                        <h2 class="text-xl font-bold text-slate-900">Data Collected (RA 10173 - Data Privacy Act Compliance)</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        In accordance with <b>Republic Act No. 10173 (Data Privacy Act of 2012)</b>, the Supplier Evaluation System collects only necessary and relevant personal and organizational data for legitimate, declared, and specific procurement and auditing purposes.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-900 text-sm mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span> 2.1 Personal & Account Data
                            </h4>
                            <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                                <li>Full name, email address, contact numbers</li>
                                <li>Encrypted login credentials & role identifiers</li>
                                <li>Activity timestamps and session logs</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-900 text-sm mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> 2.2 Purchase Order & Evaluation Info
                            </h4>
                            <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                                <li>Purchase Order (PO) & Purchase Request (PR) numbers</li>
                                <li>Supplier name, end-user office/department & item details</li>
                                <li>Evaluation performance ratings (quality, timeliness, compliance)</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-900 text-sm mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span> 2.3 Evaluation Ratings
                            </h4>
                            <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                                <li>Supplier evaluation scores & criteria ratings</li>
                                <li>Reviewer feedback & approval comments</li>
                                <li>Audit Trail records and status decisions</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-900 text-sm mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> 2.4 E-Signature & Audit Logs
                            </h4>
                            <ul class="space-y-1.5 text-slate-600 list-disc list-inside">
                                <li>Digital signature verification logs</li>
                                <li>IP addresses & device user-agent strings</li>
                                <li>Timestamp of authorization actions</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-5 p-4 bg-blue-50/80 rounded-xl border border-blue-200/80 text-xs text-blue-900">
                        <div class="font-bold text-blue-900 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Legal Basis of Data Processing (RA 10173)
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-blue-800">
                            <li>Compliance with legal procurement obligations</li>
                            <li>Contractual necessity for supplier evaluations</li>
                            <li>Legitimate organizational interest for security and transparency</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 3 -->
                <section id="login-data" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">3</div>
                        <h2 class="text-xl font-bold text-slate-900">Login & Registration Data Protection</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-3">
                        During user registration and authentication, credentials are protected using industry-standard hashing (Bcrypt/Argon2). We do not store plain text passwords under any circumstances.
                    </p>
                </section>

                <!-- Section 4 -->
                <section id="esign" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">4</div>
                        <h2 class="text-xl font-bold text-slate-900">Electronic Signatures (E-Sign)</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-3">
                        Electronic signatures captured in the system bind evaluation validation and approval workflows.
                    </p>
                    <ul class="space-y-2 text-xs text-slate-600 list-disc list-inside">
                        <li>Every signature is stamped with user ID, IP address, and high-precision UTC timestamp</li>
                        <li>E-signatures are legally recognized under applicable e-commerce and data laws</li>
                        <li>Signature graphics are securely stored and tamper-proofed in audit storage</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section id="file-uploads" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">5</div>
                        <h2 class="text-xl font-bold text-slate-900">File Uploads & Documents</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-3">
                        Uploaded Purchase Order (PO) PDF files, digital signatures, authorization letters, and evaluation reports are stored in protected directory structures with restricted role-based file permissions.
                    </p>
                </section>

                <!-- Section 6 -->
                <section id="evaluation-data" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">6</div>
                        <h2 class="text-xl font-bold text-slate-900">Dashboard & Evaluation Analytics</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-3">
                        Evaluation ratings are processed into performance metrics for internal procurement review and system reporting. Access to evaluation metrics is strictly scoped to designated personnel.
                    </p>
                </section>

                <!-- Section 7 -->
                <section id="data-sharing" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">7</div>
                        <h2 class="text-xl font-bold text-slate-900">Data Sharing & Access Controls</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Data is never sold, monetized, or shared with unauthorized third parties. Disclosure only occurs when required by law or official administrative audit orders.
                    </p>
                </section>

                <!-- Section 8 -->
                <section id="security-measures" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">8</div>
                        <h2 class="text-xl font-bold text-slate-900">Data Security Measures</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 font-medium text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span> Role-Based Access Control (RBAC)
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 font-medium text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span> CSRF & Session Security
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 font-medium text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span> Full Audit Log Tracking
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 font-medium text-slate-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span> Encrypted Data Storage
                        </div>
                    </div>
                </section>

                <!-- Section 9 -->
                <section id="user-rights" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">9</div>
                        <h2 class="text-xl font-bold text-slate-900">Your Data Privacy Rights</h2>
                    </div>
                    <ul class="space-y-2 text-xs text-slate-600 list-disc list-inside">
                        <li><b>Right to Information:</b> Be informed of data processing activities</li>
                        <li><b>Right to Access:</b> Request a copy of your personal evaluation records</li>
                        <li><b>Right to Rectification:</b> Request correction of erroneous data entries</li>
                        <li><b>Right to Erasure:</b> Request data removal, subject to legal archiving rules</li>
                    </ul>
                </section>

                <!-- Section 10 -->
                <section id="frameworks" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">10</div>
                        <h2 class="text-xl font-bold text-slate-900">Data Privacy Frameworks Disclosure</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-3">
                        Our framework aligns with the Philippine Data Privacy Act of 2012 (RA 10173) and global information security best practices.
                    </p>
                </section>

                <!-- Section 11 - Contact & DPO -->
                <section id="contact" class="privacy-section glass-card rounded-2xl p-6 shadow-sm border-2 border-blue-500/40 bg-gradient-to-br from-white to-blue-50/50">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-blue-500/30">11</div>
                        <h2 class="text-xl font-bold text-slate-900">Contact & Privacy Inquiries</h2>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">
                        For any privacy inquiries, data access requests, or system compliance concerns, please contact the designated System Administrator and Data Protection Officer below:
                    </p>

                    <div class="p-5 bg-white rounded-2xl border border-blue-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Data Protection Officer / System Administrator</div>
                            <div class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                system.administrator16@gmail.com
                            </div>
                            <div class="text-xs text-slate-500 mt-1">Official Administrator & Data Inquiry Inbox</div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="copyDpoEmail()" id="copyBtn" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Copy Email
                            </button>
                            <a href="mailto:system.administrator16@gmail.com" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow transition">
                                Send Mail
                            </a>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
            <p class="font-semibold text-slate-300">© {{ date('Y') }} Supplier Evaluation System. All Rights Reserved.</p>
            <p>Protected under Republic Act No. 10173 (Data Privacy Act of 2012)</p>
        </div>
    </footer>

    <script>
        function filterPrivacySections() {
            const input = document.getElementById('privacySearch').value.toLowerCase();
            const sections = document.querySelectorAll('.privacy-section');

            sections.forEach(section => {
                const text = section.innerText.toLowerCase();
                if (text.includes(input)) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }

        function copyDpoEmail() {
            const email = 'system.administrator16@gmail.com';
            navigator.clipboard.writeText(email).then(() => {
                const btn = document.getElementById('copyBtn');
                btn.innerText = '✓ Copied!';
                btn.classList.remove('bg-blue-600');
                btn.classList.add('bg-emerald-600');
                setTimeout(() => {
                    btn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy Email
                    `;
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-blue-600');
                }, 2000);
            });
        }
    </script>
</body>
</html>
