<!-- DATA PRIVACY MODAL -->
<div id="privacyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center">

  <div class="bg-white w-11/12 md:w-2/3 lg:w-1/2 rounded-2xl shadow-2xl p-6 transform transition-all duration-300 scale-95 opacity-0"
       id="privacyBox">

    <h2 class="text-2xl font-bold text-gray-800 mb-3">
      🔐 Data Privacy Notice
    </h2>

    <p class="text-gray-600 text-sm leading-relaxed mb-4">
      We value your privacy. All personal data collected in this system is handled in accordance with applicable data privacy laws.
      Your information is used only for evaluation and system purposes and is securely stored.
    </p>

    <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1 mb-6">
      <li>Your data is protected and encrypted where applicable.</li>
      <li>Only authorized personnel can access your information.</li>
      <li>Data is not shared without proper authorization.</li>
    </ul>

    <div class="flex justify-end gap-3">

      <button onclick="acceptPrivacy()"
        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm">
        I Understand
      </button>
    </div>

  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // show only once per login session
    const alreadySeen = sessionStorage.getItem('privacy_seen');

    if (!alreadySeen) {
        showPrivacyModal();
    }

});

function showPrivacyModal() {
    const modal = document.getElementById('privacyModal');
    const box = document.getElementById('privacyBox');

    modal.classList.remove('hidden');

    setTimeout(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    }, 50);
}



function acceptPrivacy() {
    sessionStorage.setItem('privacy_seen', 'true');
    closePrivacyModal();
}
</script>
