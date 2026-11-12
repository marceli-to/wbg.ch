<template>
  <page>
    <h1>Team</h1>
    <router-link :to="{ name: 'team-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-item-cards is-team" v-if="items.length">
      <VueDraggable v-model="items" @end="updateOrder()" ghost-class="draggable-ghost" draggable=".list-item-card">
        <div
          v-for="t in items"
          :key="t.id"
          :class="[t.publish == 0 ? 'is-disabled' : '', 'list-item-card']"
        >
          <div>
            <span>
              <strong>{{ t.firstname }} {{ t.name }}</strong>
              <em v-if="t.role">, {{ t.role }}</em>
            </span>
            <span v-if="t.email">{{ t.email }}</span>
            <span v-if="t.phone">{{ t.phone }}</span>
          </div>
          <list-actions
            wrapper-class="list-item-card__action"
            :published="t.publish == 1"
            :edit="{ name: 'team-edit', params: { id: t.id } }"
            @toggle="toggle(t.id, $event)"
            @clone="clone(t.id, $event)"
            @destroy="destroy(t.id, $event)"
          />
        </div>
      </VueDraggable>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Teammitglieder vorhanden...</p>
    </div>
  </page>
</template>
<script setup>
import { VueDraggable } from 'vue-draggable-plus';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useResourceList } from '@/composables/useResourceList';

const { items, loaded, fetch, toggle, clone, destroy, updateOrder } = useResourceList('team');
fetch();
</script>
