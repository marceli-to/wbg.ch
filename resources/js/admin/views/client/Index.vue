<template>
  <page>
    <h1>Kunden</h1>
    <router-link :to="{ name: 'client-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <div
        v-for="client in filtered"
        :key="client.id"
        :class="[client.publish == 0 ? 'is-disabled' : '', 'list-item']"
      >
        <div class="list-item-body">
          <strong>{{ client.name }}</strong><span style="display:inline" v-if="client.location">, {{ client.location }}</span>
        </div>
        <list-actions
          :published="client.publish == 1"
          :edit="{ name: 'client-edit', params: { id: client.id } }"
          @toggle="toggle(client.id, $event)"
          @clone="clone(client.id, $event)"
          @destroy="destroy(client.id, $event)"
        />
      </div>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Kunden vorhanden...</p>
    </div>
    <footer class="form-footer">
      <div>
        <div class="search-wrapper">
          <a href="#" class="icon-delete" v-if="search" aria-label="Suche leeren" @click.prevent="search = ''"></a>
          <input type="text" class="search" v-model="search" placeholder="Suche nach Name" />
        </div>
      </div>
    </footer>
  </page>
</template>
<script setup>
import { computed, ref } from 'vue';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useResourceList } from '@/composables/useResourceList';

const { items, loaded, fetch, toggle, clone, destroy } = useResourceList('clients', { cloneAt: 'start' });
fetch();

const search = ref('');
const filtered = computed(() => {
  const term = search.value.toLowerCase();
  return items.value.filter((c) => (c.name || '').toLowerCase().includes(term));
});
</script>
