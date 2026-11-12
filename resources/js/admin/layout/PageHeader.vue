<template>
  <div>
    <header class="site-header">
      <div>
        <router-link :to="{ name: 'projects' }" class="brand">WBG</router-link>
        <a href="#" @click.prevent="menuVisible = !menuVisible" class="icon-menu" aria-label="Menü"></a>
      </div>
    </header>
    <nav class="site-nav" :class="{ 'is-visible': menuVisible }">
      <div>
        <a href="#" @click.prevent="menuVisible = false" class="icon-close" aria-label="Menü schliessen"></a>
      </div>
      <ul>
        <li v-for="item in items" :key="item.name">
          <router-link :to="{ name: item.name }">{{ item.label }}</router-link>
        </li>
      </ul>
      <router-link :to="{ name: 'logout' }" class="icon-logout">Logout</router-link>
    </nav>
  </div>
</template>
<script setup>
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const items = [
  { name: 'home', label: 'Home' },
  { name: 'articles', label: 'Artikel' },
  { name: 'contents', label: 'Texte' },
  { name: 'projects', label: 'Projekte' },
  { name: 'team', label: 'Team' },
  { name: 'clients', label: 'Kunden' },
  { name: 'categories', label: 'Kategorien' },
  { name: 'competences', label: 'Kompetenzen' },
  { name: 'jobs', label: 'Jobs' },
];

const menuVisible = ref(false);
const route = useRoute();
watch(() => route.fullPath, () => (menuVisible.value = false));
</script>
