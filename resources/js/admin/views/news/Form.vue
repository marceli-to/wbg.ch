<template>
  <page>
    <h1>{{ type === 'edit' ? 'Artikel bearbeiten' : 'Artikel hinzufügen' }}</h1>
    <form @submit.prevent="submit" novalidate>
      <div class="form-row" :class="{ 'has-error': errors.title }">
        <label for="title">Titel *</label>
        <input id="title" type="text" @focus="clearError('title')" v-model="news.title" />
      </div>
      <div class="form-row" :class="{ 'has-error': errors.text }">
        <label for="text">Text</label>
        <textarea id="text" v-model="news.text" rows="5" @focus="clearError('text')"></textarea>
      </div>
      <div class="form-row" :class="{ 'has-error': errors.linkInternal }">
        <label for="linkInternal">Link (Kompetenzen)</label>
        <div class="select-wrapper">
          <select id="linkInternal" v-model="news.linkInternal" @focus="clearError('linkInternal')">
            <option :value="null">Bitte wählen...</option>
            <option v-for="c in competences" :key="c.id" :value="c.id">{{ c.title }}</option>
          </select>
        </div>
      </div>
      <div class="form-row" :class="{ 'has-error': errors.link }">
        <label for="link">
          Link
          <a :href="previewLink" target="_blank" class="icon-external-link is-sm icon-mini" v-if="previewLink"></a>
        </label>
        <input id="link" type="text" v-model="news.link" placeholder="https://test.ch/" @focus="clearError('link')" @blur="fixUri" />
      </div>
      <div class="form-row" :class="{ 'has-error': errors.linkText }">
        <label for="linkText">Link Text</label>
        <input id="linkText" type="text" v-model="news.linkText" @focus="clearError('linkText')" />
      </div>
      <div class="form-row">
        <label class="is-sm">Link in neuem Fenster öffnen?</label>
        <div class="form-radio">
          <input v-model="news.linkNewWindow" type="radio" id="link_new_window_1" :value="1" class="visually-hidden" />
          <label for="link_new_window_1" class="form-control">Ja</label>
          <input v-model="news.linkNewWindow" type="radio" id="link_new_window_0" :value="0" class="visually-hidden" />
          <label for="link_new_window_0" class="form-control">Nein</label>
        </div>
      </div>
      <form-buttons route="articles" :busy="busy" />
    </form>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Page from '@/components/ui/Page.vue';
import FormButtons from '@/components/ui/FormButtons.vue';
import api from '@/lib/api';
import { useForm } from '@/composables/useForm';

const props = defineProps({ type: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const { errors, clearError, validateRequired, handleServerErrors } = useForm();

const news = reactive({ title: null, text: null, link: null, linkInternal: null, linkText: null, linkNewWindow: 0 });
const competences = ref([]);
const previewLink = ref(null);
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`news/${route.params.id}`).then(({ data }) => {
    Object.assign(news, data);
    news.linkNewWindow = Number(data.linkNewWindow) || 0;
    fixUri();
  });
}
api.get('competences').then(({ data }) => (competences.value = data));

// Add a scheme to the link and update the preview (mail addresses -> mailto:)
function fixUri() {
  const link = (news.link || '').trim();
  if (!link) {
    news.link = null;
    previewLink.value = null;
    return;
  }
  if (link.includes('@')) {
    news.link = link;
    previewLink.value = `mailto:${link}`;
  } else if (!/^(https?|ftp):\/\//.test(link)) {
    news.link = `http://${link}`;
    previewLink.value = news.link;
  } else {
    news.link = link;
    previewLink.value = link;
  }
}

async function submit() {
  if (!validateRequired(news, ['title'])) return;
  busy.value = true;
  try {
    if (props.type === 'edit') {
      await api.put(`news/${route.params.id}`, news);
    } else {
      await api.post('news', news);
    }
    router.push({ name: 'articles' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
