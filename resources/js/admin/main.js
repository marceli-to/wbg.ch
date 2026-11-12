import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { isAxiosError } from 'axios';
import App from '@/App.vue';
import { createAdminRouter } from '@/router';
import { setupInterceptors } from '@/lib/api';
import { useAuthStore } from '@/stores/auth';

const app = createApp(App);
const pinia = createPinia();
app.use(pinia);

const auth = useAuthStore(pinia);
const router = createAdminRouter(auth);
setupInterceptors({ router, auth });

// Failed API requests are already reported by the axios interceptor
// (notification / login redirect); don't log them as uncaught errors.
app.config.errorHandler = (err, instance, info) => {
    if (isAxiosError(err)) return;
    console.error(err, info);
};
window.addEventListener('unhandledrejection', (event) => {
    if (isAxiosError(event.reason)) event.preventDefault();
});

app.use(router);
app.mount('#app');
