<template>
  <page>
    <h1>Job</h1>
    <router-link :to="{ name: 'job-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <VueDraggable v-model="items" @end="updateOrder()" ghost-class="draggable-ghost">
        <div
          v-for="j in items"
          :key="j.id"
          :class="[j.publish == 0 ? 'is-disabled' : '', 'list-item', 'is-sortable']"
        >
          <div class="list-item-body">
            <h3>{{ j.title }}</h3>
          </div>
          <list-actions
            :published="j.publish == 1"
            :edit="{ name: 'job-edit', params: { id: j.id } }"
            @toggle="toggle(j.id, $event)"
            @clone="clone(j.id, $event)"
            @destroy="destroy(j.id, $event)"
          />
        </div>
      </VueDraggable>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Jobs vorhanden...</p>
    </div>
  </page>
</template>
<script setup>
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useResourceList } from '@/composables/useResourceList';

const { items, loaded, fetch, toggle, clone, destroy, updateOrder } = useResourceList('jobs');
fetch();
</script>
