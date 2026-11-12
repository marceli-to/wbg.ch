<template>
  <page>
    <h1>{{ type === 'edit' ? 'Kategorie bearbeiten' : 'Kategorie hinzufügen' }}</h1>
    <tabs :tabs="[{ key: 'data', label: 'Daten' }]" :active="activeTab" :errors="tabErrors" @change="changeTab" />
    <form @submit.prevent="submit" novalidate>
      <div class="form-row" :class="{ 'has-error': errors.name }">
        <label for="name">Name *</label>
        <input id="name" type="text" @focus="clearError('name')" v-model="category.name" />
      </div>
      <form-buttons route="categories" :busy="busy" />
    </form>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Page from '@/components/ui/Page.vue';
import Tabs from '@/components/ui/Tabs.vue';
import FormButtons from '@/components/ui/FormButtons.vue';
import api from '@/lib/api';
import { useForm } from '@/composables/useForm';

const props = defineProps({ type: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const { errors, activeTab, tabErrors, clearError, changeTab, validateRequired, handleServerErrors } = useForm();

const category = reactive({ name: null });
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`categories/${route.params.id}`).then(({ data }) => Object.assign(category, data));
}

async function submit() {
  if (!validateRequired(category, ['name'])) return;
  busy.value = true;
  try {
    if (props.type === 'edit') {
      await api.put(`categories/${route.params.id}`, category);
    } else {
      await api.post('categories', category);
    }
    router.push({ name: 'categories' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
