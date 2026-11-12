import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '@/lib/api';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const checked = ref(false);

    /**
     * Ask the server whether the session is logged in (once per page load).
     */
    async function check() {
        if (checked.value) return user.value;
        try {
            const { data } = await api.get('user', { silent: true, skipAuthRedirect: true });
            user.value = data;
        } catch {
            user.value = null;
        } finally {
            checked.value = true;
        }
        return user.value;
    }

    async function login(email, password) {
        const { data } = await api.post('login', { email, password }, { silent: true, skipAuthRedirect: true });
        user.value = data;
        checked.value = true;
        return data;
    }

    async function logout() {
        try {
            await api.post('logout', null, { silent: true, skipAuthRedirect: true });
        } finally {
            user.value = null;
        }
    }

    return { user, checked, check, login, logout };
});
