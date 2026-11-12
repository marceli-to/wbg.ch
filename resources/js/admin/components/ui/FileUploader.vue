<template>
  <div class="uploader">
    <div
      class="dropzone dz-clickable"
      :class="{ 'dz-drag-hover': dragging }"
      role="button"
      tabindex="0"
      @click="input?.click()"
      @keydown.enter.prevent="input?.click()"
      @keydown.space.prevent="input?.click()"
      @dragenter.prevent="dragging = true"
      @dragover.prevent="dragging = true"
      @dragleave.prevent="dragging = false"
      @drop.prevent="onDrop"
    >
      <div class="dz-message">
        <span>{{ multiple ? 'Dateien hierher ziehen oder klicken' : 'Datei hierher ziehen oder klicken' }}</span>
      </div>
      <input
        ref="input"
        type="file"
        class="visually-hidden"
        :accept="accept"
        :multiple="multiple"
        tabindex="-1"
        @change="onSelect"
      />
    </div>
    <ul class="uploader-queue" v-if="queue.length">
      <li v-for="item in queue" :key="item.id" :class="{ 'has-error': item.error }">
        <span class="uploader-queue__name">{{ item.name }}</span>
        <span class="uploader-queue__progress" v-if="!item.error">
          <span :style="{ width: item.progress + '%' }"></span>
        </span>
        <span class="uploader-queue__error" v-else>{{ item.error }}</span>
        <a
          v-if="item.error"
          href="#"
          class="uploader-queue__remove"
          aria-label="Entfernen"
          @click.prevent="remove(item.id)"
        >×</a>
      </li>
    </ul>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import api, { errorMessage } from '@/lib/api';

/**
 * Upload component (replaces vue2-dropzone). Posts each file as multipart
 * field "file" and emits "uploaded" with the server response
 * ({name, filetype}).
 */
const props = defineProps({
  url: { type: String, default: 'uploads/image' },
  accept: { type: String, default: '.jpg,.jpeg,.png' },
  extensions: { type: Array, default: () => ['jpg', 'jpeg', 'png'] },
  maxFilesize: { type: Number, default: 20 }, // MB
  multiple: { type: Boolean, default: false },
});

const emit = defineEmits(['uploaded']);

const input = ref(null);
const dragging = ref(false);
const queue = ref([]);
let nextId = 1;

function onSelect(event) {
  handle(Array.from(event.target.files || []));
  event.target.value = '';
}

function onDrop(event) {
  dragging.value = false;
  handle(Array.from(event.dataTransfer?.files || []));
}

function handle(files) {
  if (!props.multiple) {
    files = files.slice(0, 1);
  }
  files.forEach(upload);
}

function remove(id) {
  queue.value = queue.value.filter((item) => item.id !== id);
}

function validate(file) {
  const ext = file.name.split('.').pop().toLowerCase();
  if (!props.extensions.includes(ext)) {
    return `Ungültiges Dateiformat (erlaubt: ${props.extensions.join(', ')}).`;
  }
  if (file.size > props.maxFilesize * 1024 * 1024) {
    return `Die Datei ist zu gross (max. ${props.maxFilesize} MB).`;
  }
  return null;
}

async function upload(file) {
  queue.value.push({ id: nextId++, name: file.name, progress: 0, error: null });
  // work on the reactive proxy, not the raw object
  const item = queue.value[queue.value.length - 1];

  item.error = validate(file);
  if (item.error) return;

  const data = new FormData();
  data.append('file', file);

  try {
    const response = await api.post(props.url, data, {
      silent: true,
      onUploadProgress: (e) => {
        if (e.total) item.progress = Math.round((e.loaded / e.total) * 100);
      },
    });
    remove(item.id);
    emit('uploaded', response.data);
  } catch (err) {
    item.error = err?.response?.status === 413
      ? `Die Datei ist zu gross (max. ${props.maxFilesize} MB).`
      : errorMessage(err, 'Upload fehlgeschlagen.');
  }
}
</script>
