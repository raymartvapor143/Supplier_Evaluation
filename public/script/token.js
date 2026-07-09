
const SESSION_LIFETIME_MINUTES = 480;
const PRE_EXPIRATION_ALERT_MINUTES = 1;
const CSRF_REFRESH_INTERVAL_MINUTES = 10;


function ensureCsrfInputs() {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    document.querySelectorAll('form').forEach(form => {
        let input = form.querySelector('input[name="_token"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            form.prepend(input);
        }
        input.value = token;
    });
}


async function refreshCsrfToken() {
    try {
        const res = await fetch('/refresh-csrf');
        if (!res.ok) throw new Error('Failed to refresh CSRF token');
        const data = await res.json();

        // Update meta tag
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) meta.setAttribute('content', data.token);

        // Update all forms
        ensureCsrfInputs();

        console.log('CSRF token refreshed');
    } catch (err) {
        console.error('Error refreshing CSRF token:', err);
    }
}


refreshCsrfToken();
setInterval(refreshCsrfToken, CSRF_REFRESH_INTERVAL_MINUTES * 60 * 1000);


async function safeFetch(url, options = {}) {
    options.headers = options.headers || {};
    options.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
    options.headers['Accept'] = 'application/json';

    try {
        const res = await fetch(url, options);

        if (res.status === 419) {
            alert('Your session has expired. Reloading page...');
            window.location.reload();
            return;
        }

        return res;
    } catch (err) {
        console.error('Fetch error:', err);
        throw err;
    }
}



function schedulePreExpirationReload() {
    const lifetimeMs = SESSION_LIFETIME_MINUTES * 60 * 1000;
    const reloadMs = lifetimeMs - (PRE_EXPIRATION_ALERT_MINUTES * 60 * 1000);

    if (reloadMs > 0) {
        setTimeout(() => {
            alert('Your session is about to expire. Reloading page...');
            window.location.reload();
        }, reloadMs);
    }
}


document.addEventListener('DOMContentLoaded', () => {
    ensureCsrfInputs();
    schedulePreExpirationReload();
});
