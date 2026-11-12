<template>
  <div :class="wrapperClass" :data-icons="icons">
    <slot name="before" />
    <a
      v-if="toggle"
      href="#"
      :class="[published ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
      :title="published ? 'Ausblenden' : 'Publizieren'"
      @click.prevent="$emit('toggle', $event)"
    ></a>
    <router-link v-if="edit" :to="edit" class="icon-edit icon-mini" title="Bearbeiten"></router-link>
    <a v-if="clone" href="#" class="icon-copy icon-mini" title="Kopieren" @click.prevent="$emit('clone', $event)"></a>
    <a href="#" class="icon-trash icon-mini" title="Löschen" @click.prevent="$emit('destroy', $event)"></a>
  </div>
</template>
<script setup>
defineProps({
  wrapperClass: { type: String, default: 'list-item-action' },
  published: { type: [Boolean, Number], default: false },
  toggle: { type: Boolean, default: true },
  clone: { type: Boolean, default: true },
  edit: { type: Object, default: null },
  icons: { type: [String, Number], default: null },
});
defineEmits(['toggle', 'clone', 'destroy']);
</script>
