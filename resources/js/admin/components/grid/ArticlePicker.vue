<template>
  <div>
    <h1>Artikel auswählen</h1>
    <div class="article-selector">
      <div v-for="a in articles" :key="a.id" @click.prevent="$emit('select', a.id)">
        <div class="article-selector__item">
          <div class="article-selector__text">
            <h2>{{ a.title }}</h2>
            <div class="item-text">{{ a.text }}</div>
          </div>
        </div>
      </div>
    </div>
    <p v-if="loaded && !articles.length">Es sind keine Artikel vorhanden...</p>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import api from '@/lib/api';

defineEmits(['select']);

const articles = ref([]);
const loaded = ref(false);
api.get('news').then(({ data }) => {
  articles.value = data;
  loaded.value = true;
});
</script>
