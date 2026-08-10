<!-- GLOBAL LOADING OVERLAY COMPONENT WITH DUAL-SIDED ROTATING LOGO -->
<div id="globalLoadingOverlay" 
     class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-slate-950/80 backdrop-blur-md transition-opacity duration-300 opacity-0 pointer-events-none">
  
  <div class="flex flex-col items-center justify-center p-8 rounded-3xl bg-slate-900/90 border border-slate-700/50 shadow-2xl shadow-orange-500/20 max-w-xs text-center">
    
    <!-- 3D ROTATING COIN / LOGO CONTAINER -->
    <div class="w-28 h-28 mb-6 relative perspective-1000">
      <div id="globalLoadingSpinner" class="w-full h-full relative transform-style-3d animate-spin-y">
        
        <!-- FRONT SIDE: pmo.jpeg -->
        <div class="absolute inset-0 w-full h-full rounded-full border-4 border-orange-500/80 shadow-lg shadow-orange-500/40 overflow-hidden bg-slate-900 backface-hidden flex items-center justify-center">
          <img src="{{ asset('pmo.jpeg') }}" alt="Background Graphic" class="w-full h-full object-cover">
        </div>
        
        <!-- BACK SIDE: logo.png -->
        <div class="absolute inset-0 w-full h-full rounded-full border-4 border-amber-400/80 shadow-lg shadow-amber-400/40 overflow-hidden bg-slate-900 backface-hidden flex items-center justify-center transform rotate-y-180">
          <img src="{{ asset('logo.png') }}" alt="System Logo" class="w-20 h-20 object-contain p-2">
        </div>

      </div>
    </div>
    
    <!-- LOADING TEXT & SUBTEXT -->
    <h3 id="globalLoadingText" class="text-base font-bold text-white tracking-wide mb-1">
      Processing...
    </h3>
    <p id="globalLoadingSubtext" class="text-xs text-orange-400 font-medium animate-pulse">
      Please wait a moment
    </p>

    <!-- BOUNCING LOADING DOTS -->
    <div class="flex items-center gap-1.5 mt-4">
      <div class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-bounce" style="animation-delay: 0s"></div>
      <div class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay: 0.15s"></div>
      <div class="w-2.5 h-2.5 rounded-full bg-orange-600 animate-bounce" style="animation-delay: 0.3s"></div>
    </div>

  </div>
</div>

<style>
.perspective-1000 {
  perspective: 1000px;
}
.transform-style-3d {
  transform-style: preserve-3d;
}
.backface-hidden {
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}
.rotate-y-180 {
  transform: rotateY(180deg);
}

@keyframes spinY {
  0% {
    transform: rotateY(0deg);
  }
  100% {
    transform: rotateY(360deg);
  }
}

.animate-spin-y {
  animation: spinY 2.2s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
}
</style>

<script>
(function() {
    let loadingCount = 0;

    window.showGlobalLoading = function(text = 'Processing...', subtext = 'Please wait a moment') {
        loadingCount++;
        const overlay = document.getElementById('globalLoadingOverlay');
        const textEl = document.getElementById('globalLoadingText');
        const subtextEl = document.getElementById('globalLoadingSubtext');
        
        if (textEl) textEl.textContent = text;
        if (subtextEl) subtextEl.textContent = subtext;
        
        if (overlay) {
            overlay.classList.remove('pointer-events-none', 'opacity-0');
            overlay.classList.add('pointer-events-auto', 'opacity-100');
        }
    };

    window.hideGlobalLoading = function(force = false) {
        if (force) {
            loadingCount = 0;
        } else {
            loadingCount = Math.max(0, loadingCount - 1);
        }
        
        if (loadingCount === 0) {
            const overlay = document.getElementById('globalLoadingOverlay');
            if (overlay) {
                overlay.classList.remove('pointer-events-auto', 'opacity-100');
                overlay.classList.add('pointer-events-none', 'opacity-0');
            }
        }
    };

    // Initial Page / Dashboard Load Handler: Keeps overlay active until browser tab finishes loading
    if (document.readyState !== 'complete') {
        window.showGlobalLoading('Loading System...', 'Please wait a moment');
        
        const fadeLoader = function() {
            window.hideGlobalLoading(true);
        };

        // Window 'load' fires ONLY when browser tab finishes loading all page assets & resources
        window.addEventListener('load', fadeLoader, { once: true });
    } else {
        window.hideGlobalLoading(true);
    }


})();
</script>
