<template>
  <page>
    <h1>Kategorien</h1>
    <router-link :to="{ name: 'category-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <VueDraggable v-model="items" @end="updateOrder()" ghost-class="draggable-ghost">
        <div
          v-for="category in items"
          :key="category.id"
          :class="[category.publish == 0 ? 'is-disabled' : '', 'list-item', 'is-sortable']"
        >
          <div class="list-item-body">
            <h3>{{ category.name }}</h3>
          </div>
          <list-actions
            :published="category.publish == 1"
            :edit="{ name: 'category-edit', params: { id: category.id } }"
            @toggle="toggle(category.id, $event)"
            @clone="clone(category.id, $event)"
            @destroy="destroy(category.id, $event)"
          />
        </div>
      </VueDraggable>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Kategorien vorhanden...</p>
    </div>
  </page>
</template>
<script setup>
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useResourceList } from '@/composables/useResourceList';

const { items, loaded, fetch, toggle, clone, destroy, updateOrder } = useResourceList('categories');
fetch();
</script>
