<template>
  <page>
    <h1>{{ type === 'edit' ? 'Teammitglied bearbeiten' : 'Teammitglied hinzufügen' }}</h1>
    <tabs :tabs="tabList" :active="activeTab" :errors="tabErrors" @change="changeTab" />
    <form @submit.prevent="submit" novalidate>
      <div v-show="activeTab === 'data'">
        <div class="grid-team">
          <div class="span form-row" :class="{ 'has-error': errors.firstname }">
            <label for="firstname">Vorname *</label>
            <input id="firstname" type="text" @focus="clearError('firstname')" v-model="team.firstname" />
          </div>
          <div class="span form-row" :class="{ 'has-error': errors.name }">
            <label for="name">Name *</label>
            <input id="name" type="text" @focus="clearError('name')" v-model="team.name" />
          </div>
          <div class="span form-row" :class="{ 'has-error': errors.role }">
            <label for="role">Position</label>
            <input id="role" type="text" @focus="clearError('role')" v-model="team.role" placeholder="z.B. Partner" />
          </div>
          <div class="span form-row" :class="{ 'has-error': errors.phone }">
            <label for="phone">Telefon</label>
            <input id="phone" type="text" @focus="clearError('phone')" v-model="team.phone" placeholder="Format: +41 44 2xx xx xx" />
          </div>
          <div class="span form-row" :class="{ 'has-error': errors.email }">
            <label for="email">E-Mail *</label>
            <input id="email" type="text" @focus="clearError('email')" v-model="team.email" />
          </div>
        </div>
      </div>
      <div v-show="activeTab === 'media'">
        <image-upload v-model="team.media" :delete-url="(name) => `team/media/${name}`" />
      </div>
      <form-buttons route="team" :busy="busy" />
    </form>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Page from '@/components/ui/Page.vue';
import Tabs from '@/components/ui/Tabs.vue';
import FormButtons from '@/components/ui/FormButtons.vue';
import ImageUpload from '@/components/ui/ImageUpload.vue';
import api from '@/lib/api';
import { useForm } from '@/composables/useForm';

const props = defineProps({ type: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const tabList = [
  { key: 'data', label: 'Daten' },
  { key: 'media', label: 'Medien' },
];
const { errors, activeTab, tabErrors, clearError, changeTab, validateRequired, handleServerErrors } = useForm({
  tabs: ['data', 'media'],
  fieldTabs: { media: 'media' },
});

const team = reactive({ firstname: null, name: null, role: null, phone: null, email: null, media: null });
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`team/${route.params.id}`).then(({ data }) => Object.assign(team, data));
}

async function submit() {
  if (!validateRequired(team, ['firstname', 'name', 'email'])) return;
  busy.value = true;
  try {
    if (props.type === 'edit') {
      await api.put(`team/${route.params.id}`, team);
    } else {
      await api.post('team', team);
    }
    router.push({ name: 'team' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
