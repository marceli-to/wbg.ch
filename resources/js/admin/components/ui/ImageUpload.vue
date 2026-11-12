<template>
  <div>
    <div class="form-row" v-if="!modelValue">
      <label>{{ labelNew }}</label>
      <file-uploader @uploaded="(file) => emit('update:modelValue', file.name)" />
      <span class="bubble is-restriction">{{ labelRestrictions }}</span>
    </div>
    <div class="form-row" v-else>
      <label>{{ labelExisting }}</label>
      <div class="dropzone-existing-assets">
        <div>
          <figure class="is-image dz-existing-asset">
            <a :href="media.size(modelValue, 'sm')" target="_blank" class="dz-file-preview">
              <img :src="media.thumbnail(modelValue)" height="300" width="300" alt="" />
            </a>
            <div class="dz-toolbar">
              <a :href="media.size(modelValue, 'sm')" target="_blank" class="icon-external-link icon-mini" title="Öffnen"></a>
              <a href="#" class="icon-trash icon-mini" title="Löschen" @click.prevent="remove($event)"></a>
            </div>
          </figure>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import FileUploader from '@/components/ui/FileUploader.vue';
import api from '@/lib/api';
import { confirmDelete, media, withLoading } from '@/lib/helpers';

/**
 * Single image (e.g. team portrait). v-model is the file name.
 */
const props = defineProps({
  modelValue: { type: String, default: null },
  deleteUrl: { type: Function, required: true }, // (name) => endpoint
  labelNew: { type: String, default: 'Bild hochladen' },
  labelExisting: { type: String, default: 'Vorhandenes Bild' },
  labelRestrictions: { type: String, default: 'jpg, png | max. 20 MB' },
});
const emit = defineEmits(['update:modelValue']);

function remove(event) {
  if (!confirmDelete()) return;
  return withLoading(event, async () => {
    await api.delete(props.deleteUrl(props.modelValue));
    emit('update:modelValue', null);
  });
}
</script>
