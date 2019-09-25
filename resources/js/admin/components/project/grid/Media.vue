<template>
  <div>
    <figure>
      <a
        href="javascript:;"
        class="btn-trash"
        @click.prevent="deleteImage(element.id, $event)"
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
    
    deleteImage(id,event) {
      let btn = event.target;
      this.$parent.deleteImage(id,btn);
    },

    getPreviewImage(image) {
      return `/media/${image}/sm`;
    },
  }
};
</script>