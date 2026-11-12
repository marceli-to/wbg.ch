<template>
  <div :class="box.cls">
    <div v-if="element">
      <div v-if="element.isMedia">
        <grid-media
          :element="element"
          :ratio="box.ratio"
          :is-home="isHome"
          @delete="$emit('delete', element, $event)"
          @cropped="$emit('cropped')"
        />
      </div>
      <div v-if="element.isArticle">
        <grid-article :element="element" @delete="$emit('delete', element, $event)" />
      </div>
    </div>
    <div v-else>
      <div class="box-buttons">
        <div>
          <a href="#" class="btn-add-media" @click.prevent="$emit('pick', 'image', box.position)">Bild auswählen</a>
        </div>
        <div>
          <a href="#" class="btn-add-article" @click.prevent="$emit('pick', 'article', box.position)">Artikel auswählen</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import GridMedia from '@/components/grid/GridMedia.vue';
import GridArticle from '@/components/grid/GridArticle.vue';

defineProps({
  box: { type: Object, required: true },
  element: { type: Object, default: null },
  isHome: { type: Boolean, default: false },
});
defineEmits(['pick', 'delete', 'cropped']);
</script>
