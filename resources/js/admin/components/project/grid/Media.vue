<template>
  <div>
    <figure :style="{ backgroundImage: 'url(' + getPreviewImage(element.image) + ')' }">
      <a
        href="javascript:;"
        class="btn-trash"
        @click.prevent="deleteImage(element.id, $event)"
      >Löschen</a>
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