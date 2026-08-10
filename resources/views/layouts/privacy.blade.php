<!-- DATA PRIVACY MODAL -->
<div id="privacyModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">

  <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 flex flex-col max-h-[90vh]"
       id="privacyBox">

    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-5 shrink-0 text-white flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold flex items-center gap-2">
          <i class="ri-shield-user-line"></i> Data Privacy Notice
        </h2>
        <p class="text-orange-100 text-xs mt-0.5">
          Supplier Evaluation Portal & Procurement Monitoring System
        </p>
      </div>
      <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold uppercase tracking-wider">
        RA 10173 Compliant
      </span>
    </div>

    <!-- Body Content -->
    <div class="p-6 overflow-y-auto space-y-4 text-sm text-gray-600 leading-relaxed">
      <p class="font-medium text-gray-800">
        In compliance with Republic Act No. 10173 (Data Privacy Act of 2012), the <strong>Supplier Evaluation Portal</strong> is committed to protecting your privacy and securing all organizational and personal information processed within this platform.
      </p>

      <div class="bg-orange-50/60 rounded-2xl p-4 border border-orange-100 space-y-2 text-xs">
        <h3 class="font-semibold text-orange-900 flex items-center gap-1.5 text-sm">
          <i class="ri-database-2-line text-orange-600"></i> Information We Collect & Process
        </h3>
        <ul class="list-disc pl-4 space-y-1 text-gray-700">
          <li><strong>User Account & Credentials:</strong> Name, work email, designated office/department, role (Administrator, Head, Representative Staff, End-user, PGSO), and account status.</li>
          <li><strong>Digital Signatures & Authorizations:</strong> Uploaded official digital signatures and authorization letters for document approvals.</li>
          <li><strong>Supplier Evaluation & Performance Data:</strong> Supplier names, evaluation criteria scores (quality of items, timeliness of delivery, compliance), rating feedback, and semester performance summary reports.</li>
          <li><strong>Purchase Order Details:</strong> Purchase Order (PO) numbers, Purchase Request (PR) numbers, end-user departments, items, and evaluation status.</li>
          <li><strong>Security & Audit Logs:</strong> IP addresses, browser specs, activity audit trails, and timestamps of user actions to prevent unauthorized system access.</li>
        </ul>
      </div>

      <div class="space-y-2">
        <h3 class="font-semibold text-gray-800 flex items-center gap-1.5">
          <i class="ri-lock-2-line text-orange-500"></i> Purpose & File Storage Security
        </h3>
        <ul class="list-disc pl-4 space-y-1 text-xs text-gray-600">
          <li>Facilitate supplier performance evaluation workflows, item scoring, and semester analytics.</li>
          <li>Generate official PDF evaluation certificates and process digital signature approvals.</li>
          <li><strong>File Uploads & Documents:</strong> Uploaded Purchase Order (PO) PDF files, digital signatures, and evaluation summary PDFs are stored in protected directory structures with restricted role-based access permissions.</li>
        </ul>
      </div>

      <div class="space-y-2 border-t pt-3 text-xs text-gray-500">
        <p>
          <i class="ri-verified-badge-line text-green-600"></i>
          Your information is stored strictly within encrypted servers and accessible only by designated system administrators and authorized office heads. Data is never shared with third parties without official administrative authorization.
        </p>
      </div>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between shrink-0">
      <span class="text-xs text-gray-400">By continuing, you acknowledge and agree to these terms.</span>
      <button onclick="acceptPrivacy()"
        class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-xl text-sm transition shadow-sm flex items-center gap-2">
        <i class="ri-checkbox-circle-line text-lg"></i>
        <span>I Understand & Agree</span>
      </button>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const alreadySeen = sessionStorage.getItem('privacy_seen');
    if (!alreadySeen) {
        showPrivacyModal();
    }
});

function showPrivacyModal() {
    const modal = document.getElementById('privacyModal');
    const box = document.getElementById('privacyBox');

    if (!modal || !box) return;

    modal.classList.remove('hidden');

    setTimeout(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closePrivacyModal() {
    const modal = document.getElementById('privacyModal');
    const box = document.getElementById('privacyBox');

    if (!modal || !box) return;

    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function acceptPrivacy() {
    sessionStorage.setItem('privacy_seen', 'true');
    closePrivacyModal();
}
</script>
