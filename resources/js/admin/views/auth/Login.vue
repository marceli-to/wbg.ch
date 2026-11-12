<template>
  <div class="container-auth">
    <main class="content-auth" role="main">
      <header class="auth-header">
        <h1>Login</h1>
      </header>
      <form method="POST" class="login" @submit.prevent="submit">
        <div class="form-row" :class="{ 'has-error': error }">
          <label for="email">E-Mail</label>
          <input id="email" type="email" required autofocus autocomplete="email" v-model="email" />
        </div>
        <div class="form-row" :class="{ 'has-error': error }">
          <label for="password">Passwort</label>
          <input id="password" type="password" required autocomplete="current-password" v-model="password" />
          <span class="form-helper is-error" v-if="error" role="alert">{{ error }}</span>
        </div>
        <div class="form-row is-last">
          <div class="form-buttons">
            <input type="submit" class="btn" value="Login" :disabled="busy" />
          </div>
        </div>
      </form>
    </main>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { errorMessage } from '@/lib/api';

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();

const email = ref('');
const password = ref('');
const error = ref(null);
const busy = ref(false);

async function submit() {
  error.value = null;
  busy.value = true;
  try {
    await auth.login(email.value, password.value);
    const redirect = typeof route.query.redirect === 'string' && route.query.redirect.startsWith('/')
      ? route.query.redirect
      : null;
    router.replace(redirect || { name: 'projects' });
  } catch (err) {
    const status = err?.response?.status;
    if (status === 429) {
      error.value = 'Zu viele Login-Versuche. Bitte später erneut versuchen.';
    } else if (status === 422) {
      error.value = errorMessage(err, 'Die Zugangsdaten sind ungültig.');
    } else {
      error.value = 'Login fehlgeschlagen. Bitte erneut versuchen.';
    }
  } finally {
    busy.value = false;
  }
}
</script>
