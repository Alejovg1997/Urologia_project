import axios from 'axios';
window.axios = axios;

// Default header used by Laravel for AJAX requests
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Security mitigations for known axios advisories (development + production)
// - limit response/body sizes to reduce DoS risk via huge payloads
// - set a reasonable timeout so requests cannot hang indefinitely
// These defaults can be overridden per-request when needed.
window.axios.defaults.maxContentLength = 2000000; // bytes
window.axios.defaults.maxBodyLength = 2000000; // bytes
window.axios.defaults.timeout = 30000; // 30 seconds

// Optional: do not follow more than a few redirects (if axios supports)
// axios doesn't expose a built-in maxRedirects in the browser, but Node adapter does.

// Note: Also ensure server-side validation/whitelisting for any user-supplied URLs
// to avoid SSRF or credential leakage (this is a server concern, see controllers).
