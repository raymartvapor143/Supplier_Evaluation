(function () {

    let alertShown = false;

    function showWarning(message) {

        // Do not interrupt existing modal (like access code modal)
        if (Swal.isVisible()) return;

        if (alertShown) return;
        alertShown = true;

        Swal.fire({
            icon: "warning",
            title: "Action Blocked",
            text: message,
            confirmButtonText: "OK",
            showCloseButton: true
        }).then(() => {
            alertShown = false;
        });

    }

    /* DEVTOOLS SIZE DETECTION */
    function detectDevTools() {

        const threshold = 160;
        const widthThreshold = window.outerWidth - window.innerWidth > threshold;
        const heightThreshold = window.outerHeight - window.innerHeight > threshold;

        if (widthThreshold || heightThreshold) {
            showWarning("Developer tools are not allowed on this page.");
        }

    }

    setInterval(detectDevTools, 800);

})();


/* DISABLE RIGHT CLICK */
document.addEventListener("contextmenu", function (e) {

    e.preventDefault();

    if (!Swal.isVisible()) {
        Swal.fire({
            icon: "warning",
            title: "Action Blocked",
            text: "Right click is not allowed.",
            confirmButtonText: "OK",
            showCloseButton: true
        });
    }

});


/* BLOCK DEVTOOLS SHORTCUTS ALWAYS */
document.addEventListener("keydown", function (e) {

    if (
        e.key === "F12" ||
        (e.ctrlKey && e.shiftKey && ["I","J","C"].includes(e.key)) ||
        (e.ctrlKey && e.key === "U") ||
        (e.metaKey && e.altKey && e.key === "I")
    ) {

        e.preventDefault();
        e.stopPropagation();

        // Only show warning if no modal is open
        if (!Swal.isVisible()) {
            Swal.fire({
                icon: "warning",
                title: "Action Blocked",
                text: "Opening Developer Tools is not allowed.",
                confirmButtonText: "OK",
                showCloseButton: true
            });
        }

        return false;

    }

}, true);
