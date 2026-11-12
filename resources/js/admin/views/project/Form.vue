<template>
  <page>
    <h1>{{ type === 'edit' ? 'Projekt bearbeiten' : 'Projekt hinzufügen' }}</h1>
    <tabs :tabs="tabList" :active="activeTab" :errors="tabErrors" @change="changeTab" />
    <form @submit.prevent="submit" novalidate>
      <div v-show="activeTab === 'data'">
        <div class="form-row" :class="{ 'has-error': errors.name }">
          <label for="name">Name *</label>
          <input id="name" type="text" @focus="clearError('name')" v-model="project.name" />
        </div>
        <div class="form-row" :class="{ 'has-error': errors.principal }">
          <label for="principal">Auftraggeber</label>
          <input id="principal" type="text" @focus="clearError('principal')" v-model="project.principal" />
        </div>
        <div class="form-row" :class="{ 'has-error': errors.description }">
          <label>Beschreibung</label>
          <rich-text-editor v-model="project.description" @focus="clearError('description')" />
        </div>
        <div class="form-row" :class="{ 'has-error': errors.meta_description }">
          <label for="meta_description">SEO-Text</label>
          <textarea id="meta_description" @focus="clearError('meta_description')" v-model="project.meta_description" rows="15"></textarea>
        </div>
        <div class="form-row" :class="{ 'has-error': errors.client_id }">
          <label for="client_id">Kunde *</label>
          <div class="select-wrapper is-wide">
            <select id="client_id" v-model="project.client_id" @focus="clearError('client_id')">
              <option :value="null">Bitte wählen...</option>
              <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
        </div>
        <div class="form-row" :class="{ 'has-error': errors.category_id }">
          <label for="category_id">Kategorie *</label>
          <div class="select-wrapper is-wide">
            <select id="category_id" v-model="project.category_id" @focus="clearError('category_id')">
              <option :value="null">Bitte wählen...</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
        </div>
        <div class="form-row" v-show="project.category_id == PANOPTIKUM_ID">
          <label for="subcategory_id">Subkategorie (Panoptikum)</label>
          <div class="select-wrapper is-wide">
            <select id="subcategory_id" v-model="project.subcategory_id">
              <option :value="null">Bitte wählen...</option>
              <option v-for="(label, key) in subcategories" :key="key" :value="Number(key)">{{ label }}</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <label class="is-sm">Logo Projekt?</label>
          <div class="form-radio">
            <input v-model="project.is_brands" type="radio" id="is_brands_1" :value="1" class="visually-hidden" />
            <label for="is_brands_1" class="form-control">Ja</label>
            <input v-model="project.is_brands" type="radio" id="is_brands_0" :value="0" class="visually-hidden" />
            <label for="is_brands_0" class="form-control">Nein</label>
          </div>
        </div>
        <div class="form-row" :class="{ 'has-error': errors.video }">
          <label for="video">Video</label>
          <input id="video" type="text" @focus="clearError('video')" v-model="project.video" />
        </div>
        <div class="form-row" :class="{ 'has-error': errors.video_link }">
          <label for="video_link">Video Verweis</label>
          <input id="video_link" type="text" @focus="clearError('video_link')" v-model="project.video_link" />
        </div>
      </div>

      <div v-show="activeTab === 'media'">
        <div style="position:relative">
          <multi-image-upload
            v-model="project.images"
            :defaults="{ is_preview: 0, is_preview_related: 0, client_id: null, url: null }"
            :delete-url="(name) => `project-images/${name}`"
            :toggle-url="(asset, flag) => `project-images/${asset.id}/${flag}`"
            has-star
            has-star-related
            has-url
            has-clients
            :clients="clients"
          />
        </div>
      </div>

      <div v-if="type === 'edit'" v-show="activeTab === 'relations'">
        <div class="project-relations">
          <label for="related_project_id">Relation hinzufügen</label>
          <div class="project-relations__grid">
            <div class="select-wrapper">
              <select id="related_project_id" v-model="relation">
                <option :value="null">Bitte wählen...</option>
                <option v-for="p in relatable" :key="p.id" :value="p.id">{{ p.name }}{{ p.category ? ` – ${p.category.name}` : '' }}</option>
              </select>
            </div>
            <div>
              <a href="#" @click.prevent="addRelation($event)" class="btn-primary">Hinzufügen</a>
            </div>
          </div>
        </div>
        <div class="list-items" v-if="project.relations.length">
          <label>Vorhandene Relationen</label>
          <div
            v-for="r in project.relations"
            :key="r.id"
            :class="[r.related && r.related.publish == 0 ? 'is-disabled' : '', 'list-item']"
          >
            <div class="list-item-body">
              {{ r.related ? r.related.name : '–' }}
            </div>
            <div class="list-item-action" data-icons="1">
              <a href="#" @click.prevent="destroyRelation(r.id, $event)" class="icon-trash icon-mini" title="Löschen"></a>
            </div>
          </div>
        </div>
      </div>
      <form-buttons route="projects" :busy="busy" />
    </form>
  </page>
