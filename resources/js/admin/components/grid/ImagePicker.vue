<template>
  <div>
    <!-- Project grid: images of this project -->
    <template v-if="mode === 'project'">
      <h1>Projektbild auswählen</h1>
      <div class="project-selector">
        <div class="project-selector__item">
          <div class="project-selector__media">
            <figure v-for="image in images" :key="image.id">
              <a v-if="image.is_crop" href="#" class="btn-trash-mini" title="Zuschnitt löschen" @click.prevent.stop="deleteCrop(image, images)"></a>
              <a href="#" @click.prevent="$emit('select', image.id)">
                <img :src="media.size(image.name, 'xs')" height="120" width="70" alt="" />
              </a>
            </figure>
          </div>
        </div>
        <p v-if="loaded && !images.length">Es sind keine publizierten Bilder vorhanden...</p>
      </div>
    </template>

    <!-- Home grid: choose a project first -->
    <template v-else>
      <div class="project-selector">
        <div class="select-wrapper">
          <select v-model="selectedProject" aria-label="Projekt wählen">
            <option :value="null">Projekt wählen...</option>
            <option v-for="p in projectsWithImages" :key="p.id" :value="p.id">{{ p.name }}</option>
          </select>
        </div>
        <div v-if="currentProject">
          <div class="project-selector__item is-multi">
            <div class="project-selector__media">
              <figure v-for="image in currentProject.images" :key="image.id">
                <a v-if="image.is_crop" href="#" class="btn-trash-mini" title="Zuschnitt löschen" @click.prevent.stop="deleteCrop(image, currentProject.images)"></a>
                <a href="#" @click.prevent="$emit('select', image.id)">
                  <img :src="media.size(image.name, 'xs')" height="50" width="50" alt="" />
                </a>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import api from '@/lib/api';
import { confirmDelete, media } from '@/lib/helpers';

const props = defineProps({
  mode: { type: String, required: true },
  projectId: { type: Number, default: null },
});
const emit = defineEmits(['select', 'deleted']);

const images = ref([]);
const projects = ref([]);
const loaded = ref(false);
const selectedProject = ref(null);

if (props.mode === 'project') {
  api.get(`projects/${props.projectId}/images`).then(({ data }) => {
    images.value = data;
    loaded.value = true;
  });
} else {
  api.get('projects', { params: { published: 1 } }).then(({ data }) => {
    projects.value = data;
    loaded.value = true;
  });
}

const projectsWithImages = computed(() => projects.value.filter((p) => p.images && p.images.length > 0));
const currentProject = computed(() => projectsWithImages.value.find((p) => p.id === selectedProject.value) || null);

async function deleteCrop(image, list) {
  if (!confirmDelete()) return;
  await api.delete(`project-images/cropped/${image.name}`);
  const index = list.findIndex((i) => i.id === image.id);
  if (index !== -1) list.splice(index, 1);
  emit('deleted');
}
</script>
