<template>
  <page>
    <h1>{{ type === 'edit' ? 'Job bearbeiten' : 'Job hinzufügen' }}</h1>
    <form @submit.prevent="submit" novalidate>
      <div class="form-row" :class="{ 'has-error': errors.title }">
        <label for="title">Titel *</label>
        <input id="title" type="text" @focus="clearError('title')" v-model="job.title" />
      </div>
      <div class="form-row" :class="{ 'has-error': errors.text }">
        <label>Text</label>
        <rich-text-editor v-model="job.text" @focus="clearError('text')" />
      </div>
      <form-buttons route="jobs" :busy="busy" />
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

const job = reactive({ title: null, text: '' });
const busy = ref(false);

if (props.type === 'edit') {
  api.get(`jobs/${route.params.id}`).then(({ data }) => Object.assign(job, data));
}

async function submit() {
  if (!validateRequired(job, ['title'])) return;
  busy.value = true;
  try {
    if (props.type === 'edit') {
      await api.put(`jobs/${route.params.id}`, job);
    } else {
      await api.post('jobs', job);
    }
    router.push({ name: 'jobs' });
  } catch (err) {
    handleServerErrors(err);
  } finally {
    busy.value = false;
  }
}
</script>
