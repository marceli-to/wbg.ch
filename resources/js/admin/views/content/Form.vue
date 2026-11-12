<template>
  <page>
    <h1>{{ type === 'edit' ? 'Text bearbeiten' : 'Text hinzufügen' }}</h1>
    <form @submit.prevent="submit" novalidate>
      <div class="form-row" :class="{ 'has-error': errors.key }">
        <label for="key">Seite</label>
        <div class="select-wrapper">
          <select id="key" v-model="content.key" @focus="clearError('key')">
            <option :value="null">Bitte wählen...</option>
            <option v-for="k in keys" :key="k.key" :value="k.key">{{ k.page }}</option>
          </select>
        </div>
      </div>
      <div class="form-row" :class="{ 'has-error': errors.text }">
        <label>Text</label>
        <rich-text-editor v-model="content.text" height="360px" @focus="clearError('text')" />
      </div>
      <form-buttons route="contents" :busy="busy" />
    </form>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Page from '@/components/ui/Page.vue';
import FormButtons from '@/components/ui/FormButtons.vue';
import RichTextEditor from '@/components/ui/RichTextEditor.vue';
import api from '@/lib/api';
import { useForm } from '@/composables/useForm';

const props = defineProps({ type: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const { errors, clearError, validateRequired, handleServerErrors } = useForm();

const content = reactive({ key: null, text: '' });
const keys = ref([]);
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`contents/${route.params.id}`).then(({ data }) => Object.assign(content, data));
}
api.get('contents/keys').then(({ data }) => (keys.value = data));

async function submit() {
  if (!validateRequired(content, ['key', 'text'])) return;
  busy.value = true;
  try {
    if (props.type === 'edit') {
      await api.put(`contents/${route.params.id}`, content);
    } else {
      await api.post('contents', content);
    }
    router.push({ name: 'contents' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
