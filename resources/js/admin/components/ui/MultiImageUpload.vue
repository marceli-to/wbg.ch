<template>
  <div>
    <div class="form-row">
      <label>{{ labelNew }}</label>
      <file-uploader multiple @uploaded="add" />
      <span class="bubble is-restriction">{{ labelRestrictions }}</span>
    </div>
    <div class="form-row" v-if="modelValue.length">
      <label>{{ labelExisting }}</label>
      <div class="has-images dropzone-existing-assets">
        <div>
          <figure
            v-for="asset in modelValue"
            :key="asset.id || asset.name"
            :class="[asset.publish == 0 ? 'is-disabled' : '', 'dz-existing-asset is-image']"
          >
            <a :href="media.size(asset.name, 'sm')" target="_blank" class="dz-file-preview">
              <img :src="media.thumbnail(asset.name)" height="300" width="300" alt="" />
              <div class="dz-file-preview__caption" v-if="asset.caption">{{ truncate(asset.caption, 20) }}</div>
            </a>
            <div class="dz-toolbar">
              <a
                href="#"
                :class="[asset.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                :title="asset.publish == 1 ? 'Ausblenden' : 'Publizieren'"
                @click.prevent="toggle(asset, 'publish', $event)"
              ></a>
              <a href="#" class="icon-edit icon-mini" title="Bearbeiten" @click.prevent="editing = asset"></a>
              <a :href="media.size(asset.name, 'sm')" target="_blank" class="icon-external-link icon-mini" title="Öffnen"></a>
              <a href="#" class="icon-trash icon-mini" title="Löschen" @click.prevent="remove(asset, $event)"></a>
              <a
                v-if="hasStar"
                href="#"
                :class="[asset.is_preview == 1 ? 'icon-star' : 'icon-star-off', 'icon-mini']"
                title="Vorschaubild"
                @click.prevent="toggle(asset, 'is_preview', $event)"
              ></a>
              <a
                v-if="hasStarRelated"
                href="#"
                :class="[asset.is_preview_related == 1 ? 'icon-star-related' : 'icon-star-off', 'icon-mini']"
                title="Vorschaubild verwandte Projekte"
                @click.prevent="toggle(asset, 'is_preview_related', $event)"
              ></a>
            </div>
          </figure>
        </div>
      </div>
    </div>

    <Teleport to="body">
      <div class="overlay-asset is-visible" v-if="editing" @keydown.esc="editing = null">
        <div>
          <div class="overlay-grid">
            <div>
              <img :src="media.size(editing.name, 'sm')" height="300" width="300" alt="" />
              <figcaption>{{ editing.caption }}</figcaption>
            </div>
            <div>
              <div class="form-row">
                <label>Name:</label>
                <div>{{ editing.name }}</div>
              </div>
              <div class="form-row">
                <label :for="`caption-${uid}`">Legende:</label>
                <input :id="`caption-${uid}`" type="text" v-model="editing.caption" class="is-caption" />
              </div>
              <div class="form-row" v-if="hasUrl">
                <label :for="`url-${uid}`">Link:</label>
                <input :id="`url-${uid}`" type="text" v-model="editing.url" class="is-caption" />
              </div>
              <div class="form-row" v-if="hasClients">
                <label :for="`client-${uid}`">Kunde</label>
                <div class="select-wrapper is-wide">
                  <select :id="`client-${uid}`" v-model="editing.client_id">
                    <option :value="null">Bitte wählen...</option>
                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                  </select>
                </div>
              </div>
              <div class="form-row-button">
                <a href="#" class="btn-secondary" @click.prevent="editing = null">Speichern</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
<script setup>
import { onBeforeUnmount, ref, watch, useId } from 'vue';
import FileUploader from '@/components/ui/FileUploader.vue';
import api from '@/lib/api';
import { confirmDelete, media, truncate, withLoading } from '@/lib/helpers';

/**
 * Image list with upload (competence media, project images).
 *
 * v-model is the array of assets. New uploads get id=null and are saved
 * together with the parent form; flags of saved assets are toggled right
 * away through the API (same as the old admin).
 */
const props = defineProps({
  modelValue: { type: Array, required: true },
  defaults: { type: Object, default: () => ({}) },
  deleteUrl: { type: Function, required: true }, // (name) => endpoint
  toggleUrl: { type: Function, required: true }, // (asset, flag) => endpoint
  labelNew: { type: String, default: 'Bilder hochladen' },
  labelExisting: { type: String, default: 'Vorhandene Bilder' },
  labelRestrictions: { type: String, default: 'jpg, png | max. 20 MB' },
  hasStar: Boolean,
  hasStarRelated: Boolean,
  hasUrl: Boolean,
  hasClients: Boolean,
  clients: { type: Array, default: () => [] },
});
const emit = defineEmits(['update:modelValue']);

const uid = useId();
const editing = ref(null);

// lock the page scroll while the edit overlay is open
watch(editing, (asset) => document.documentElement.classList.toggle('has-overlay', !!asset));
onBeforeUnmount(() => editing.value && document.documentElement.classList.remove('has-overlay'));

function add(file) {
  emit('update:modelValue', [
    ...props.modelValue,
    { id: null, name: file.name, caption: null, publish: 1, ...props.defaults },
  ]);
}

function toggle(asset, flag, event) {
  if (!asset.id) {
    asset[flag] = asset[flag] == 1 ? 0 : 1;
    return;
  }
  return withLoading(event, async () => {
    const { data } = await api.patch(props.toggleUrl(asset, flag));
    asset[flag] = data.value;
  });
}

function remove(asset, event) {
  if (!confirmDelete()) return;
  return withLoading(event, async () => {
    await api.delete(props.deleteUrl(asset.name));
    emit('update:modelValue', props.modelValue.filter((a) => a.name !== asset.name));
  });
}
</script>
