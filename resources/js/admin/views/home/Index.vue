<template>
  <page>
    <a href="#" class="icon-layout is-home" @click.prevent="view = view === 'grid' ? 'list' : 'grid'">
      <span v-if="view === 'grid'">Grid</span>
      <span v-else>Liste</span>
    </a>
    <layout-selector endpoint="home-grid-layouts" @select="addRow" />

    <div class="grid-rows" v-if="view === 'grid'">
      <div
        v-for="grid in grids"
        :key="grid.id"
        :class="['grid-row', removing.has(grid.id) ? 'fade-out' : '']"
      >
        <a href="#" class="btn-trash" @click.prevent="deleteRow(grid.id, $event)">Zeile löschen</a>
        <grid-row :layout="grid.layout.key" :grid-id="grid.id" mode="home" @changed="hasChanges = true" :refresh="version" @images-deleted="version++" />
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
        <a href="/" class="btn-preview" target="_blank">Vorschau</a>
      </div>
    </footer>
    <footer :class="[hasChanges ? '' : 'is-hidden', 'form-footer is-warning']">
      <div>
        <div class="fs-xs">
          Die Seite hat nicht publizierte Änderungen. Damit diese auf der Webseite sichtbar werden, muss das aktuelle Layout publiziert werden.
        </div>
        <div>
          <button type="button" class="btn-secondary" @click="deploy($event)">Änderungen publizieren</button>
        </div>
        <div>
          <button type="button" class="btn-primary" @click="reset($event)">Änderungen verwerfen</button>
        </div>
      </div>
    </footer>
  </page>
</template>
<script setup>
import { reactive, ref } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import GridRow from '@/components/grid/GridRow.vue';
import LayoutSelector from '@/components/grid/LayoutSelector.vue';
import api from '@/lib/api';
import { media, saveOrder, withLoading } from '@/lib/helpers';
import { success } from '@/lib/notify';

const grids = ref([]);
const view = ref('grid');
const hasChanges = ref(false);
const removing = reactive(new Set());
// bumped to make the rows re-fetch their elements (after a reset)
const version = ref(0);

/**
 * Staged changes = elements added (environment=development) or live
 * elements marked for deletion (action=delete).
 */
function detectChanges(rows) {
  return rows.some((row) =>
    (row.elements || []).some((el) => el.environment === 'development' || el.action === 'delete'),
  );
}

async function fetch() {
  const { data } = await api.get('home-grids');
  grids.value = data;
  hasChanges.value = detectChanges(data);
}
fetch();

async function addRow(layoutId) {
  const { data } = await api.post('home-grids', { layout_id: layoutId });
  grids.value = data;
  success('Zeile hinzugefügt!');
}

function deleteRow(id, event) {
  if (!window.confirm('Bitte löschen bestätigen!')) return;
  return withLoading(event, async () => {
    await api.delete(`home-grids/${id}`);
    removing.add(id);
    setTimeout(() => {
      grids.value = grids.value.filter((g) => g.id !== id);
      removing.delete(id);
      success('Zeile gelöscht!');
    }, 200);
  });
}

async function updateOrder() {
  await saveOrder('home-grids/order', grids.value);
  success('Reihenfolge angepasst');
}

function deploy(event) {
  if (!window.confirm('Bitte publizieren bestätigen.')) return;
  return withLoading(event, async () => {
    await api.post('home-grids/deploy');
    hasChanges.value = false;
    success('Seite publiziert!');
  });
}

function reset(event) {
  if (!window.confirm('Bitte zurücksetzen bestätigen.')) return;
  return withLoading(event, async () => {
    await api.post('home-grids/reset');
    await fetch();
    version.value++;
    success('Änderungen verworfen!');
  });
}
</script>
