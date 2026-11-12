<template>
  <div>
    <page-header />
    <router-view :key="$route.fullPath" />
  </div>
</template>
<script setup>
import PageHeader from '@/layout/PageHeader.vue';
</script>
