<template>
  <div>
    <div class="grid-layout-selector">
      <a href="#" class="btn-toggle-layout" @click.prevent="open = !open">Layout wählen</a>
      <ul :class="open ? 'is-visible' : ''">
        <li v-for="layout in layouts" :key="layout.id">
          <a href="#" :title="layout.key" @click.prevent="select(layout.id)">
            <img :src="media.layoutIcon(layout.key)" height="30" width="100" :alt="layout.key" />
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import api from '@/lib/api';
import { media } from '@/lib/helpers';

const props = defineProps({ endpoint: { type: String, required: true } });
const emit = defineEmits(['select']);

const layouts = ref([]);
const open = ref(false);
api.get(props.endpoint).then(({ data }) => (layouts.value = data));

function select(id) {
  open.value = false;
  emit('select', id);
}
</script>
