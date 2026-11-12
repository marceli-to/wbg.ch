import axios from 'axios';
import { error as notifyError } from '@/lib/notify';

/**
 * Axios instance for the admin API.
 *
 * Auth is the regular Laravel session cookie. Laravel sets the XSRF-TOKEN
 * cookie and axios sends it back as X-XSRF-TOKEN on same-origin requests.
 *
 * Request config flags:
 *   silent: true      -> don't show a notification on errors (caller handles them)
 *   skipAuthRedirect  -> don't redirect to the login on 401
 */
const api = axios.create({
    baseURL: '/api/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
    },
});

export function errorMessage(err, fallback = 'Es ist ein Fehler aufgetreten.') {
    const data = err?.response?.data;
    if (data?.errors) {
        const first = Object.values(data.errors)[0];
        if (Array.isArray(first) && first.length) {
            return first[0];
        }
    }
    return data?.message || fallback;
}

/**
 * Wire the global error handling. Called once from main.js with the router
 * and the auth store (avoids circular imports).
 */
export function setupInterceptors({ router, auth }) {
    api.interceptors.response.use(
        (response) => response,
        (err) => {
            const status = err?.response?.status;
            const config = err?.config || {};

            if (status === 401 && !config.skipAuthRedirect) {
                auth.user = null;
                if (router.currentRoute.value.name !== 'login') {
                    router.push({ name: 'login', query: { redirect: router.currentRoute.value.fullPath } });
                }
                return Promise.reject(err);
            }

            // CSRF token mismatch (e.g. expired session): start fresh
            if (status === 419) {
                window.location.reload();
                return Promise.reject(err);
            }

            if (!config.silent && axios.isAxiosError(err) && !axios.isCancel(err)) {
                if (status === 422 && err.response.data?.errors) {
                    notifyError('Bitte markierte Felder prüfen!');
                } else if (status === 404) {
                    notifyError('Eintrag nicht gefunden.');
                } else if (status === 429) {
                    notifyError('Zu viele Anfragen. Bitte später erneut versuchen.');
                } else {
                    notifyError(errorMessage(err));
                }
            }

            return Promise.reject(err);
        },
    );
}

export default api;
