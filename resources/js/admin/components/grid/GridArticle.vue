<template>
  <div class="box-wrapper">
    <article>
      <a href="#" class="btn-trash" @click.prevent="$emit('delete', $event)">Löschen</a>
      <div>
        <div><strong>{{ element.title }}</strong></div>
        <div>{{ truncate(element.text, 200) }}</div>
        <div class="article__link" v-if="element.link">
          <a :href="element.link" target="_blank">{{ element.linkText }} ({{ element.link }})</a>
        </div>
      </div>
    </article>
  </div>
</template>
<script setup>
import { truncate } from '@/lib/helpers';

defineProps({ element: { type: Object, required: true } });
defineEmits(['delete']);
</script>
