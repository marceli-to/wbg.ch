<template>
  <div>
    <div class="ratio-boxes" v-if="def">
      <div>
        <div :class="def.box">
          <template v-for="(col, i) in def.cols" :key="i">
            <div v-if="col.stack" class="span">
              <div class="grid-stacked">
                <grid-slot
                  v-for="box in col.stack"
                  :key="box.position"
                  :box="box"
                  :element="elements[box.position]"
                  :is-home="isHome"
                  @pick="openPicker"
                  @delete="deleteElement"
                  @cropped="fetch()"
                />
              </div>
            </div>
            <grid-slot
              v-else
              :box="col"
              :element="elements[col.position]"
              :is-home="isHome"
              @pick="openPicker"
              @delete="deleteElement"
              @cropped="fetch()"
            />
          </template>
        </div>
      </div>
    </div>
    <p v-else>Unbekanntes Layout «{{ layout }}»</p>

    <Teleport to="body">
      <div class="overlay is-visible" v-if="overlay.visible.value">
        <a href="#" @click.prevent="overlay.hide()" class="icon-close-overlay" aria-label="Schliessen"></a>
        <div>
          <image-picker
            v-if="picker === 'image'"
            :mode="mode"
            :project-id="projectId"
            @select="storeImage"
            @deleted="emit('images-deleted')"
          />
          <article-picker v-if="picker === 'article'" @select="storeArticle" />
        </div>
      </div>
    </Teleport>
  </div>
</template>
<script setup>
import { computed, ref, watch } from 'vue';
import GridSlot from '@/components/grid/GridSlot.vue';
import ImagePicker from '@/components/grid/ImagePicker.vue';
import ArticlePicker from '@/components/grid/ArticlePicker.vue';
import { LAYOUTS } from '@/components/grid/layouts';
import { MODES, normalizeElements } from '@/components/grid/modes';
import api from '@/lib/api';
import { success } from '@/lib/notify';
import { useOverlay } from '@/composables/useOverlay';

const props = defineProps({
  layout: { type: String, required: true },
  gridId: { type: Number, required: true },
  mode: { type: String, default: 'project' }, // project | home
  projectId: { type: Number, default: null },
  // changing this number makes the row re-fetch its elements
  refresh: { type: Number, default: 0 },
});
// images-deleted: a crop was deleted; it may be used in other rows too
const emit = defineEmits(['changed', 'images-deleted']);

const config = MODES[props.mode];
const isHome = config.isHome;
const def = computed(() => LAYOUTS[props.layout]);
const elements = ref({});

async function fetch() {
  const { data } = await api.get(config.elements(props.gridId));
  elements.value = normalizeElements(data, props.mode);
}
fetch();
watch(() => props.refresh, fetch);

// Picker overlay
const overlay = useOverlay();
const picker = ref(null);
const position = ref(null);

function openPicker(type, pos) {
  picker.value = type;
  position.value = pos;
  overlay.show();
}

async function store(data, message) {
  await api.post(config.elements(props.gridId), { position: position.value, ...data });
  overlay.hide();
  success(message);
  await fetch();
  emit('changed');
}

const storeImage = (imageId) => store({ project_image_id: imageId }, 'Bild hinzugefügt');
const storeArticle = (newsId) => store({ news_id: newsId }, 'Element hinzugefügt');

async function deleteElement(element, event) {
  if (config.confirmElementDelete && !window.confirm('Bitte löschen bestätigen!')) return;
  const el = event?.currentTarget || event?.target;
  el?.classList.add('is-loading');
  try {
    await api.delete(config.destroyElement(element.id));
    success(element.isMedia ? 'Bild gelöscht' : 'Element gelöscht');
    await fetch();
    emit('changed');
  } finally {
    el?.classList.remove('is-loading');
  }
}

</script>