</template>
<script setup>
import { computed, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Page from '@/components/ui/Page.vue';
import Tabs from '@/components/ui/Tabs.vue';
import FormButtons from '@/components/ui/FormButtons.vue';
import MultiImageUpload from '@/components/ui/MultiImageUpload.vue';
import RichTextEditor from '@/components/ui/RichTextEditor.vue';
import api, { errorMessage } from '@/lib/api';
import { error as notifyError, success } from '@/lib/notify';
import { PANOPTIKUM_ID, confirmDelete, withLoading } from '@/lib/helpers';
import { useForm } from '@/composables/useForm';

const props = defineProps({ type: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const tabList = computed(() => [
  { key: 'data', label: 'Daten' },
  { key: 'media', label: 'Bilder' },
  ...(props.type === 'edit' ? [{ key: 'relations', label: 'Relationen' }] : []),
]);
const { errors, activeTab, tabErrors, clearError, changeTab, validateRequired, handleServerErrors } = useForm({
  tabs: ['data', 'media', 'relations'],
  fieldTabs: { images: 'media' },
});

const project = reactive({
  id: null,
  name: null,
  principal: null,
  description: '',
  meta_description: null,
  category_id: null,
  subcategory_id: null,
  client_id: null,
  is_brands: 0,
  video: null,
  video_link: null,
  images: [],
  relations: [],
});

const categories = ref([]);
const clients = ref([]);
const projects = ref([]);
const subcategories = ref({});
const relation = ref(null);
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`projects/${route.params.id}`).then(({ data }) => {
    Object.assign(project, data, {
      is_brands: Number(data.is_brands) || 0,
      // only original images, crops are managed in the grid editor
      images: data.original_images || [],
      relations: data.relations || [],
    });
  });
}
api.get('categories').then(({ data }) => (categories.value = data));
api.get('categories/subcategories').then(({ data }) => (subcategories.value = data));
api.get('clients').then(({ data }) => (clients.value = data));
if (props.type === 'edit') {
  api.get('projects', { params: { published: 1 } }).then(({ data }) => (projects.value = data));
}

// Projects that can still be added as relation
const relatable = computed(() => {
  const taken = new Set(project.relations.map((r) => r.related_project_id));
  return projects.value.filter((p) => p.id !== project.id && !taken.has(p.id));
});

async function submit() {
  if (!validateRequired(project, ['name', 'category_id', 'client_id'])) return;
  busy.value = true;
  try {
    const payload = {
      name: project.name,
      principal: project.principal,
      description: project.description,
      meta_description: project.meta_description,
      video: project.video,
      video_link: project.video_link,
      category_id: project.category_id,
      subcategory_id: project.category_id == PANOPTIKUM_ID ? project.subcategory_id : null,
      client_id: project.client_id,
      is_brands: Number(project.is_brands) === 1,
      images: project.images.map((img) => ({
        id: img.id,
        name: img.name,
        caption: img.caption,
        url: img.url,
        client_id: img.client_id || null,
        publish: Number(img.publish) === 1,
        is_preview: Number(img.is_preview) === 1,
        is_preview_related: Number(img.is_preview_related) === 1,
      })),
    };
    if (props.type === 'edit') {
      await api.put(`projects/${route.params.id}`, payload);
    } else {
      await api.post('projects', payload);
    }
    router.push({ name: 'projects' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}

function addRelation(event) {
  if (!relation.value) return;
  return withLoading(event, async () => {
    try {
      const { data } = await api.post(`projects/${project.id}/relations`, { related_project_id: relation.value }, { silent: true });
      project.relations.push(data);
      relation.value = null;
      success('Relation hinzugefügt');
    } catch (err) {
      notifyError(errorMessage(err));
    }
  });
}

function destroyRelation(id, event) {
  if (!confirmDelete()) return;
  return withLoading(event, async () => {
    await api.delete(`project-relations/${id}`);
    project.relations = project.relations.filter((r) => r.id !== id);
    success('Eintrag gelöscht');
  });
}
</script>
