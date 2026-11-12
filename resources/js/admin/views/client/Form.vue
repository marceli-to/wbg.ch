<template>
  <page>
    <h1>{{ type === 'edit' ? 'Kunde bearbeiten' : 'Kunde hinzufügen' }}</h1>
    <tabs :tabs="[{ key: 'data', label: 'Daten' }]" :active="activeTab" :errors="tabErrors" @change="changeTab" />
    <form @submit.prevent="submit" novalidate>
      <div class="form-row" :class="{ 'has-error': errors.name }">
        <label for="name">Name *</label>
        <input id="name" type="text" @focus="clearError('name')" v-model="client.name" />
      </div>
      <div class="form-row" :class="{ 'has-error': errors.location }">
        <label for="location">Ort</label>
        <input id="location" type="text" @focus="clearError('location')" v-model="client.location" />
      </div>
      <div class="form-row" :class="{ 'has-error': errors.project_id }">
        <label for="project_id">Verlinktes Projekt</label>
        <div class="select-wrapper">
          <select id="project_id" class="is-wide" v-model="client.project_id" @focus="clearError('project_id')">
            <option :value="null">Bitte wählen...</option>
            <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}{{ p.category ? ` – ${p.category.name}` : '' }}</option>
          </select>
        </div>
      </div>
      <form-buttons route="clients" :busy="busy" />
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

const client = reactive({ name: null, location: null, project_id: null });
const projects = ref([]);
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`clients/${route.params.id}`).then(({ data }) => Object.assign(client, data));
}
api.get('projects', { params: { published: 1 } }).then(({ data }) => (projects.value = data));

async function submit() {
  if (!validateRequired(client, ['name'])) return;
  busy.value = true;
  try {
    if (props.type === 'edit') {
      await api.put(`clients/${route.params.id}`, client);
    } else {
      await api.post('clients', client);
    }
    router.push({ name: 'clients' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
