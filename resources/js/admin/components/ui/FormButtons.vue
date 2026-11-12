<template>
  <footer class="form-footer">
    <div>
      <button type="submit" class="btn-secondary" :class="{ 'is-loading': busy }" :disabled="busy">Speichern</button>
      <router-link :to="{ name: route }">Zurück</router-link>
    </div>
  </footer>
</template>
<script setup>
defineProps({
  route: { type: String, required: true },
  busy: { type: Boolean, default: false },
});
</script>
