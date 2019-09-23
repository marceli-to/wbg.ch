<template>
  <div>
    <figure>
      <a
        href="javascript:;"
        class="btn-trash"
        @click.prevent="deleteImage(element.id)"
      >Löschen</a>
      <img :src="getPreviewImage(element.image)" height="50" width="50">
      <figcaption v-if="element.caption">
        <strong>{{element.caption}}</strong>
      </figcaption>
    </figure>
  </div>
</template>
<script>
export default {
  props: {
    element: Object
  },

  methods: {
    
    deleteImage(id) {
      this.$parent.deleteImage(id);
    },

    getPreviewImage(image) {
      return `/media/${image}/sm`;
    },
  }
};
</script>