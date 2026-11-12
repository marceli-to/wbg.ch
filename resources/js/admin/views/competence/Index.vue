<template>
  <page>
    <h1>Kompetenzen</h1>
    <router-link :to="{ name: 'competence-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <VueDraggable v-model="items" @end="updateOrder()" ghost-class="draggable-ghost">
        <div
          v-for="competence in items"
          :key="competence.id"
          :class="[competence.publish == 0 ? 'is-disabled' : '', 'list-item', 'is-sortable']"
        >
          <div class="list-item-body">
            <h3>{{ competence.title }}</h3>
            <span class="bubble is-info" v-if="competence.category">Verweis: <strong>{{ competence.category.name }}</strong></span>
          </div>
          <list-actions
            :published="competence.publish == 1"
            :edit="{ name: 'competence-edit', params: { id: competence.id } }"
            @toggle="toggle(competence.id, $event)"
            @clone="clone(competence.id, $event)"
            @destroy="destroy(competence.id, $event)"
          />
        </div>
      </VueDraggable>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Kompetenzen vorhanden...</p>
    </div>
  </page>
</template>
<script setup>
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useResourceList } from '@/composables/useResourceList';

// clone returns the copy without its category relation -> reload the list
const { items, loaded, fetch, toggle, clone, destroy, updateOrder } = useResourceList('competences', { refetchAfterClone: true });
fetch();
</script>
