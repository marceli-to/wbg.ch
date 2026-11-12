<template>
  <page>
    <h1>{{ type === 'edit' ? 'Kompetenz bearbeiten' : 'Kompetenz hinzufügen' }}</h1>
    <tabs :tabs="tabList" :active="activeTab" :errors="tabErrors" @change="changeTab" />
    <form @submit.prevent="submit" novalidate>
      <div v-show="activeTab === 'data'">
        <div class="form-row" :class="{ 'has-error': errors.title }">
          <label for="title">Titel *</label>
          <input id="title" type="text" @focus="clearError('title')" v-model="competence.title" />
        </div>
        <div class="form-row" :class="{ 'has-error': errors.description }">
          <label for="description">Beschreibung</label>
          <textarea id="description" @focus="clearError('description')" v-model="competence.description" rows="15"></textarea>
        </div>
        <div class="form-row">
          <div class="grid-form">
            <div class="form-row" :class="{ 'has-error': errors.category_id }">
              <label for="category_id">Kategorie (Verweis)</label>
              <div class="select-wrapper is-wide">
                <select id="category_id" v-model="competence.category_id" @focus="clearError('category_id')">
                  <option :value="null">Bitte wählen...</option>
                  <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
              </div>
            </div>
            <div class="form-row" v-show="competence.category_id == PANOPTIKUM_ID">
              <label for="subcategory_id">Subkategorie (Panoptikum)</label>
              <div class="select-wrapper is-wide">
                <select id="subcategory_id" v-model="competence.subcategory_id">
                  <option :value="null">Bitte wählen...</option>
                  <option v-for="(label, key) in subcategories" :key="key" :value="Number(key)">{{ label }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-show="activeTab === 'media'">
        <multi-image-upload
          v-model="competence.media"
          :delete-url="(name) => `competence-media/${name}`"
          :toggle-url="(asset) => `competence-media/${asset.id}/publish`"
        />
      </div>
      <form-buttons route="competences" :busy="busy" />
    </form>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Page from '@/components/ui/Page.vue';
import Tabs from '@/components/ui/Tabs.vue';
import FormButtons from '@/components/ui/FormButtons.vue';
import MultiImageUpload from '@/components/ui/MultiImageUpload.vue';
import api from '@/lib/api';
import { PANOPTIKUM_ID } from '@/lib/helpers';
import { useForm } from '@/composables/useForm';

const props = defineProps({ type: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const tabList = [
  { key: 'data', label: 'Daten' },
  { key: 'media', label: 'Bilder' },
];
const { errors, activeTab, tabErrors, clearError, changeTab, validateRequired, handleServerErrors } = useForm({
  tabs: ['data', 'media'],
  fieldTabs: { media: 'media' },
});

const competence = reactive({ title: null, description: null, category_id: null, subcategory_id: null, media: [] });
const categories = ref([]);
const subcategories = ref({});
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`competences/${route.params.id}`).then(({ data }) => Object.assign(competence, data, { media: data.media || [] }));
}
api.get('categories').then(({ data }) => (categories.value = data));
api.get('categories/subcategories').then(({ data }) => (subcategories.value = data));

async function submit() {
  if (!validateRequired(competence, ['title'])) return;
  busy.value = true;
  try {
    const payload = {
      ...competence,
      media: competence.media.map(({ id, name, caption, publish }) => ({ id, name, caption, publish: Number(publish) === 1 })),
    };
    if (props.type === 'edit') {
      await api.put(`competences/${route.params.id}`, payload);
    } else {
      await api.post('competences', payload);
    }
    router.push({ name: 'competences' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
