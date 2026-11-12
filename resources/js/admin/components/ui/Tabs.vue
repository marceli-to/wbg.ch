<template>
  <nav class="tabs">
    <ul>
      <li v-for="tab in tabs" :key="tab.key">
        <a
          href="#"
          @click.prevent="$emit('change', tab.key)"
          :class="[active === tab.key ? 'is-active' : '', errors[tab.key] ? 'has-error' : '']"
        >{{ tab.label }}</a>
      </li>
    </ul>
  </nav>
</template>
<script setup>
defineProps({
  tabs: { type: Array, required: true }, // [{key, label}]
  active: { type: String, required: true },
  errors: { type: Object, default: () => ({}) },
});
defineEmits(['change']);
</script>
