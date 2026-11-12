<template>
  <page>
    <h1>Texte</h1>
    <router-link :to="{ name: 'content-create' }" class="btn-add">
      <span>Hinzufügen</span>
    </router-link>
    <div class="list-items" v-if="items.length">
      <div
        v-for="c in items"
        :key="c.id"
        :class="[c.publish == 0 ? 'is-disabled' : '', 'list-item']"
        data-icons="3"
      >
        <div class="list-item-body">
          <span v-html="c.text"></span>
          <span class="bubble is-info" style="margin-top: 3px">Seite: <strong>{{ pageName(c.key) }}</strong></span>
        </div>
        <list-actions
          icons="3"
          :clone="false"
          :published="c.publish == 1"
          :edit="{ name: 'content-edit', params: { id: c.id } }"
          @toggle="toggle(c.id, $event)"
          @destroy="destroy(c.id, $event)"
        />
      </div>
    </div>
    <div v-else-if="loaded">
      <p>Es sind keine Texte vorhanden...</p>
    </div>
  </page>
</template>
<script setup>
import { ref } from 'vue';
import Page from '@/components/ui/Page.vue';
import ListActions from '@/components/ui/ListActions.vue';
import api from '@/lib/api';
import { useResourceList } from '@/composables/useResourceList';

const { items, loaded, fetch, toggle, destroy } = useResourceList('contents');
fetch();

const keys = ref([]);
api.get('contents/keys').then(({ data }) => (keys.value = data));

function pageName(key) {
  return keys.value.find((k) => k.key === key)?.page || key;
}
</script>
