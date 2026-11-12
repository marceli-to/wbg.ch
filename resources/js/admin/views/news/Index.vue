<template>
  <page>
    <h1>Artikel</h1>
    <router-link :to="{ name: 'article-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <div
        v-for="n in items"
        :key="n.id"
        :class="[n.publish == 0 ? 'is-disabled' : '', 'list-item']"
      >
        <div class="list-item-body">
          <h3>{{ n.title }}</h3>
        </div>
        <list-actions
          :published="n.publish == 1"
          :edit="{ name: 'article-edit', params: { id: n.id } }"
          @toggle="toggle(n.id, $event)"
          @clone="clone(n.id, $event)"
          @destroy="destroy(n.id, $event)"
        />
      </div>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Artikel vorhanden...</p>
    </div>
  </page>
</template>
<script setup>
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useResourceList } from '@/composables/useResourceList';

// Deleting an article that is used on the home page returns a 422 with a
// message; the API interceptor shows it.
const { items, loaded, fetch, toggle, clone, destroy } = useResourceList('news');
fetch();
</script>
