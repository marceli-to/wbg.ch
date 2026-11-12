<template>
  <page>
    <h1>Projekte</h1>
    <router-link :to="{ name: 'project-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <div v-for="group in groups" :key="`${group.key}-${build}`">
        <div class="list-items">
          <div class="list-item-group">
            <h2>{{ group.name }}</h2>
            <VueDraggable
              v-model="group.projects"
              :disabled="!!search"
              @end="updateGroupOrder(group)"
              ghost-class="draggable-ghost"
            >
              <div v-for="project in group.projects" :key="project.id">
                <div
                  :class="[project.publish == 0 ? 'is-disabled' : '', 'list-item', 'list-item--project', search ? '' : 'is-sortable']"
                  data-icons="6"
                >
                  <div class="list-item-body">
                    <h3>{{ project.name }}</h3>
                    <span v-if="project.principal">Auftraggeber: {{ project.principal }}</span>
                    <span v-if="project.client">Kunde: {{ project.client.name }}</span>
                    <span class="bubble is-info" v-if="project.subcategory_id">{{ subcategories[project.subcategory_id] }}</span>
                  </div>
                  <list-actions
                    icons="6"
                    :published="project.publish == 1"
                    :edit="{ name: 'project-edit', params: { id: project.id } }"
                    @toggle="toggle(project.id, $event)"
                    @clone="clone(project.id, $event)"
                    @destroy="destroy(project.id, $event)"
                  >
                    <template #before>
                      <a :href="`/projekt/${project.id}`" target="_blank" class="icon-external-link icon-mini" title="Vorschau"></a>
                      <router-link
                        :to="{ name: 'project-grids', params: { id: project.id } }"
                        :class="[project.images.length > 0 ? '' : 'is-disabled', 'icon-grid icon-mini']"
                        title="Layout"
                      ></router-link>
                    </template>
                  </list-actions>
                </div>
              </div>
            </VueDraggable>
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Projekte vorhanden...</p>
    </div>
    <footer class="form-footer">
      <div>
        <div class="search-wrapper">
          <a href="#" class="icon-delete" v-if="search" aria-label="Suche leeren" @click.prevent="search = ''"></a>
          <input type="text" class="search" v-model="search" placeholder="Filter nach Projektname, Kunde oder Kategorie" />
        </div>
      </div>
    </footer>
  </page>
</template>
<script setup>
import { ref, watch } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import api from '@/lib/api';
import { saveOrder } from '@/lib/helpers';
import { success } from '@/lib/notify';
import { useResourceList } from '@/composables/useResourceList';

// clone copies images/grids/relations -> reload the list afterwards
const { items, loaded, fetch, toggle, clone, destroy } = useResourceList('projects', { refetchAfterClone: true });
fetch();

const subcategories = ref({});
api.get('categories/subcategories').then(({ data }) => (subcategories.value = data));

const search = ref('');
const groups = ref([]);
// Sortable keeps the listeners of its first render: re-mount the groups
// whenever they are rebuilt so they never work on stale arrays
const build = ref(0);

/**
 * Group the (filtered) projects by category, categories in their sort order.
 * Projects are sorted within their category.
 */
function buildGroups() {
  const term = search.value.toLowerCase();
  const matches = (p) =>
    !term ||
    (p.name || '').toLowerCase().includes(term) ||
    (p.client?.name || '').toLowerCase().includes(term) ||
    (p.category?.name || '').toLowerCase().includes(term);

  const map = new Map();
  for (const project of items.value.filter(matches)) {
    const key = project.category ? project.category.id : 'none';
    if (!map.has(key)) {
      map.set(key, {
        key,
        name: project.category ? project.category.name : 'Ohne Kategorie',
        order: project.category ? project.category.order : Number.MAX_SAFE_INTEGER,
        projects: [],
      });
    }
    map.get(key).projects.push(project);
  }
  // keep the drag & drop order (saveOrder updates item.order in place)
  map.forEach((group) => group.projects.sort((a, b) => (a.order ?? 0) - (b.order ?? 0)));
  groups.value = [...map.values()].sort((a, b) => a.order - b.order);
  build.value++;
}

watch([items, search], buildGroups, { deep: false });

async function updateGroupOrder(group) {
  await saveOrder('projects/order', group.projects);
  success('Reihenfolge angepasst');
}
</script>
