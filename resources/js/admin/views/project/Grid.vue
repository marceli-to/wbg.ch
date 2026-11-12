<template>
  <page>
    <h1>Layout für Projekt «{{ pageTitle }}»</h1>
    <a href="#" class="icon-layout" @click.prevent="view = view === 'grid' ? 'list' : 'grid'">
      <span v-if="view === 'grid'">Grid</span>
      <span v-else>Liste</span>
    </a>
    <layout-selector endpoint="grid-layouts" @select="addRow" />

    <div class="grid-rows" v-if="view === 'grid'">
      <div
        v-for="grid in grids"
        :key="grid.id"
        :class="['grid-row', removing.has(grid.id) ? 'fade-out' : '']"
      >
        <a href="#" class="btn-trash" @click.prevent="deleteRow(grid.id, $event)">Zeile löschen</a>
        <grid-row :layout="grid.layout.key" :grid-id="grid.id" :project-id="projectId" mode="project" :refresh="version" @images-deleted="version++" />
      </div>
    </div>

    <div class="grid-rows" v-else>
      <VueDraggable v-model="grids" @end="updateOrder" ghost-class="draggable-ghost" draggable=".grid-row">
        <div class="grid-row is-list is-draggable" v-for="grid in grids" :key="grid.id">
          <span class="icon-grid-list">
            <img :src="media.layoutIcon(grid.layout.key)" height="172" width="126" :alt="grid.layout.key" />
          </span>
        </div>
      </VueDraggable>
    </div>

    <footer class="form-footer">
      <div>
        <a :href="`/projekt/${projectId}`" class="btn-preview" target="_blank">Vorschau</a>
        <router-link :to="{ name: 'projects' }">Zurück</router-link>
      </div>
    </footer>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { useRoute } from 'vue-router';
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import GridRow from '@/components/grid/GridRow.vue';
import LayoutSelector from '@/components/grid/LayoutSelector.vue';
import api from '@/lib/api';
import { media, saveOrder, withLoading } from '@/lib/helpers';
import { success } from '@/lib/notify';

const route = useRoute();
const projectId = Number(route.params.id);
const pageTitle = ref('');
const grids = ref([]);
const view = ref('grid');
const removing = reactive(new Set());
// bumped to make the rows re-fetch their elements
const version = ref(0);

api.get(`projects/${projectId}`).then(({ data }) => (pageTitle.value = data.name));

async function fetch() {
  const { data } = await api.get(`projects/${projectId}/grids`);
  grids.value = data;
}
fetch();

async function addRow(layoutId) {
  const { data } = await api.post(`projects/${projectId}/grids`, { layout_id: layoutId });
  grids.value = data;
  success('Zeile hinzugefügt');
}

function deleteRow(id, event) {
  if (!window.confirm('Bitte löschen bestätigen!')) return;
  return withLoading(event, async () => {
    await api.delete(`grids/${id}`);
    removing.add(id);
    setTimeout(() => {
      grids.value = grids.value.filter((g) => g.id !== id);
      removing.delete(id);
      success('Zeile gelöscht');
    }, 200);
  });
}

async function updateOrder() {
  await saveOrder('grids/order', grids.value);
  success('Reihenfolge angepasst');
}
</script>
